<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class BooksControllerTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /** @test */
    public function authorized_user_can_add_book()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'To Kill a Mockingbird',
            'isbn' => '0978039912',
            'published_at' => $this->faker->date('Y-m-d', date('Y-m-d', strtotime("-1 year"))),
            'status' => 'AVAILABLE'
        ];

        $response = $this->actingAs($user)->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'title',
                'isbn',
                'published_at',
                'status'
            ]
        ]);

        $response->assertJsonFragment($data);

        $book = Book::find($response->decodeResponseJson()['data']['id'])->first();

        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['isbn'], $book->isbn);
        $this->assertEquals($data['published_at'], $book->published_at);
        $this->assertEquals($data['status'], $book->status);
    }
}
