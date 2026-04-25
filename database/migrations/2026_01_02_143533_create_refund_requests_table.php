<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('order_item_ids'); // Array of order item IDs
            $table->json('quantities')->nullable(); // Quantities to refund for each item
            $table->string('reason_type'); // defective, wrong, damaged, other
            $table->text('reason_details');
            $table->text('additional_info')->nullable();
            $table->json('attachments')->nullable(); // Array of file paths
            $table->decimal('requested_amount', 10, 2);
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('stripe_refund_id')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Pivot table for refund request items
        Schema::create('refund_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('refunded_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('refund_request_items');
        Schema::dropIfExists('refund_requests');
    }
};
