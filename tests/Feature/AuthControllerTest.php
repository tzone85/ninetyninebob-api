<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint(): void
    {
        $this->getJson('/api/health')->assertOk()->assertJson(['status' => 'ok']);
    }

    public function test_root_endpoint(): void
    {
        $this->getJson('/api/')
            ->assertOk()
            ->assertJsonStructure(['service', 'version']);
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'correct-horse-battery',
        ];
        $response = $this->postJson('/api/register', $payload);
        $response->assertCreated();
        $response->assertJsonStructure(['user' => ['id', 'name', 'email'], 'access_token', 'token_type']);
        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
        $stored = User::where('email', 'alice@example.com')->first();
        $this->assertTrue(Hash::check('correct-horse-battery', $stored->password));
    }

    public function test_register_rejects_short_password(): void
    {
        $this->postJson('/api/register', [
            'name' => 'A',
            'email' => 'a@example.com',
            'password' => 'short',
        ])->assertStatus(422);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dup@example.com']);
        $this->postJson('/api/register', [
            'name' => 'X',
            'email' => 'dup@example.com',
            'password' => 'correct-horse-battery',
        ])->assertStatus(422);
    }

    public function test_login_returns_token_for_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'bob@example.com',
            'password' => Hash::make('correct-horse'),
        ]);
        $r = $this->postJson('/api/login', [
            'email' => 'bob@example.com',
            'password' => 'correct-horse',
        ]);
        $r->assertOk();
        $r->assertJsonStructure(['user' => ['id', 'name', 'email'], 'access_token', 'token_type']);
    }

    public function test_login_rejects_wrong_password_with_generic_message(): void
    {
        User::factory()->create([
            'email' => 'bob@example.com',
            'password' => Hash::make('correct-horse'),
        ]);
        // REGRESSION: original `login()` did \Log::info(json_encode($credentials)) —
        // raw passwords in app log. Original also returned the literal 'Unauthorized'
        // string. Now: generic 'Invalid credentials.' wrapped in a validation
        // error structure; no username enumeration, no password logging.
        $r = $this->postJson('/api/login', [
            'email' => 'bob@example.com',
            'password' => 'wrong',
        ]);
        $r->assertStatus(422);
        $r->assertJsonFragment(['email' => ['Invalid credentials.']]);
    }

    public function test_login_rejects_unknown_user_with_same_generic_message(): void
    {
        $this->postJson('/api/login', [
            'email' => 'nobody@example.com',
            'password' => 'anything',
        ])->assertStatus(422)->assertJsonFragment(['email' => ['Invalid credentials.']]);
    }

    public function test_me_requires_token(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_me_returns_authenticated_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;
        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_user_alias_route_works(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;
        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id);
    }

    public function test_logout_deletes_the_current_token_from_db(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->assertSame(1, PersonalAccessToken::count(), 'token should exist before logout');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Successfully logged out']);

        // The token row is gone — any future request with this bearer would
        // fail authentication. (Asserting DB state directly is more robust
        // than chaining a second test request — the test client persists the
        // resolved user across calls within one test.)
        $this->assertSame(0, PersonalAccessToken::count(), 'token should be deleted after logout');
        $this->assertNull(PersonalAccessToken::findToken($token));
    }

    public function test_logout_without_token_is_rejected_by_middleware(): void
    {
        $this->postJson('/api/logout')->assertUnauthorized();
    }
}
