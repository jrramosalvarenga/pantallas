<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('category')->nullable();
            $table->foreignId('municipality_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('origin')->nullable();
            $table->string('data_source')->nullable();
            $table->string('nearby_screen')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->default('por_visitar');
            $table->string('plan_of_interest')->nullable();
            $table->date('visit_date')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('priority');
            $table->index('next_followup_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
