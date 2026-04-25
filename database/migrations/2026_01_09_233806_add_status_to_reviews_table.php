<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add new columns
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending')->after('comment');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('declined_at')->nullable()->after('approved_at');

            // Keep the old 'approved' column for backward compatibility
            // Or remove it if you want: $table->dropColumn('approved');
        });

        // Update existing records: set status based on approved column
        DB::table('reviews')->where('approved', 1)->update([
            'status' => 'approved',
            'approved_at' => DB::raw('created_at')
        ]);

        DB::table('reviews')->where('approved', 0)->update([
            'status' => 'pending'
        ]);
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at', 'declined_at']);
        });
    }
};
