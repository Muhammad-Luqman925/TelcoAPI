<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();

        // === 1. DATA PROFIL (Dari Desain Awal Luqman) ===
        // Tetap kita pakai karena penting buat Admin Panel
        $table->string('name');
        $table->integer('age')->nullable();
        $table->string('gender')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('location')->nullable();
        $table->string('occupation')->nullable();
        $table->string('status')->default('active'); // active / churned
        $table->date('join_date')->nullable();
        $table->string('clv_segment')->nullable();

        // === 2. DATA TEKNIS (Wajib Sesuai API ML) ===
        // Nama kolom disesuaikan biar codingan Service bersih
        
        $table->float('avg_call_duration')->default(0);      // SAMA
        $table->float('avg_data_usage_gb')->default(0);      // GANTI NAMA (dulu: avg_data_usage)
        $table->string('plan_type')->default('Prepaid');     // GANTI NAMA (dulu: current_plan)
        $table->integer('sms_freq')->default(0);             // GANTI NAMA (dulu: avg_sms_count)
        
        // Kolom Tambahan (Requirement ML yang belum ada di desain lama)
        $table->string('device_brand')->nullable();          // Misal: Samsung, Apple
        $table->decimal('monthly_spend', 12, 2)->default(0); // Total tagihan sebulan
        $table->float('pct_video_usage')->default(0);        // 0.0 - 1.0
        $table->integer('complaint_count')->default(0);      // Jumlah komplain
        $table->integer('topup_freq')->default(0);           // Sering isi pulsa?
        $table->float('travel_score')->default(0);           // Sering traveling?

        $table->timestamps();
    });
}

    public function down(): void {
        Schema::dropIfExists('customers');
    }
};
