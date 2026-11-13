<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\PasscodePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PasscodePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_passcode_success_navigates_to_a_safe_fallback_destination(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(PasscodePage::class)
            ->set('redirectTo', 'https://example.com')
            ->call('verify', '123456')
            ->assertRedirect(route('dashboard'));
    }

    public function test_passcode_success_navigates_to_the_original_internal_destination(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(PasscodePage::class, [
            'redirectTo' => '/markets?section=perps',
        ])
            ->call('verify', '123456')
            ->assertRedirect('/markets?section=perps');
    }
}
