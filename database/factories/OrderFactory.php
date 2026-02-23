<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'status' => OrderStatus::PENDING,
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'shipping_address' => $this->faker->address(),
        ];
    }
}
