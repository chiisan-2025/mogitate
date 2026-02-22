<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Taro',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(); // Fortifyは登録後リダイレクト（先は環境で差が出るのでassertRedirectでOK）
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(); // Fortifyのログアウト後の遷移
    }

    public function test_register_required_error_messages(): void
    {
         $response = $this->from(route('register'))->post(route('register'), [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('register'));

        $response->assertSessionHasErrors([
            'name' => 'お名前 を入力してください。',
            'email' => 'メールアドレス を入力してください。',
            'password' => 'パスワード を入力してください。',
        ]);
    }

    public function test_register_email_format_message(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Taro',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレス はメール形式で入力してください。',
        ]);
    }

    public function test_register_password_min_message(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Taro',
            'email' => 'taro@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワード は 8 文字以上で入力してください。',
        ]);
    }

    public function test_register_password_confirmation_message(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Taro',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワード と一致しません。',
        ]);
    }

    public function test_login_required_error_messages(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors([
            'email' => 'メールアドレス を入力してください。',
            'password' => 'パスワード を入力してください。',
        ]);
    }

    public function test_login_failed_message(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));

    // Fortifyは「email」にエラーとして載せることが多い
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}