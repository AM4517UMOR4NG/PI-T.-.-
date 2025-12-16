<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        try {
            // Get notification data from Midtrans
            $notification = $this->midtransService->handleNotification();
            
            // Extract notification data
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? 'accept';
            $paymentType = $notification->payment_type;
            $grossAmount = $notification->gross_amount;
            $transactionId = $notification->transaction_id;
            $transactionTime = $notification->transaction_time;
            $statusCode = $notification->status_code;

            // Log notification for debugging
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'amount' => $grossAmount,
                'raw_body' => $request->getContent(), // Log raw body for signature debug
            ]);

            // Verify signature hash
            $serverKey = config('midtrans.server_key');
            $signatureKey = $notification->signature_key ?? '';
            
            // Midtrans sends gross_amount as string (e.g. "10000.00"). 
            // We must use it EXACTLY as received for signature verification.
            $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            
            if ($hashed !== $signatureKey) {
                Log::warning('Invalid Midtrans signature', [
                    'order_id' => $orderId,
                    'expected' => $hashed,
                    'received' => $signatureKey,
                    'input_str' => $orderId . $statusCode . $grossAmount . '[HIDDEN_KEY]'
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            DB::beginTransaction();

            // Find or create payment record
            // We search by order_id. If not found, we create a new one.
            // IMPORTANT: If creating, we must try to find the rental_id from the order_id string
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                // Payment record missing - this happens if webhook arrives before our app created the record
                // or if the record creation failed but Midtrans flow continued.
                
                // Try to extract rental_id from order_id
                $rentalId = null;
                
                // Pattern 1: ORD-YYYYMMDD-{rental_id}-{uniqid}
                if (preg_match('/^ORD-\d{8}-(\d+)-/', $orderId, $matches)) {
                    $rentalId = $matches[1];
                } 
                // Pattern 2: RENTAL-{rental_id}-{timestamp}
                elseif (preg_match('/^RENTAL-(\d+)-/', $orderId, $matches)) {
                    $rentalId = $matches[1];
                }

                $payment = Payment::create([
                    'order_id' => $orderId,
                    'rental_id' => $rentalId,
                    'method' => 'midtrans',
                    'amount' => $grossAmount,
                    'transaction_status' => $transactionStatus,
                ]);

                Log::info('Created new Payment record from webhook', [
                    'order_id' => $orderId,
                    'rental_id' => $rentalId,
                ]);
            }

            // Update payment with Midtrans data
            $notificationData = [
                'transaction_id' => $transactionId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'transaction_time' => $transactionTime,
                'fraud_status' => $fraudStatus,
            ];
            
            $payment->updateFromMidtrans($notificationData);

            // Update rental status based on payment status
            if ($payment->rental_id) {
                $rental = Rental::find($payment->rental_id);
                
                if ($rental) {
                    $this->updateRentalStatus($rental, $transactionStatus, $fraudStatus, $grossAmount);
                } else {
                     Log::error('Rental not found for payment', ['rental_id' => $payment->rental_id]);
                }
            } else {
                Log::error('Payment has no rental_id, cannot update rental status', ['payment_id' => $payment->id]);
            }

            DB::commit();

            return response()->json(['message' => 'Notification processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error processing Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Update rental status based on payment status
     */
    /**
     * Update rental status based on payment status
     */
    protected function updateRentalStatus(Rental $rental, string $transactionStatus, string $fraudStatus, $amount)
    {
        Log::info('Updating rental status from Midtrans', [
            'rental_id' => $rental->id,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'amount' => $amount,
        ]);

        // Handle different transaction statuses
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Payment captured and verified (Credit Card)
                $this->handleSuccessfulPayment($rental, $amount);
                
                Log::info('✅ Rental PAID - Status: menunggu_pengantaran (capture)', [
                    'rental_id' => $rental->id,
                    'paid_amount' => $amount
                ]);
            } else {
                // Fraud detected
                $rental->status = 'cancelled';
                $rental->save();
                // No need to restore stock as it wasn't decremented yet
                
                Log::warning('⚠️ Rental CANCELLED - Fraud detected', [
                    'rental_id' => $rental->id,
                    'fraud_status' => $fraudStatus
                ]);
            }
        } elseif ($transactionStatus == 'settlement') {
            // Payment settled (E-wallet, Bank Transfer, etc)
            $this->handleSuccessfulPayment($rental, $amount);
            
            Log::info('✅ Rental PAID - Status: menunggu_pengantaran (settlement)', [
                'rental_id' => $rental->id,
                'paid_amount' => $amount
            ]);
        } elseif ($transactionStatus == 'pending') {
            // Payment pending (waiting for customer to complete)
            $rental->status = 'pending';
            $rental->save();
            
            Log::info('⏳ Rental PENDING - Waiting for payment', [
                'rental_id' => $rental->id
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // Payment failed/cancelled/expired
            $rental->status = 'cancelled';
            $rental->save();
            // No need to restore stock as it wasn't decremented yet
            
            Log::info('❌ Rental CANCELLED - Payment ' . $transactionStatus, [
                'rental_id' => $rental->id,
                'reason' => $transactionStatus
            ]);
        }
    }

    protected function handleSuccessfulPayment(Rental $rental, $amount)
    {
        DB::transaction(function () use ($rental, $amount) {
            // Update rental status to waiting for delivery
            // Waktu sewa (start_at) akan diupdate ketika user konfirmasi penerimaan
            $rental->status = 'menunggu_pengantaran';
            $rental->paid = $amount;
            $rental->save();

            // Decrement stock here (stok dikurangi setelah pembayaran sukses)
            foreach ($rental->items as $item) {
                if ($item->rentable) {
                    $rentable = $item->rentable;
                    
                    // Lock for update to prevent race conditions
                    if ($rentable instanceof \App\Models\UnitPS) {
                        $rentable->stock -= $item->quantity;
                    } else {
                        $rentable->stok -= $item->quantity;
                    }
                    $rentable->save();
                }
            }
        });

        Log::info('✅ Payment successful - Status: menunggu_pengantaran', [
            'rental_id' => $rental->id,
            'rental_kode' => $rental->kode,
            'paid_amount' => $amount,
        ]);
    }

    /**
     * Restore stock when rental is cancelled
     * (Deprecated: Stock is now only decremented on payment)
     */
    protected function restoreStock(Rental $rental)
    {
        // No-op
    }

    /**
     * Check payment status manually (for debugging)
     */
    public function checkStatus($orderId)
    {
        try {
            $status = $this->midtransService->getTransactionStatus($orderId);
            
            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();
            
            // Authorization Check: Ensure user owns this payment/rental
            if ($payment && $payment->rental_id) {
                $rental = Rental::find($payment->rental_id);
                if ($rental) {
                    $user = auth()->user();
                    // Allow if user is owner of rental OR user is admin/staff
                    if ($rental->user_id !== $user->id && !$user->isAdmin() && !$user->isKasir()) {
                        return response()->json(['message' => 'Unauthorized'], 403);
                    }
                }
            } else {
                // If payment not found yet, try to parse rental ID from order ID to check auth
                $rentalId = null;
                if (preg_match('/^ORD-\d{8}-(\d+)-/', $orderId, $matches) || preg_match('/^RENTAL-(\d+)-/', $orderId, $matches)) {
                    $rentalId = $matches[1];
                    $rental = Rental::find($rentalId);
                    if ($rental) {
                        $user = auth()->user();
                        if ($rental->user_id !== $user->id && !$user->isAdmin() && !$user->isKasir()) {
                            return response()->json(['message' => 'Unauthorized'], 403);
                        }
                    }
                }
            }
            
            if (!$payment) {
                // Same recovery logic as notification
                $rentalId = null;
                
                if (preg_match('/^ORD-\d{8}-(\d+)-/', $orderId, $matches)) {
                    $rentalId = $matches[1];
                } elseif (preg_match('/^RENTAL-(\d+)-/', $orderId, $matches)) {
                    $rentalId = $matches[1];
                }

                $payment = Payment::create([
                    'order_id' => $orderId,
                    'rental_id' => $rentalId,
                    'method' => 'midtrans',
                    'amount' => $status->gross_amount,
                    'transaction_status' => $status->transaction_status,
                ]);

                Log::info('Created new Payment record from checkStatus', [
                    'order_id' => $orderId,
                    'rental_id' => $rentalId,
                ]);
            }
            
            if ($payment) {
                // Update payment data
                $payment->updateFromMidtrans([
                    'transaction_id' => $status->transaction_id,
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type,
                    'gross_amount' => $status->gross_amount,
                    'transaction_time' => $status->transaction_time,
                    'fraud_status' => $status->fraud_status ?? 'accept',
                ]);

                // Update rental status
                if ($payment->rental_id) {
                    $rental = Rental::find($payment->rental_id);
                    if ($rental) {
                        $this->updateRentalStatus(
                            $rental, 
                            $status->transaction_status, 
                            $status->fraud_status ?? 'accept', 
                            $status->gross_amount
                        );
                    } else {
                         Log::error('Rental not found for payment in checkStatus', ['rental_id' => $payment->rental_id]);
                    }
                }
            }
            
            return response()->json([
                'order_id' => $orderId,
                'status' => $status,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking Midtrans status manually', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
