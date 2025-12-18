<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_if_user_is_not_approved()
    {
        $user = User::factory()->create(['is_approved' => 0]);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('pending'));
    }

    /** @test */
    public function it_displays_products_when_user_is_approved()
    {
        $user = User::factory()->create(['is_approved' => 1]);
        Product::factory()->count(3)->create();

        $this->actingAs($user);
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }
}
