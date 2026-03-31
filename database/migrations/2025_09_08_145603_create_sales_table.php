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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->integer('count', unsigned: true);
            $table->decimal('cash', 7, 2);
            $table->boolean('paid_in_cash')->default(true);
            $table->dateTime('saved_at');

            $table->bigInteger('presentation_id', unsigned: true);
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');

            $table->softDeletes();

            // Audit
            $table->bigInteger('created_by');
            $table->bigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
