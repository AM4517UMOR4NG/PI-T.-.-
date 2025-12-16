# Development Guidelines - PlayStation Rental System

## Route & Authorization Patterns

### ⚠️ IMPORTANT: Multi-Role Access Routes

When creating routes that need to be accessed by **multiple roles** (e.g., both customers AND admins):

**❌ WRONG - Too Restrictive:**
```php
Route::get('some/resource', [Controller::class, 'method'])
    ->middleware(['auth', 'can:access-admin']); // Only admins!
```

**✅ CORRECT - Use Controller-Level Auth:**
```php
// Route: Allow all authenticated users
Route::get('some/resource', [Controller::class, 'method'])
    ->middleware(['auth']);

// Controller: Check ownership or role
public function method($id) {
    $resource = Resource::find($id);
    $user = auth()->user();
    
    // Allow if owner OR admin/staff
    if ($resource->user_id !== $user->id && !$user->isAdmin() && !$user->isKasir()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    // ... proceed
}
```

---

## Midtrans Integration Patterns

### 1. Webhook Reliability
- **Always** exempt webhook routes from CSRF (`bootstrap/app.php`)
- **Never** rely solely on webhooks - implement manual status check fallback

### 2. Signature Verification
- Use **exact string** from `$notification->gross_amount` (Midtrans sends as string like "10000.00")
- Don't cast to int before hashing

### 3. Payment Record Recovery
- Always try to extract `rental_id` from `order_id` string pattern
- Create Payment record on-the-fly if missing during webhook/status check

### 4. Order ID Patterns
```
ORD-{YYYYMMDD}-{rental_id}-{unique}   // Standard pattern
RENTAL-{rental_id}-{timestamp}         // Alternative pattern
```

---

## Input Validation Checklist

### Numeric Fields (Price, Stock, Quantity)
- [ ] `type="number"`
- [ ] `min="0"` or `min="1"` as appropriate
- [ ] `step="1"` for integers
- [ ] `oninput="this.value = this.value.replace(/[^0-9]/g, '')"` for strict numeric

### Phone Numbers
- [ ] Pattern: `^\\+62[0-9]{9,13}$`
- [ ] Placeholder showing expected format

### Addresses
- [ ] Minimum word count validation (10 words)
- [ ] Maximum character limit (500)

---

## Common Bugs to Avoid

| Bug | Prevention |
|-----|------------|
| Column name mismatch | Use model attributes, not raw SQL column names |
| Null reference | Always check `->first()` result before using |
| Debug endpoints open | Add auth middleware + ownership check |
| CSRF blocking webhooks | Exclude in `bootstrap/app.php` |
