<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_checkout_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['status' => 'AVAILABLE']);

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_id' => $book->id]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_action_logs', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'action' => 'CHECKOUT'
        ]);
    }

    /** @test */
    public function guest_cannot_checkout_book()
    {
        $book = Book::factory()->create(['status' => 'AVAILABLE']);

        $response = $this->postJson('/api/checkout', ['book_id' => $book->id]);

        $response->assertStatus(401);
    }

    /** @test */
    public function cannot_checkout_unavailable_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['status' => 'CHECKED_OUT']);

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_id' => $book->id]);

        $response->assertStatus(422);
    }

    /** @test */
    public function cannot_checkout_non_existing_book()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_id' => 999]);

        $response->assertStatus(422);
    }
}
