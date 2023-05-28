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
        Schema::create('chat_user', function (Blueprint $table) {
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('chat_id')->references('id')->on('chats');
            $table->foreign('user_id')->references('id')->on('users');

            //$table->primary(['chat_id  ','user_id'], 'chat_id_user_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_user');
    }
};