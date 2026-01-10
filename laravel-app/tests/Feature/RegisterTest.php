<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 会員登録が正常に行えること
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'password111',
            'password_confirmation' => 'password111'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    // 名前が空の場合、会員登録に失敗すること
    public function test_register_fails_when_name_is_empty()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password111',
            'password_confirmation' => 'password111'
        ]);

        $response->assertSessionHasErrors('name');
    }

    // メールアドレスが空の場合、会員登録に失敗すること
    public function test_register_fails_when_email_is_empty()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => '',
            'password' => 'password111',
            'password_confirmation' => 'password111'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // メールアドレスの形式が不正な場合、会員登録に失敗すること
    public function test_register_fails_when_email_format_invalid()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'invalid-email',
            'password' => 'password111',
            'password_confirmation' => 'password111'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // パスワードが空の場合、会員登録に失敗すること
    public function test_register_fails_when_password_empty()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    // パスワードが8文字未満の場合、会員登録に失敗すること
    public function test_register_fails_when_password_too_short()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short'
        ]);

        $response->assertSessionHasErrors('password');
    }

    // パスワードとパスワード確認が一致しない場合、会員登録に失敗すること
    public function test_register_fails_when_password_confirmation_mismatch()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'password111',
            'password_confirmation' => 'differentpassword'
        ]);

        $response->assertSessionHasErrors('password');
    }
}