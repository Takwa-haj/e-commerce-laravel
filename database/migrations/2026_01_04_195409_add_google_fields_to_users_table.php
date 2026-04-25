<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add Google OAuth fields if they don't exist
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }

            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('google_id');
            }

            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('provider_id');
            }

            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            // Make password nullable for social login users
            $table->string('password')->nullable()->change();

            // Add indexes for better performance
            $table->index('google_id');
            $table->index('provider');
            $table->index('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();

            $table->dropColumn(['google_id', 'provider', 'provider_id', 'avatar']);
            $table->dropColumn('email_verified_at');
        });
    }
};
