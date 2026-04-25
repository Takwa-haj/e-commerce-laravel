<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('country');
            $table->string('address_type')->default('shipping')->after('phone');
            $table->boolean('is_default_shipping')->default(false)->after('address_type');
            $table->boolean('is_default_billing')->default(false)->after('is_default_shipping');

            // Remove the old is_default column if it exists
            if (Schema::hasColumn('user_addresses', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address_type', 'is_default_shipping', 'is_default_billing']);
            $table->boolean('is_default')->default(false);
        });
    }
};
