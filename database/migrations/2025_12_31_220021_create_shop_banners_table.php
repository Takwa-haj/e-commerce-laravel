<?php
// database/migrations/xxxx_xx_xx_create_shop_banners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shop_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->string('button_text')->default('Shop Now');
            $table->string('button_link');
            $table->string('background_color')->default('#f5e6e0');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_banners');
    }
};
