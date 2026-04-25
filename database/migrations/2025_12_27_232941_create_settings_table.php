<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default(config('app.name'));
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->text('site_address')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('currency_symbol', 5)->default('$');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->text('footer_text')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            'site_name' => config('app.name'),
            'site_email' => 'admin@example.com',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'tax_rate' => 0,
            'shipping_cost' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
