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
        Schema::create('api_users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("client_id")->unique();
            $table->string("token")->unique();
            $table->string("role");
            $table->string("email")->unique();
            $table->string("users_id")->unique();
            $table->string("visible")->default("true");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_users');
    }
};
