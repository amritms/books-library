<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Pass1234'
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $user = User::where([
            'email'=> $user->email,
            'name'=> $user->name
        ])->first();

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function users_cannot_authenticate_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $this->assertGuest();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
