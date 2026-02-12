<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_with_products()
    {
        $category = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);
        Product::factory()->count(5)->create(['category_id' => $category->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Our Products');
        $response->assertSee('Electronics');
    }

    public function test_can_add_product_to_cart()
    {
        $product = Product::factory()->create(['price' => 100]);

        $response = $this->get('/add-to-cart/' . $product->id);

        $response->assertRedirect();
        $this->assertEquals(1, count(session('cart')));
        $this->assertEquals($product->name, session('cart')[$product->id]['name']);
    }

    public function test_guest_can_place_order()
    {
        $product = Product::factory()->create(['price' => 100]);

        // Add to cart first
        $this->get('/add-to-cart/' . $product->id);

        $response = $this->post('/place-order', [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'address' => '123 Test Street',
            'payment_method' => 'cod'
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('orders', [
            'total_price' => 100,
            'payment_method' => 'cod'
        ]);
        $this->assertEmpty(session('cart'));
    }
}
