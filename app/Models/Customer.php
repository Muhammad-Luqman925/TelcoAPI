<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'gender',
        'location',
        'occupation',
        'current_plan',
        'status',
        'join_date',
        'avg_data_usage',
        'avg_call_duration',
        'avg_sms_count',
        'clv_segment',
    ];
}
