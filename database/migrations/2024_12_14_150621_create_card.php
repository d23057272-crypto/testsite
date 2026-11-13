<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('crypto_asset_id')->constrained()->onDelete('cascade');
            $table->string('card_number')->unique();
            $table->string('card_holder');
            $table->string('expiry_month', 2);
            $table->string('expiry_year', 2);
            $table->string('cvv', 4);
            $table->text('billing_address');
            $table->string('zip_code');
            $table->enum('status', ['active', 'inactive', 'frozen'])->default('inactive');
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('card_type', ['virtual', 'physical'])->default('virtual');
            $table->string('last_four', 4);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
};