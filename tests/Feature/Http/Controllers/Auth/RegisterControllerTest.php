<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $data = [
            'name' => 'Amrit Man Shrestha',
            'email' => 'amritms@gmail.com',
            'password' => 'Pass1234',
            'date_of_birth' => $this->faker->date('Y-m-d')
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'name',
                'email',
                'id',
                'date_of_birth'
            ]
        ]);

        $user = User::where([
            'email' => $data['email'],
            'name' => $data['name']
        ])->first();

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    /** @test */
    public function email_should_be_unique()
    {
        $data = [
            'name' => 'Amrit Man Shrestha',
            'email' => 'amritms@gmail.com',
            'password' => 'Pass1234',
            'date_of_birth' => $this->faker->date('Y-m-d'),
        ];

        User::create($data);

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonMissingValidationErrors('name');
        $response->assertJsonMissingValidationErrors('password');
        $response->assertJsonValidationErrors('email');
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function user_cannot_register_with_invalid_data(array $invalidData, string $invalidParameter)
    {
        $validData = [
            'name' => 'Amrit Man Shrestha',
            'email' => 'amritms@gmail.com',
            'password' => 'pass1234',
            'date_of_birth' => $this->faker->date('Y-m-d'),
        ];

        $data = array_merge($validData, $invalidData);

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['name' => null], 'name'],
            [['name' => ''], 'name'],
            [['name' => []], 'name'],
            [['name' => 'asd'], 'name'],
            [['name' => ['asd']], 'name'],
            [['email' => 'null'], 'email'],
            [['email' => ''], 'email'],
            [['email' => []], 'email'],
            [['email' => 'aa'], 'email'],
            [['password' => ''], 'password'],
            [['password' => []], 'password'],
            [['password' => 'asd'], 'password'],
            [['password' => 'asdfghhj'], 'password'],
            [['date_of_birth' => null], 'date_of_birth'],
            [['date_of_birth' => ''], 'date_of_birth'],
            [['date_of_birth' => '12-12-2001'], 'date_of_birth'],
        ];
    }
}
