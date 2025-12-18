<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_attributes()
    {
        $user = new User();
        $this->assertEquals(
            ['name', 'email', 'password', 'phone_number', 'comment', 'business_name'],
            $user->getFillable()
        );
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
