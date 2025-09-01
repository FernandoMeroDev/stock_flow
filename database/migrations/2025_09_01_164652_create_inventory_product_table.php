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
        Schema::create('inventory_product', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->decimal('price', 7, 3);
            $table->integer('incoming_count', unsigned: true);
            $table->integer('outgoing_count', unsigned: true);

            $table->bigInteger('product_id', unsigned: true);
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_product_');
    }
};
