<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function create(CreateProductRequest $request): JsonResponse
    {

        $data = $request->validated();
        Log::info("Creating product " . json_encode($data, JSON_PRETTY_PRINT));

        $category = $this->getCategory($data["category_id"]);
        Log::info("Category found " . json_encode($category, JSON_PRETTY_PRINT));

        $data["category_id"] = $category->id;

        $product = Product::create($data);
        $product->category = $category->name;

        Log::info("Product created successfully " . json_encode($product, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 201,
            "message" => "Product created successfully",
            "data" => new ProductResource($product)
        ], 201);
    }

    private function getCategory(int $categoryId): Category
    {
        $category = Category::find($categoryId);

        if (!$category) {
            Log::info("Category not found");
            throw new HttpResponseException(response()->json([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Category not found"
                ],
            ])->setStatusCode(404));
        }

        return $category;
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->getProduct($id);
        Log::info("Product found " . json_encode($product, JSON_PRETTY_PRINT));

        $data = $request->validated();
        Log::info("Updating product " . json_encode($data, JSON_PRETTY_PRINT));

        $category = $this->getCategory($data["category_id"]);
        Log::info("Category found " . json_encode($category, JSON_PRETTY_PRINT));

        $data["category_id"] = $category->id;

        $product->update($data);
        $product->category = $category->name;


        Log::info("Product updated successfully " . json_encode($product, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 200,
            "message" => "Product updated successfully",
            "data" => new ProductResource($product)
        ]);
    }

    private function getProduct(int $id): Product
    {
        $product = Product::find($id);

        if (!$product) {
            Log::info("Product not found");
            throw new HttpResponseException(response()->json([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Product not found"
                ],
            ])->setStatusCode(404));
        }

        return $product;
    }

    public function delete(int $id): JsonResponse
    {
        $product = $this->getProduct($id);

        $product->delete();

        Log::info("Product deleted successfully " . json_encode($product, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 200,
            "message" => "Product deleted successfully"
        ]);
    }

    public function list(Request $request): ProductCollection
    {
        $products = Product::with("category");
        Log::info("Searching products " . json_encode($request->all(), JSON_PRETTY_PRINT));

        $products->when($request->filled("category"), function (Builder $builder) use ($request) {
            $categoryName = $request->input("category");
            $builder->whereHas("category", function (Builder $query) use ($categoryName) {
                $query->where("name", $categoryName);
            });
        });

        $products->when($request->filled("min_price"), function (Builder $builder) use ($request) {
            $minPrice = $request->input("min_price");
            $builder->where("price", ">=", $minPrice);
        });

        $products->when($request->filled("max_price"), function (Builder $builder) use ($request) {
            $maxPrice = $request->input("max_price");
            $builder->where("price", "<=", $maxPrice);
        });


        $products = $products->get();

        return new ProductCollection($products);
    }


    public function get(int $id): JsonResponse
    {
        $product = Product::find($id);
        Log::info("Product found " . json_encode($product, JSON_PRETTY_PRINT));

        if (!$product) {
            Log::info("Product not found");
            throw new HttpResponseException(response()->json([
                "ok" => false,
                "status" => 404,
                "message" => "Not found",
                "errors" => [
                    "Product not found"
                ],
            ])->setStatusCode(404));
        }

        $product->category = $product->category->name;

        Log::info("Product retrieved successfully " . json_encode($product, JSON_PRETTY_PRINT));
        return response()->json([
            "ok" => true,
            "status" => 200,
            "message" => "Product retrieved successfully",
            "data" => new ProductResource($product)
        ]);
    }
}
