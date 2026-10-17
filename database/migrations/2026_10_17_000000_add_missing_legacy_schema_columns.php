<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * The production database was built from another app's migrations, so these
 * tables exist there without columns this app's migrations would have created.
 * On a fresh database every column already exists and this is a no-op.
 */
return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'requires_password_update')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('requires_password_update')->default(false)->after('password');
            });
        }

        foreach ([config('permission.table_names.permissions'), config('permission.table_names.roles')] as $tableName) {
            if (! Schema::hasColumn($tableName, 'description')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('description')->nullable()->after('guard_name');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The columns belong to the original create migrations on a fresh database
    }
};
