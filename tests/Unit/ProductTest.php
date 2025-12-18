<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function it_has_expected_fillable_attributes()
    {
        $product = new Product();
        $this->assertEquals(
            ['id', 'name', 'description', 'price', 'quantity'],
            $product->getFillable()
        );
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Sample description',
            'price' => 19.99,
            'quantity' => 5,
        ];

        $product = Product::create($data);

        $this->assertDatabaseHas('products', $data);
    }
}
