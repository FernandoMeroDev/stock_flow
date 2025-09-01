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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->integer('count', unsigned: true);

            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->bigInteger('inventory_product_id', unsigned: true);
            $table->foreign('inventory_product_id')->references('id')->on('inventory_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
