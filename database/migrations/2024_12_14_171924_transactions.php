<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('cryptoasset_id')
                  ->constrained('crypto_assets')
                  ->onDelete('cascade');
            
            // Transaction Details
            $table->enum('type', [
                Transaction::TYPE_DEPOSIT,
                Transaction::TYPE_WITHDRAWAL,
                Transaction::TYPE_SWAP,
                Transaction::TYPE_FUNDING,
                Transaction::TYPE_REFUND,
            ]);
            
            $table->string('which_crypto');  // btc, usdt_bep20, etc.
            $table->string('from_crypto')->nullable();  // For swaps
            $table->string('to_crypto')->nullable();    // For swaps
            
            // Blockchain Details
            $table->string('transaction_hash')->nullable();
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            
            // Amount Details
            $table->decimal('amount_in', 20, 8)->nullable();
            $table->decimal('amount_out', 20, 8)->nullable();
            $table->decimal('network_fee', 20, 8)->nullable();
            $table->decimal('rate', 20, 8)->nullable();
            
            // Status and Metadata
            $table->enum('status', [
                Transaction::STATUS_PENDING,
                Transaction::STATUS_PROCESSING,
                Transaction::STATUS_COMPLETED,
                Transaction::STATUS_FAILED,
                Transaction::STATUS_CANCELLED,
                Transaction::STATUS_REVERSED,
            ])->default(Transaction::STATUS_PENDING);
            
            $table->json('metadata')->nullable();
            $table->foreignId('related_transaction_id')
                  ->nullable()
                  ->constrained('transactions')
                  ->nullOnDelete();
            
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('which_crypto');
            $table->index('status');
            $table->index('transaction_hash');
            $table->index(['user_id', 'type', 'status']);
            $table->index(['cryptoasset_id', 'which_crypto']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};