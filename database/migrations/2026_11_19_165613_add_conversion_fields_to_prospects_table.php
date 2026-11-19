<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->decimal('deal_amount', 10, 2)->nullable()->after('plan_of_interest');
            $table->string('payment_frequency')->nullable()->after('deal_amount');
            $table->date('payment_start_date')->nullable()->after('payment_frequency');
            $table->date('payment_end_date')->nullable()->after('payment_start_date');
            $table->string('ad_video_path')->nullable()->after('logo_path');
            $table->string('ad_video_original_name')->nullable()->after('ad_video_path');
            $table->foreignId('converted_to_client_id')->nullable()->after('ad_video_original_name')
                ->constrained('clients')->nullOnDelete();
            $table->timestamp('converted_at')->nullable()->after('converted_to_client_id');
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_to_client_id');
            $table->dropColumn([
                'deal_amount',
                'payment_frequency',
                'payment_start_date',
                'payment_end_date',
                'ad_video_path',
                'ad_video_original_name',
                'converted_at',
            ]);
        });
    }
};
