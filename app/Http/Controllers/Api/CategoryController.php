<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function create(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        Log::info("Creating category " . json_encode($data, JSON_PRETTY_PRINT));

        $category = Category::create($data);

        Log::info("Category created successfully " . json_encode($category, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 201,
            "message" => "Category created successfully",
            "data" => new CategoryResource($category)
        ], 201);
    }

    public function getAll(): CategoryCollection
    {
        $categories = Category::all();

        Log::info("Retrieved all categories " . json_encode($categories, JSON_PRETTY_PRINT));
        return new CategoryCollection($categories);
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $data = $request->validated();
        Log::info("Updating category " . json_encode($data, JSON_PRETTY_PRINT));

        $category = Category::find($id);
        if (!$category) {
            Log::error("Category not found");
            throw new HttpResponseException(response()->json([
                "ok" => false,
                "status" => 404,
                "message" => "Category not found",
            ])->setStatusCode(404));
        }

        $category->update($data);

        Log::info("Category updated successfully " . json_encode($category, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 200,
            "message" => "Category updated successfully",
            "data" => new CategoryResource($category)
        ], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            Log::error("Category not found");
            throw new HttpResponseException(response()->json([
                "ok" => false,
                "status" => 404,
                "message" => "Category not found",
            ])->setStatusCode(404));
        }

        $category->delete();

        Log::info("Category deleted successfully " . json_encode($category, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 200,
            "message" => "Category deleted successfully"
        ], 200);
    }
}
