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
            $table->integer('role')->default(1);
            $table->boolean('account_active')->default(false);
            $table->timestamp('account_expires')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('last_failed_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_active', 'account_expires', 'failed_login_attempts', 'last_failed_login']);
        });
    }
};
