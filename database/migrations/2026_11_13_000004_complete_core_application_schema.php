<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addMissingUserColumns();
        $this->createSettingsTable();
        $this->createCryptoAssetsTable();
        $this->createSupportTicketTables();
    }

    private function addMissingUserColumns(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (!Schema::hasColumn('users', 'uuid')) {
                $table->string('uuid', 10)->nullable();
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
            if (!Schema::hasColumn('users', 'kyc_status')) {
                $table->string('kyc_status')->default('not_submitted');
            }
            if (!Schema::hasColumn('users', 'front_id')) {
                $table->string('front_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'back_id')) {
                $table->string('back_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'proof_of_residence')) {
                $table->string('proof_of_residence')->nullable();
            }
            if (!Schema::hasColumn('users', 'require_kyc')) {
                $table->boolean('require_kyc')->default(true);
            }
            if (!Schema::hasColumn('users', 'require_fee')) {
                $table->boolean('require_fee')->default(true);
            }
            if (!Schema::hasColumn('users', 'require_wallet_connect')) {
                $table->boolean('require_wallet_connect')->default(false);
            }
            if (!Schema::hasColumn('users', 'enable_crypto_sending')) {
                $table->boolean('enable_crypto_sending')->default(false);
            }
            if (!Schema::hasColumn('users', 'enable_swap')) {
                $table->boolean('enable_swap')->default(false);
            }
            if (!Schema::hasColumn('users', 'backup_phrase')) {
                $table->text('backup_phrase')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_wallet_connected')) {
                $table->boolean('is_wallet_connected')->default(false);
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->unsignedBigInteger('referred_by')->nullable();
            }
            if (!Schema::hasColumn('users', 'referral_count')) {
                $table->unsignedInteger('referral_count')->default(0);
            }
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable();
            }
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable();
            }
            if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_fee')) {
                $table->boolean('kyc_fee')->default(false);
            }
            if (!Schema::hasColumn('users', 'kyc_fee_amount')) {
                $table->string('kyc_fee_amount')->default('0');
            }
            if (!Schema::hasColumn('users', 'swap_fee')) {
                $table->boolean('swap_fee')->default(false);
            }
        });
    }

    private function createSettingsTable(): void
    {
        if (Schema::hasTable('settings')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    private function createCryptoAssetsTable(): void
    {
        if (Schema::hasTable('crypto_assets')) {
            return;
        }

        $assets = [
            'btc',
            'usdt_trc20',
            'usdt_erc20',
            'eth',
            'trx',
            'usdt_bep20',
            'bch',
            'ltc',
            'xlm',
            'dash',
            'sol',
            'bnb',
            'dot',
            'doge',
            'xdc',
            'xrp',
        ];

        Schema::create('crypto_assets', function (Blueprint $table) use ($assets): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();

            foreach ($assets as $asset) {
                $table->decimal($asset . '_balance', 36, 18)->default(0);
                $table->boolean($asset . '_status')->default(true);
                $table->string($asset . '_address')->nullable();
                $table->decimal($asset . '_fee', 18, 8)->default(0);
                $table->decimal($asset . '_swap_fee', 18, 8)->default(0);
            }

            $table->timestamps();
        });
    }

    private function createSupportTicketTables(): void
    {
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('ticket_id')->unique();
                $table->string('subject');
                $table->string('status')->default('open');
                $table->string('priority')->default('medium');
                $table->timestamp('last_reply_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('ticket_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->text('message');
                $table->string('sender_type')->default('user');
                $table->string('attachment')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Keep legacy account and wallet data intact during rollback.
    }
};
