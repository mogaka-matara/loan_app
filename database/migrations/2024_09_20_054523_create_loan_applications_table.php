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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('loan_product_id')->constrained();
            $table->decimal('amount_applied', 10, 2);
            $table->decimal('amount_disbursed', 10, 2)->nullable();
            $table->decimal('amount_repaid', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'disbursed', 'repaid'])->default('pending');
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
