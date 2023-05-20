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
        Schema::create('loggins', function (Blueprint $table) {
            $table->id();
            $table->text("type");
            $table->text("message");
            $table->text("user");
            $table->text("ip")->nullable();
            $table->text("users_admin_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loggins');
    }
};
