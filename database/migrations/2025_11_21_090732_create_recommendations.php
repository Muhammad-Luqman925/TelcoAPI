<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('recommendations', function (Blueprint $table) {
        $table->id();

        // 1. DATA RELASI UTAMA (Fitur Lama Luqman)
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        
        // Mencatat User/Admin yang melakukan generate (PENTING)
        // Pastikan tabel 'users' sudah di-migrate duluan ya
        $table->foreignId('recommended_by')->nullable()->constrained('users')->nullOnDelete();

        // 2. HASIL ML (TOP 5) - Mengikuti Data JSON ML
        // Kita simpan skor murni (0.0 - 1.0)
        
        // Rank 1
        $table->foreignId('rank_1_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->float('rank_1_score')->default(0); 

        // Rank 2
        $table->foreignId('rank_2_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->float('rank_2_score')->default(0);

        // Rank 3
        $table->foreignId('rank_3_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->float('rank_3_score')->default(0);
        
        // Rank 4
        $table->foreignId('rank_4_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->float('rank_4_score')->default(0);

        // Rank 5
        $table->foreignId('rank_5_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->float('rank_5_score')->default(0);

        // 3. FITUR MANUAL OVERRIDE (Fitur Lama Luqman)
        // Ini fitur bagus, wajib dipertahankan!
        $table->boolean('is_overridden')->default(false);
        $table->foreignId('override_product_id')->nullable()->constrained('products')->nullOnDelete();
        $table->text('override_reason')->nullable(); // Alasan kenapa di-override (opsional)

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
