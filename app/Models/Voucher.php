<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'voucher_type',
        'voucher_date',
        'voucher_day',
        'prepared_by',
        'approved_by',
        'description',
        'total_debit',
        'total_credit',
    ];

    protected $casts = [
        'voucher_date' => 'date',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(VoucherDetails::class, 'voucher_id');
    }
}