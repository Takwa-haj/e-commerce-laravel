<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->json('stripe_metadata')->nullable();
            $table->string('payment_status')->default('pending');
            $table->text('payment_error')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_client_secret',
                'stripe_payment_method',
                'stripe_charge_id',
                'stripe_metadata',
                'payment_status',
                'payment_error',
                'payment_completed_at'
            ]);
        });
    }
};
