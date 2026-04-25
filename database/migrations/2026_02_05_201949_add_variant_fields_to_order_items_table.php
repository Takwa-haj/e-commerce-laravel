<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['size_id']);
            $table->dropForeign(['color_id']);
            $table->dropColumn(['variant_id', 'size_id', 'color_id']);
        });
    }
};
