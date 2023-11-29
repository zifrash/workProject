<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = ProductSeeder::class;

    public function testGetProducts(): void
    {
        $productsApi10 = $this->getJson('/api/products/?per_page=10&sort=name-asc')
            ->assertJsonCount(10, 'products')
            ->assertStatus(200)
            ->json('products');

        $productsApi20 = $this->getJson('/api/products/?per_page=20&sort=name-dsc')
            ->assertJsonCount(20, 'products')
            ->assertStatus(200)
            ->json('products');

        $this->getJson('/api/products/?per_page=30')
            ->assertJsonCount(30, 'products')
            ->assertStatus(200);

        Product::query()->limit(10)->orderBy('name')->get()->each(function($productBase) use (&$productsApi10) {
            $this->assertEquals($productBase->id, array_shift($productsApi10)['id']);
        });

        Product::query()->limit(20)->orderByDesc('name')->get()->each(function($productBase) use (&$productsApi20) {
            $this->assertEquals($productBase->id, array_shift($productsApi20)['id']);
        });
    }

    public function testGetProduct(): void
    {
        $product = Product::query()->first();
        $getProduct = $this->getJson("/api/products/{$product->slug}/")->assertStatus(200);

        $this->assertEquals($getProduct['name'], $product->name);
    }

    public function testUpdateProduct(): void
    {
        $product = Product::first();

        $newProductName = "Updated {$product->name}";
        $newProductSlug = str_slug($newProductName);

        $this->putJsonAuth("/api/products/{$product->slug}/", [
            'name' => $newProductName,
            'slug' => $newProductSlug,
        ])->assertStatus(200);

        $updateProduct = Product::query()->where('slug', $newProductSlug)->first();
        $this->assertEquals($newProductName, $updateProduct->name);
    }

    public function testCreateProduct(): void
    {
        $productName = 'Test product';
        $productSlug = str_slug($productName);

        $this->postJsonAuth('/api/products/', ['name' => $productName])->assertStatus(201);

        $newProduct = Product::whereSlug($productSlug)->first();
        $this->assertEquals($productName, $newProduct->name);
    }

    public function testDeleteProduct(): void
    {
        $product = Product::query()->first();

        $this->deleteJsonAuth("/api/products/{$product->slug}/")->assertStatus(200);

        $this->assertFalse(Product::query()->where('slug', $product->slug)->exists());
    }
}
