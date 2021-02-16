<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_checkout_book()
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->create(['status' => 'AVAILABLE']);
        $book2 = Book::factory()->create(['status' => 'AVAILABLE']);

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_ids' => [$book1->id, $book2->id]]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('user_action_logs', [
            'book_id' => $book1->id,
            'user_id' => $user->id,
            'action' => 'CHECKOUT'
        ]);

        $this->assertDatabaseHas('user_action_logs', [
            'book_id' => $book2->id,
            'user_id' => $user->id,
            'action' => 'CHECKOUT'
        ]);
    }

    /** @test */
    public function guest_cannot_checkout_book()
    {
        $book = Book::factory()->create(['status' => 'AVAILABLE']);

        $response = $this->postJson('/api/checkout', ['book_id' => $book->id]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function cannot_checkout_unavailable_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['status' => 'CHECKED_OUT']);

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_id' => $book->id]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function cannot_checkout_non_existing_book()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/checkout', ['book_id' => 999]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
