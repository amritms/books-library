<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\UserActionLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_checkout_book()
    {
        $user = User::factory()->create();
        $book = $this->checkoutBook($user, 'CHECKED_OUT', 'CHECKOUT');

        $response = $this->actingAs($user)->postJson('/api/checkin', ['book_id' => $book->id]);

        $response->assertStatus(200);

        $this->assertSame('AVAILABLE', $book->fresh()->status);

        $this->assertDatabaseHas('user_action_logs', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'action' => 'CHECKIN'
        ]);
    }

    /** @test */
    public function guest_cannot_checkin_book()
    {
        $response = $this->postJson('/api/checkin', ['book_id' => 999]);

        $response->assertStatus(401);
    }

    /** @test */
    public function cannot_checkin_available_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['status' => 'AVAILABLE']);

        $response = $this->actingAs($user)->postJson('/api/checkin', ['book_id' => $book->id]);

        $response->assertStatus(422);
    }

    /** @test */
    public function cannot_checkin_non_existing_book()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/checkin', ['book_id' => 999]);

        $response->assertStatus(422);
    }

    /**
     * create checkout book
     * @param $user
     * @param string $status
     * @param string $action
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function checkoutBook($user, $status = 'CHECKED_OUT', $action='CHECKOUT'){
        $book = Book::factory()->create(['status' => $status]);
        UserActionLog::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'action' => $action
        ]);

        return $book;
    }
}
