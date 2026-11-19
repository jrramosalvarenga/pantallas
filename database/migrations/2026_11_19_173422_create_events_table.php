<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('screen_id')->nullable()->constrained()->nullOnDelete();
            $table->string('venue');
            $table->string('venue_address')->nullable();
            $table->foreignId('municipality_id')->nullable()->constrained()->nullOnDelete();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->enum('type', [
                'boda', 'quinceanera', 'cumpleanos', 'corporativo',
                'concierto', 'festival', 'deportivo', 'religioso',
                'politico', 'graduacion', 'feria', 'otro',
            ])->default('otro');
            $table->enum('status', [
                'cotizado', 'confirmado', 'en_curso', 'completado', 'cancelado',
            ])->default('cotizado');
            $table->decimal('quoted_price', 12, 2)->nullable();
            $table->decimal('final_price', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->boolean('deposit_paid')->default(false);
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('technical_notes')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
