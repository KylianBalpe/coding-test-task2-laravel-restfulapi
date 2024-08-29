<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = Category::where("name", "Electronics")->first();
        Log::info("Category 1 found " . json_encode($category1, JSON_PRETTY_PRINT));
        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                "name" => "Product $i",
                "description" => "Description $i",
                "price" => 1000 * $i,
                "category_id" => $category1->id,
            ]);
        }

        $category2 = Category::where("name", "Clothing and Accessories")->first();
        Log::info("Category 2 found " . json_encode($category2, JSON_PRETTY_PRINT));
        for ($i = 1; $i <= 2; $i++) {
            Product::create([
                "name" => "Product $i Category $category2->name",
                "description" => "Product $i Category $category2->name description",
                "price" => 1000 * $i,
                "category_id" => $category2->id,
            ]);
        }
    }
}
