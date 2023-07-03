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
        Schema::create('pirep', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("users_id");
            $table->string("departure");
            $table->string("arrival");
            $table->string("aircraft");
            $table->longText("fpl");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
