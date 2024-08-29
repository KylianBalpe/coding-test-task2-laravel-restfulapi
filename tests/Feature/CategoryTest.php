<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testCreateCategorySuccess()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/category", [
            "name" => "Category 1",
        ])->assertStatus(201)
            ->assertJson([
                "ok" => true,
                "status" => 201,
                "message" => "Category created successfully",
                "data" => [
                    "name" => "Category 1",
                ]
            ]);
    }

    public function testCreateCategoryValidationError()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/category", [
            "name" => "",
        ])->assertStatus(422)
            ->assertJson([
                "ok" => false,
                "status" => 422,
                "message" => "Validation error",
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testCreateCategoryUnauthenticated()
    {
        $this->post("/api/category", [
            "name" => "Category 1",
        ])->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testCreateCategoryUnauthorized()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->post("/api/category", [
            "name" => "Category 1",
        ])->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }

    public function testGetAllCategoriesSuccess()
    {
        $this->seed(CategorySeeder::class);
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/categories")
            ->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertCount(2, $response["data"]);
    }

    public function testGetAllCategoriesUnauthenticated()
    {
        $this->get("/api/categories")
            ->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testGetAllCategoriesEmpty()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->get("/api/categories")
            ->assertStatus(200)
            ->json();

        self::assertCount(0, $response["data"]);
    }

    public function testUpdateCategorySuccess()
    {
        $category = Category::factory()->create();
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/category/" . $category->id, [
            "name" => "Category Update",
        ])->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "Category updated successfully",
                "data" => [
                    "name" => "Category Update",
                ]
            ]);
    }

    public function testUpdateCategoryNotFound()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/category/1", [
            "name" => "Category Update",
        ])->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Category not found"
            ]);
    }

    public function testUpdateCategoryUnauthenticated()
    {
        $category = Category::factory()->create();

        $this->put("/api/category/" . $category->id, [
            "name" => "Category Update",
        ])->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testUpdateCategoryUnauthorized()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->put("/api/category/" . $category->id, [
            "name" => "Category Update",
        ])->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }

    public function testDeleteCategorySuccess()
    {

        $category = Category::factory()->create();
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/category/" . $category->id)
            ->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "Category deleted successfully"
            ]);
    }

    public function testDeleteCategoryNotFound()
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/category/1")
            ->assertStatus(404)
            ->assertJson([
                "ok" => false,
                "status" => 404,
                "message" => "Category not found"
            ]);
    }

    public function testDeleteCategoryUnauthenticated()
    {
        $category = Category::factory()->create();

        $this->delete("/api/category/" . $category->id)
            ->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Unauthenticated"
            ]);
    }

    public function testDeleteCategoryUnauthorized()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $this->delete("/api/category/" . $category->id)
            ->assertStatus(403)
            ->assertJson([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized"
            ]);
    }
}
