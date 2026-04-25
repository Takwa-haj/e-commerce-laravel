<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('refund_status', ['none', 'requested', 'approved', 'rejected', 'processing', 'completed'])->default('none');
            $table->text('refund_reason')->nullable();
            $table->timestamp('refund_requested_at')->nullable();
            $table->timestamp('refund_processed_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('stripe_refund_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refund_reason',
                'refund_requested_at',
                'refund_processed_at',
                'refund_amount',
                'stripe_refund_id'
            ]);
        });
    }
};
