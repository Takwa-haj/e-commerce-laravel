<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            // Add new columns
            $table->text('description')->nullable()->after('subtitle');
            $table->string('button_text')->default('Explorer la collection')->after('link');
            $table->decimal('price', 10, 2)->nullable()->after('button_text');
            $table->string('price_text')->nullable()->after('price');
            $table->string('featured_image')->nullable()->after('image');
            $table->enum('type', ['home', 'shop'])->default('home')->after('featured_image');
            $table->string('background_color')->default('#0a0a0a')->after('type');
            $table->string('text_position')->default('left')->after('background_color');
            $table->boolean('is_active')->default(true)->after('text_position');
            $table->integer('order')->default(0)->after('is_active');

            // Remove old columns if they exist
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            // Restore old columns
            $table->string('status')->nullable();

            // Remove new columns
            $table->dropColumn([
                'description',
                'button_text',
                'price',
                'price_text',
                'featured_image',
                'type',
                'background_color',
                'text_position',
                'is_active',
                'order'
            ]);
        });
    }
};
