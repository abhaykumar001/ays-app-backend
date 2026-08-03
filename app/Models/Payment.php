<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_unit_id', 'unit_id', 'amount', 'payment_type',
        'payment_date', 'due_date', 'status', 'payment_method',
        'reference_number', 'notes',
    ];

    public function unit() { return $this->belongsTo(unit::class); }
}
