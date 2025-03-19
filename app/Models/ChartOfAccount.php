<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';

    use HasFactory;
    protected $fillable = [
        'account_code',
        'account_type',
        'account_section',
        'account_subsection',
        'account_name',
    ];
}
