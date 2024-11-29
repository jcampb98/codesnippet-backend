<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(config('database.default') === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
        else {
            // For SQLite or other database's
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamps();
            });

            DB::statement('INSERT INTO users_temp SELECT id, name, email, created_at, updated_at FROM users');

            Schema::drop('users');
            Schema::rename('users_temp', 'users');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
        });
    }
};
