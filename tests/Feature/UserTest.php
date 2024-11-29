<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        $this->withoutMiddleware();

        $this->withoutExceptionHandling();
    }

    /**
     * Test getting User Details
     * 
     * @return void
     */
    public function testGetUserDetails() {
        // Arrange
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        // Act
        $response = $this->get("api/auth/user", [
            "Authorization" => "bearer {$token}",
            "CONTENT_TYPE" => "application/json",
        ]);

        // Assert
        $response->assertStatus(200)->assertJsonStructure([
                'id', 
                'name', 
                'email',
            ]
        );
    }

    /**
     * Test creating a User
     * @return void
     */
    public function testRegisterUser(){
        // Generates new data for creating the user
        $userData = [
            'name' => 'Ron Swanson',
            'email' => 'Ron.Swanson@example.com',
            'password' => 'secret',
        ];

        // Sends a POST Request to create the user
        $response = $this->post('api/auth/register', $userData);

        // Assert that the request was successful (status code 200)
        $response -> assertStatus(200);

        // Assert that the user was stored in the database with the provided user data
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    /**
     * Test updating User Details
     * 
     * @return void
     */
    public function testUpdateUser() {
        // Creates User Details
        $user = User::factory()->create();

        // Generates new data for updating the user
        $userData = [
            'name' => 'Johnathan Doe',
            'email' => 'John.Doe@test.net',
            'password' => 'Password123'
        ];

        $token = JWTAuth::fromUser($user);

        // Sends a PATCH Request to update the user
        $response = $this->patch("api/auth/update/{$user->id}", $userData, [
            "Authorization" => "bearer {$token}",
        ]);

        // Assert that the request was successful (status code 200)
        $response -> assertStatus(200);

        // Assert that the user was stored in the database with the provided user data
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        // Assert: Password was updated and properly hashed
        $this->assertTrue(Hash::check('Password123', $user->fresh()->password));
    }

    /**
     * Test deleting User
     * 
     * @return void
     */
    public function testDeleteUser() {
        // Creates a User
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        // Sends a DELETE Request to delete the user
        $response = $this->delete("api/auth/delete/{$user->id}", [], [
            "Authorization" => "bearer {$token}",
            "CONTENT_TYPE" => "application/json",
        ]);

        // Assert that the request was successful (status code 200)
        $response->assertStatus(200);

        // Assert that the user was updated with the new data
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}