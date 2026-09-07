<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storage_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('qty')->default(0);
            $table->timestamps();
            $table->unique(['storage_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_products');
    }
};
