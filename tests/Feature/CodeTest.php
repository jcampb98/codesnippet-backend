<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Code;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function testCreateCodeSnippet() {
        // Arrange
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $codeData = [
            "title" => "Sample Code",
            "code_snippet" => "echo 'hello world'; ",
        ];

        // Act
        $response = $this->post("api/code/create", $codeData, [
            "Authorization" => "bearer {$token}"
        ]);

        // Assert
        $response->assertStatus(200)->assertJsonFragment(["title" => $codeData["title"]]);

        $this->assertDatabaseHas('codes', [
            "title" => $codeData["title"],
            "code_snippet" => $codeData["code_snippet"],
            "id" => $user->id,
        ]);
    }

    public function testShowCodeSnippetByGuid() {
        // Arrange
        $user = User::factory()->create();
        $code = Code::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->get("api/code/guid/{$code->guid}");

        // Assert
        $response->assertStatus(200)->assertJsonFragment([
            "title" => $code->title,
            "code_snippet" => $code->code_snippet,
        ]);
    }

    public function testUpdateCodeSnippet() {
        // Arrange
        $user = User::factory()->create();
        $code = Code::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $updatedData = [
            "title" => "Sample Code",
            "code_snippet" => "echo 'hello world'; ",
        ];

        // Act
        $response = $this->put("api/code/{$code->id}", $updatedData, [
            "Authorization" => "bearer {$token}"
        ]);

        // Assert
        $response->assertStatus(200)->assertJsonFragment(["title" => $updatedData["title"]]);

        $this->assertDatabaseHas('codes', [
            "id" => $code->id,
            "title" => $updatedData["title"],
            "code_snippet" => $updatedData["code_snippet"],
        ]);
    }

    public function testShowAllCodeSnippetsForUser()
    {
        // Arrange
        $user = User::factory()->create();
        Code::factory(3)->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        // Act
        $response = $this->get("api/code/{$user->id}", [
            'Authorization' => "Bearer {$token}",
        ]);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'code' => [
                         '*' => ['id', 'title', 'code_snippet', 'user_id', 'created_at', 'updated_at'],
                     ],
                 ]);
    }

    public function testDeleteCodeSnippet() {
        // Arrange
        $user = User::factory()->create();
        $code = Code::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $codeData = [
            "title" => "Sample Code",
            "code_snippet" => "echo 'hello world'; ",
        ];

        // Act
        $response = $this->post("api/code/create", $codeData, [
            "Authorization" => "bearer {$token}"
        ]);

        // Assert
        $response->assertStatus(200)->assertJsonFragment(["title" => $codeData["title"]]);

        $this->assertDatabaseMissing('codes', ["id" => $user->id,]);
    }
}
