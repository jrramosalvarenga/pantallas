<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['individual', 'combo']);
            $table->foreignId('ad_type_id')->constrained()->cascadeOnDelete();
            $table->integer('screens_count')->default(1);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->integer('duration_months')->default(1);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
