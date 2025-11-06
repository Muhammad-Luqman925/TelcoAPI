<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recommendations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
    $table->foreignId('recommended_by')->nullable()->constrained('users')->onDelete('set null');
    $table->boolean('is_overridden')->default(false);
    $table->foreignId('override_product_id')->nullable()->constrained('products')->onDelete('set null');
    $table->timestamps();
});

    }

    public function down(): void {
        Schema::dropIfExists('recommendations');
    }
};
