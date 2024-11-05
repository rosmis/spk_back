<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('password_reset_code')->nullable()->after('password');
            $table->timestamp('password_reset_code_expiry')->nullable()->after('password_reset_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_reset_code');
            $table->dropColumn('password_reset_code_expiry');
        });
    }
};
