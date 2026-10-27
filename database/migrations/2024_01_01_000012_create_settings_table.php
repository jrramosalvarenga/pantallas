<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('critical_path_steps', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('phase');
            $table->string('title');
            $table->text('description');
            $table->date('target_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('status', ['pendiente', 'en_curso', 'completado', 'bloqueado'])->default('pendiente');
            $table->string('responsible')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('critical_path_steps');
        Schema::dropIfExists('settings');
    }
};
