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
        //supprimer la colonne whitelist et discord de la table users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('whiteList', 'discord_users', 'discord');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whiteList');
            $table->string('discord_users');
            $table->string('discord');
        });
    }
};
