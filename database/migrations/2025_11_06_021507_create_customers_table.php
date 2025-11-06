<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('location')->nullable();
            $table->string('occupation')->nullable();
            $table->string('current_plan')->nullable();
            $table->enum('status', ['active', 'churned'])->default('active');
            $table->date('join_date')->nullable();
            $table->float('avg_data_usage')->nullable(); // in GB
            $table->float('avg_call_duration')->nullable(); // in minutes
            $table->integer('avg_sms_count')->nullable();
            $table->string('clv_segment')->nullable(); // customer lifetime value segment
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('customers');
    }
};
