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
        Schema::table('settings', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('settings', 'theme_palette')) {
                $table->string('theme_palette')->default('soft-minimal')->after('footer_text');
            }

            if (!Schema::hasColumn('settings', 'theme_colors')) {
                $table->json('theme_colors')->nullable()->after('theme_palette');
            }

            if (!Schema::hasColumn('settings', 'theme_custom_mode')) {
                $table->boolean('theme_custom_mode')->default(false)->after('theme_colors');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['theme_palette', 'theme_colors', 'theme_custom_mode']);
        });
    }
};
