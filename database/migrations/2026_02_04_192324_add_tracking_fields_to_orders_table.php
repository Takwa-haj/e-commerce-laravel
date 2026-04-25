<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add tracking fields
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('delivered_date');
            }

            if (!Schema::hasColumn('orders', 'carrier')) {
                $table->string('carrier')->nullable()->after('tracking_number');
            }

            if (!Schema::hasColumn('orders', 'estimated_delivery')) {
                $table->date('estimated_delivery')->nullable()->after('carrier');
            }

            if (!Schema::hasColumn('orders', 'confirmed_date')) {
                $table->timestamp('confirmed_date')->nullable()->after('estimated_delivery');
            }

            if (!Schema::hasColumn('orders', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('cancelled_date');
            }

            if (!Schema::hasColumn('orders', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('is_archived');
            }

            // Add shipping field if missing
            if (!Schema::hasColumn('orders', 'shipping')) {
                $table->decimal('shipping', 10, 2)->default(0)->after('discount');
            }

            // Add transaction relationship field
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->unsignedBigInteger('transaction_id')->nullable()->after('user_id');
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop tracking fields
            $table->dropColumn([
                'tracking_number',
                'carrier',
                'estimated_delivery',
                'confirmed_date',
                'is_archived',
                'archived_at',
                'shipping'
            ]);

            // Drop foreign key first, then column
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};
