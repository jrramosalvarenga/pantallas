<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screen_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['preventivo', 'correctivo', 'emergencia']);
            $table->string('title');
            $table->text('description');
            $table->string('technician')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->date('next_maintenance_date')->nullable();
            $table->enum('status', ['programado', 'en_curso', 'completado', 'cancelado'])->default('completado');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
