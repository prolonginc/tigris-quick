<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private $adminEmails = [
        'udsn27@gmail.com',
        'sari.yono@lnxinc.com',
        'sabrisaadoon@gmail.com'
    ];

    /** @test */
    public function non_admin_users_cannot_access_admin_index()
    {
        $user = User::factory()->create(['email' => 'normal@example.com']);
        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_users_lists()
    {
        $admin = User::factory()->create(['email' => $this->adminEmails[0]]);
        $approved = User::factory()->create(['is_approved' => 1]);
        $pending = User::factory()->create(['is_approved' => 0]);

        $this->actingAs($admin);
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertViewHasAll(['approvedUsers', 'pendingUsers']);
    }

    /** @test */
    public function admin_can_approve_users()
    {
        $admin = User::factory()->create(['email' => $this->adminEmails[0]]);
        $user = User::factory()->create(['is_approved' => 0]);

        $this->actingAs($admin);
        $response = $this->post(route('admin.approve', $user));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_approved' => 1]);
        $response->assertRedirect(route('admin.index'));
    }

    /** @test */
    public function admin_can_delete_users()
    {
        $admin = User::factory()->create(['email' => $this->adminEmails[0]]);
        $user = User::factory()->create();

        $this->actingAs($admin);
        $response = $this->delete(route('admin.destroy', $user));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $response->assertRedirect(route('admin.index'));
    }
}
