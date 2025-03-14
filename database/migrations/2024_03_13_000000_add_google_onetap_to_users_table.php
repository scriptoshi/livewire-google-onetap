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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'googleId')) {
                $table->string('googleId')->nullable()->after('remember_token');
            }
            
            if (!Schema::hasColumn('users', 'google_avatar_url')) {
                $table->string('google_avatar_url')->nullable()
                    ->comment('URL to user google avatar image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'googleId')) {
                $table->dropColumn('googleId');
            }
            
            if (Schema::hasColumn('users', 'google_avatar_url')) {
                $table->dropColumn('google_avatar_url');
            }
        });
    }
};
