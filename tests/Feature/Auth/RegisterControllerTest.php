<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $response->assertStatus(201);

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

        $response1 = $this->postJson('/api/register', $data);
        $response1->assertStatus(201);

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422);
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
            'password' => 'pass1234'
        ];

        $data = array_merge($validData, $invalidData);

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422);
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
            [['password' => 'asdfghh1'], 'password'],
        ];
    }


}
