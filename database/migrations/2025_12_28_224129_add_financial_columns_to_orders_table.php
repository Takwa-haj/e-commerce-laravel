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
             $table->string('currency', 10)->default('EUR')->after('total');
            $table->string('currency_symbol', 5)->default('€')->after('currency');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('currency_symbol');
            $table->decimal('shipping', 10, 2)->default(0)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
              $table->dropColumn(['currency', 'currency_symbol', 'tax_rate', 'shipping']);
        });
    }
};
