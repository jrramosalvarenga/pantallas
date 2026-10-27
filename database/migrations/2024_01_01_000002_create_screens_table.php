<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('address');
            $table->string('gps_lat', 20)->nullable();
            $table->string('gps_lng', 20)->nullable();
            $table->integer('total_spaces')->default(24);
            $table->integer('spot_duration_seconds')->default(10);
            $table->integer('cycle_duration_seconds')->default(240);
            $table->string('operating_hours')->default('06:00-20:00');
            $table->decimal('investment_cost', 12, 2)->default(0);
            $table->decimal('book_value', 12, 2)->default(0);
            $table->integer('useful_life_months')->default(60);
            $table->date('purchase_date')->nullable();
            $table->decimal('monthly_depreciation', 12, 2)->default(0);
            $table->enum('status', ['activa', 'mantenimiento', 'inactiva'])->default('activa');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
