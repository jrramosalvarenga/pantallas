<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('monthly_amount', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_frequency', ['mensual', 'trimestral', 'semestral', 'anual'])->default('mensual');
            $table->enum('status', ['activo', 'vencido', 'cancelado', 'suspendido'])->default('activo');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('contract_screen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('screen_id')->constrained()->cascadeOnDelete();
            $table->integer('space_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_screen');
        Schema::dropIfExists('contracts');
    }
};
