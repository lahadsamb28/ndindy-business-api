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
        Schema::create('items', function (Blueprint $table) {
             $table->id();
            $table->foreignId('arrivals_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('condition')->nullable();
            $table->string('imei')->nullable()->unique();
            $table->integer('battery_health')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['arrivals_id', 'product_id']);
            $table->index(['brand', 'model']);
            $table->index('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
