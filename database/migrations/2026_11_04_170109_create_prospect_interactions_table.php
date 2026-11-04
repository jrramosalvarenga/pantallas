<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospect_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('notes')->nullable();
            $table->string('result')->nullable();
            $table->dateTime('interaction_date');
            $table->date('next_followup_date')->nullable();
            $table->timestamps();

            $table->index(['prospect_id', 'interaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_interactions');
    }
};
