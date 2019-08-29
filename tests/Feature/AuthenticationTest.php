<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AuthenticationTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $user = new User([
            'name' => 'Name',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $user->save();
    }

    /** @test */
    public function it_will_log_a_user_in(): void
    {
        $response = $this->post('api/register', [
            'name' => 'name',
            'email' => 'test2@gmail.com',
            'password' => '123456'
        ]);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    /** @test */
    public function it_will_not_log_an_invalid_user_in(): void
    {
        $response = $this->post('api/login', [
            'email' => 'test@gmail.com',
            'password' => 'notlegitpassword'
        ]);

        $response->assertJsonStructure(['error',]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
