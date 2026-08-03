<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id', 'buyer_id', 'agent_id', 'booking_number', 'status',
        'booking_date', 'expiry_date', 'contract_signed_at', 'handover_date',
        'unit_price', 'discount_amount', 'final_price', 'booking_amount',
        'payment_plan_id', 'payment_status', 'notes', 'owner_id', 'created_by',
    ];

    public function unit()      { return $this->belongsTo(unit::class); }
    public function buyer()     { return $this->belongsTo(Buyer::class); }
    public function agent()     { return $this->belongsTo(Agent::class); }
    public function paymentPlan() { return $this->belongsTo(PaymentPlan::class); }
}
