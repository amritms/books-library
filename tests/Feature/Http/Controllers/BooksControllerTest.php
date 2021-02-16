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

    /** @test */
    public function unauthorized_user_cannot_create_book()
    {
        $data = [
            'title' => 'To Kill a Mockingbird',
            'isbn' => '0978039912',
            'published_at' => $this->faker->date('Y-m-d', date('Y-m-d', strtotime("-1 year"))),
            'status' => 'AVAILABLE'
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function user_cannot_create_book_with_invalid_data(array $invalidData, string $invalidParameter)
    {
        $user = User::factory()->create();

        $validData = [
            'title' => 'To Kill a Mockingbird',
            'isbn' => '0978039912',
            'published_at' => $this->faker->date('Y-m-d', date('Y-m-d', strtotime("-1 year"))),
            'status' => 'AVAILABLE'
        ];

        $data = array_merge($validData, $invalidData);

        $response = $this->actingAs($user)->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['title' => null], 'title'],
            [['title' => ''], 'title'],
            [['title' => []], 'title'],
            [['isbn' => 'null'], 'isbn'],
            [['isbn' => ''], 'isbn'],
            [['isbn' => []], 'isbn'],
            [['isbn' => '0987654321'], 'isbn'],
            [['published_at' => null], 'published_at'],
            [['published_at' => ''], 'published_at'],
            [['published_at' => []], 'published_at'],
            [['published_at' => '2020-13-13'], 'published_at'],
            [['published_at' => '11-25-2020'], 'published_at'],
            [['published_at' => '12-12-2020'], 'published_at'],
        ];
    }
}
