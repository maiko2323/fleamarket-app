<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレスが空の場合、ログインできない
    public function test_login_fails_when_email_is_empty()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password111'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // パスワードが空の場合、ログインできない
    public function test_login_fails_when_password_is_empty()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    // メールアドレスまたはパスワードが間違っている場合、ログインできない
    public function test_login_fails_when_credentials_are_wrong()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password111'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

    }

    // 正しい情報でログインできる
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password111'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password111',
        ]);

        $response->assertRedirect('/mypage');
        $this->assertAuthenticatedAs($user);
    }

    // ログアウトできる
    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password111'),
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
    }
}
