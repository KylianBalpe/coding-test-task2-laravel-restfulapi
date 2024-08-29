<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProductSuccess()
    {
        $category = Category::factory()->create();
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/product", [
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id,
        ])->assertStatus(201)
            ->assertJson([
                "ok" => true,
                "status" => 201,
                "message" => "Product created successfully",
                "data" => [
                    "name" => "Product 1",
                    "description" => "Description 1",
                    "price" => 5000,
                    "category" => $category->name,
                ]
            ]);
    }

    public function testCreateProductValidationError()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/product", [
            "name" => "",
            "description" => "",
            "price" => "",
            "category_id" => "",
        ])->assertStatus(422)
            ->assertJson([
                "message" => "Validation error",
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "description" => [
                        "The description field is required."
                    ],
                    "price" => [
                        "The price field is required."
                    ],
                    "category_id" => [
                        "The category id field is required."
                    ],
                ]
            ]);
    }

    public function testCreateProductCategoryNotFound()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/product", [
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => 1,
        ])->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Category not found"
                ]
            ]);
    }

    public function testCreateProductUnauthenticated()
    {
        $this->post("/api/product", [
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => 1,
        ])->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testCreateProductUnauthorized()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/product", [
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => 1,
        ])->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }

    public function testGetProductSuccess()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create([
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id,
        ]);

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->get("/api/product/" . $product->id)
            ->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "Product retrieved successfully",
                "data" => [
                    "name" => "Product 1",
                    "description" => "Description 1",
                    "price" => 5000,
                    "category" => $category->name,
                ]
            ]);
    }

    public function testGetProductNotFound()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->get("/api/product/1")
            ->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Product not found"
                ]
            ]);
    }

    public function testGetProductUnauthenticated()
    {
        $this->get("/api/product/1")
            ->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testUpdateProductSuccess()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create([
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id,
        ]);

        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/product/" . $product->id, [
            "name" => "Product Update",
            "description" => "Description Update",
            "price" => 10000,
            "category_id" => $category->id,
        ])->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "Product updated successfully",
                "data" => [
                    "name" => "Product Update",
                    "description" => "Description Update",
                    "price" => 10000,
                    "category" => $category->name,
                ]
            ]);
    }

    public function testUpdateProductNotFound()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/product/1", [
            "name" => "Product Update",
            "description" => "Description Update",
            "price" => 10000,
            "category_id" => 1,
        ])->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Product not found"
                ]
            ]);
    }

    public function testUpdateProductCategoryNotFound()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create([
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id + 1,
        ]);

        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/product/" . $product->id, [
            "name" => "Product Update",
            "description" => "Description Update",
            "price" => 10000,
            "category_id" => 1,
        ])->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Category not found"
                ]
            ]);
    }

    public function testUpdateProductUnauthenticated()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create([
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id,
        ]);

        $this->put("/api/product/" . $product->id, [
            "name" => "Product Update",
            "description" => "Description Update",
            "price" => 10000,
            "category_id" => $category->id,
        ])->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testUpdateProductUnauthorized()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create([
            "name" => "Product 1",
            "description" => "Description 1",
            "price" => 5000,
            "category_id" => $category->id,
        ]);

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/product/" . $product->id, [
            "name" => "Product Update",
            "description" => "Description Update",
            "price" => 10000,
            "category_id" => $category->id,
        ])->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }

    public function testDeleteProductSuccess()
    {
        $product = Product::factory()->create();

        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/product/" . $product->id)
            ->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "Product deleted successfully",
            ]);
    }

    public function testDeleteProductNotFound()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/product/1")
            ->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Product not found"
                ]
            ]);
    }

    public function testDeleteProductUnauthenticated()
    {
        $this->delete("/api/product/1")
            ->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testDeleteProductUnauthorized()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/product/" . $product->id)
            ->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }

    public function testFilterProductByCategory()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/products?category=Electronics")
            ->assertStatus(200)
            ->json();
        Log::info("Response " . json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(10, $response["data"]);
    }

    public function testFilterProductByMinPrice()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/products?min_price=5000")
            ->assertStatus(200)
            ->json();
        Log::info("Response " . json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(6, $response["data"]);
    }

    public function testFilterProductByMaxPrice()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/products?max_price=5000")
            ->assertStatus(200)
            ->json();
        Log::info("Response " . json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(7, $response["data"]);
    }

    public function testFilterProductByMinMaxPrice()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/products?min_price=1000&max_price=5000")
            ->assertStatus(200)
            ->json();
        Log::info("Response " . json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(7, $response["data"]);
    }

    public function testFilterProductByCategoryAndMinMaxPrice()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/products?category=clothing and accessories&min_price=1000&max_price=5000")
            ->assertStatus(200)
            ->json();

        Log::info("Response " . json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(2, $response["data"]);
    }
}
