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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->decimal('value', 7, 2);
            $table->timestamps();
            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('updated_by_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
