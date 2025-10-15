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
        Schema::create('arrivals', function (Blueprint $table) {
             $table->id();
            $table->decimal('totalSpend', 12, 2);
            $table->decimal('purchaseCost', 12, 2);
            $table->decimal('shippingCost', 10, 2);
            $table->string('supplier');
            $table->foreignId('country_id')->constrained()->onDelete('restrict');
            $table->integer('totalItems');
            $table->dateTime('arrival_date');
            $table->string('sku')->nullable()->unique();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['country_id', 'status', 'arrival_date']);
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivals');
    }
};
