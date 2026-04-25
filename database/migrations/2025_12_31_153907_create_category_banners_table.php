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
        Schema::create('category_banners', function (Blueprint $table) {
           $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->decimal('price_from', 10, 2)->nullable();
            $table->string('price_text')->nullable();
            $table->string('button_text')->default('Découvrir');
            $table->string('button_link');
            $table->enum('position', ['left', 'right'])->default('left');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_banners');
    }
};
