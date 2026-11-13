<?php

namespace Tests\Feature;

use App\Models\CryptoAsset;
use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsPasscodeVerifiedUser($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsPasscodeVerifiedUser($user)
            ->from('/profile')
            ->patch('/profile', $this->validSecuritySettings());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertTrue(Hash::check('654321', $user->passcode));
        $this->assertTrue(Hash::check('NewPassword123!', $user->password));
    }

    public function test_security_settings_update_does_not_change_email_verification(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsPasscodeVerifiedUser($user)
            ->from('/profile')
            ->patch('/profile', $this->validSecuritySettings());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();
        $cryptoAsset = $user->createCryptoAssets();
        $card = Card::create([
            'user_id' => $user->id,
            'crypto_asset_id' => $cryptoAsset->id,
            'card_number' => '4111111111111111',
            'card_holder' => $user->name,
            'expiry_month' => '12',
            'expiry_year' => '30',
            'cvv' => '123',
            'billing_address' => 'Private billing address',
            'zip_code' => '12345',
            'status' => 'active',
            'balance' => 20,
            'card_type' => 'virtual',
            'last_four' => '1111',
            'is_default' => true,
        ]);
        $cardTransaction = CardTransaction::create([
            'card_id' => $card->id,
            'amount' => 5,
            'type' => 'debit',
            'description' => 'Private card activity',
        ]);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'cryptoasset_id' => $cryptoAsset->id,
            'type' => Transaction::TYPE_DEPOSIT,
            'which_crypto' => 'btc',
            'transaction_hash' => 'sensitive-hash',
            'from_address' => 'sensitive-from',
            'to_address' => 'sensitive-to',
            'amount_in' => 1.25,
            'status' => Transaction::STATUS_COMPLETED,
            'metadata' => ['email' => $user->email],
        ]);

        $response = $this
            ->actingAsPasscodeVerifiedUser($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
        $this->assertDatabaseMissing('crypto_assets', [
            'id' => $cryptoAsset->id,
        ]);
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);

        $cardTransaction->refresh();
        $this->assertNull($cardTransaction->card_id);
        $this->assertNull($cardTransaction->description);
        $this->assertSame(5.0, (float) $cardTransaction->amount);

        $transaction->refresh();
        $this->assertNull($transaction->user_id);
        $this->assertNull($transaction->cryptoasset_id);
        $this->assertNull($transaction->transaction_hash);
        $this->assertNull($transaction->from_address);
        $this->assertNull($transaction->to_address);
        $this->assertNull($transaction->metadata);
        $this->assertSame('1.25000000', $transaction->amount_in);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAsPasscodeVerifiedUser($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    private function actingAsPasscodeVerifiedUser(User $user): static
    {
        return $this->actingAs($user)->withSession([
            'passcode_verified' => true,
            'passcode_verified_at' => now()->toDateTimeString(),
        ]);
    }

    private function validSecuritySettings(): array
    {
        return [
            'passcode' => '654321',
            'current_password' => 'password',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ];
    }
}
