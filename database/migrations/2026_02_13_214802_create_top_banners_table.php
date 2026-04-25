<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_top_banners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('top_banners', function (Blueprint $table) {
            $table->id();
            $table->string('message')->default('Livraison gratuite pour les commandes de plus de 50€');
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);

            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('pinterest_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();

            // Display options
            $table->string('background_color')->default('#0a0a0a');
            $table->string('text_color')->default('#ffffff');
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('top_banners');
    }
};
