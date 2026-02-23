<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_user_can_access_home_page(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_admin_is_logged_out_on_home_page(): void
    {
        $admin = User::factory()->create([
            'user_type' => UserType::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/');
        
        $response->assertRedirect('/');
        $this->assertGuest();
        
        // Follow redirect to see guest access
        $this->get('/')->assertStatus(200);
    }

    public function test_super_admin_is_logged_out_on_home_page(): void
    {
        $superAdmin = User::factory()->create([
            'user_type' => UserType::SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->get('/');
        
        $response->assertRedirect('/');
        $this->assertGuest();
        
        // Follow redirect to see guest access
        $this->get('/')->assertStatus(200);
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_is_logged_out_from_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_user_is_redirected_to_dashboard_from_guest_routes(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $this->actingAs($user)->get('/login')->assertRedirect(route('dashboard'));
        $this->actingAs($user)->get('/register')->assertRedirect(route('dashboard'));
    }

    public function test_admin_is_logged_out_from_auth_routes(): void
    {
        $admin = User::factory()->create([
            'user_type' => UserType::ADMIN,
        ]);

        // Login
        $response = $this->actingAs($admin)->get('/login');
        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/login')->assertStatus(200);

        // Register
        $response = $this->actingAs($admin)->get('/register');
        $response->assertRedirect('/register');
        $this->assertGuest();
        $this->get('/register')->assertStatus(200);
    }
}
