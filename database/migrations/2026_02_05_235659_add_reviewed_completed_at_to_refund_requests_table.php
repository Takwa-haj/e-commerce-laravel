<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');
            $table->timestamp('completed_at')->nullable()->after('processed_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    public function down()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn(['reviewed_at', 'completed_at', 'cancelled_at']);
        });
    }
};
