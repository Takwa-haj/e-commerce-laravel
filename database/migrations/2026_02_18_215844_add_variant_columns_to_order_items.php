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
        Schema::table('order_items', function (Blueprint $table) {
            // Ajouter variant_id si n'existe pas
            if (!Schema::hasColumn('order_items', 'variant_id')) {
                $table->foreignId('variant_id')
                      ->nullable()
                      ->after('product_id')
                      ->constrained('product_variants')
                      ->nullOnDelete();
            }

            // Ajouter size_id si n'existe pas
            if (!Schema::hasColumn('order_items', 'size_id')) {
                $table->foreignId('size_id')
                      ->nullable()
                      ->after('variant_id')
                      ->constrained('sizes')
                      ->nullOnDelete();
            }

            // Ajouter color_id si n'existe pas
            if (!Schema::hasColumn('order_items', 'color_id')) {
                $table->foreignId('color_id')
                      ->nullable()
                      ->after('size_id')
                      ->constrained('colors')
                      ->nullOnDelete();
            }

            // Ajouter un champ pour marquer si le stock a été mis à jour
            if (!Schema::hasColumn('order_items', 'stock_updated')) {
                $table->boolean('stock_updated')
                      ->default(false)
                      ->after('color_id')
                      ->comment('Indique si le stock a été décrémenté pour cet article');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Supprimer les contraintes étrangères d'abord
            if (Schema::hasColumn('order_items', 'variant_id')) {
                $table->dropForeign(['variant_id']);
            }
            if (Schema::hasColumn('order_items', 'size_id')) {
                $table->dropForeign(['size_id']);
            }
            if (Schema::hasColumn('order_items', 'color_id')) {
                $table->dropForeign(['color_id']);
            }

            // Supprimer les colonnes
            $columns = ['variant_id', 'size_id', 'color_id', 'stock_updated'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
