<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Enums\UserType;
use App\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Account Overview');
        $response->assertSee('Recent Orders');
    }

    public function test_dashboard_displays_user_orders(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total_price' => 100.00,
            'status' => OrderStatus::PENDING,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT));
        $response->assertSee('$100.00');
    }

    public function test_dashboard_displays_no_orders_message(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::USER,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee("haven't placed any orders yet", false);
    }
}
