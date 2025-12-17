<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DamageReport;

class DamageReportResult extends Mailable
{
    use Queueable, SerializesModels;

    public $damageReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DamageReport $damageReport)
    {
        $this->damageReport = $damageReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Hasil Review Laporan Kerusakan - #' . $this->damageReport->id)
                    ->view('emails.damage_report_result');
    }
}
