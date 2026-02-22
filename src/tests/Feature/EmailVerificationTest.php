<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verification_email_is_sent_on_register_event()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        event(new Registered($user));

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function unverified_user_cannot_access_items_create_and_is_redirected_to_verify_notice()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/items/create');

        // verified middleware の標準挙動：/email/verify へ
        $response->assertRedirect('/email/verify');
    }

    /** @test */
    public function verified_user_can_access_items_create()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/items/create');

        $response->assertStatus(200);
    }

    /** @test */
    public function unverified_user_can_resend_verification_email()
    {
        Notification::fake();

        $user = User::factory()->create([
        'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('verification.send'));

        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\VerifyEmail::class);

    }

    
}