<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    //会員登録後、認証メールが送信されること
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    //認証はこちらからを押下すると、認証通知ページが表示されること
    public function test_verification_notice_page_is_displayed()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }


    //認証メールのリンクをクリックすると、メールアドレスが認証され、プロフィール編集画面に遷移すること
    public function test_user_can_verify_email_and_is_redirected_to_profile_edit()
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
            'id' => $user->id,
            'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($url);

        $response->assertRedirect(route('mypage.profile.edit'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
