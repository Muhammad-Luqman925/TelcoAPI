<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        // === 1. DATA PROFIL (Administrasi) ===
        'name',
        'phone_number', // Tadi kita tambah ini di migration
        'age',
        'gender',
        'location',
        'occupation',
        'status',       // active/churned
        'join_date',
        'clv_segment',

        // === 2. DATA TEKNIS (Wajib Sama dengan API ML) ===
        'avg_call_duration',
        'avg_data_usage_gb',  // Dulu: avg_data_usage
        'plan_type',          // Dulu: current_plan
        'sms_freq',           // Dulu: avg_sms_count
        
        // === 3. FIELD BARU (Requirement ML) ===
        'device_brand',
        'monthly_spend',
        'pct_video_usage',
        'complaint_count',
        'topup_freq',
        'travel_score',
    ];

    // Pastikan ini ada di Customer.php
public function transactions()
{
    return $this->hasMany(Transaction::class);
}
}