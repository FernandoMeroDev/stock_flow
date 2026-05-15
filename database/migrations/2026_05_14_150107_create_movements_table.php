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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->integer('count');
            $table->decimal('unitary_price', 8,3);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('movementable_id');
            $table->string('movementable_type', 1000);

            $table->foreignId('provider_id');
            $table->foreignId('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
