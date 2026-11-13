<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CoreApplicationSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_user_wallet_and_settings_schema_is_available(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'is_admin',
            'preferred_currency',
            'locale',
            'passcode',
        ]));
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasColumns('tickets', [
            'user_id',
            'ticket_id',
            'status',
        ]));
        $this->assertTrue(Schema::hasColumns('ticket_replies', [
            'ticket_id',
            'user_id',
            'sender_type',
        ]));
        $this->assertTrue(Schema::hasColumns('crypto_assets', [
            'user_id',
            'btc_balance',
            'btc_address',
            'xrp_balance',
        ]));
    }
}
