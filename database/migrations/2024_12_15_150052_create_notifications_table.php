<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('type'); // e.g., 'password_reset', 'kyc', 'send_crypto', 'receive_crypto', 'card_funding', 'welcome'
        $table->string('title');
        $table->text('message');
        $table->boolean('is_read')->default(false);
        $table->json('extra_data')->nullable(); // For storing additional context
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('notifications');
}
};
