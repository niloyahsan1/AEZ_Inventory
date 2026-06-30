<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get all products
        $products = DB::table('products')->get();

        foreach ($products as $product) {
            if (empty($product->name)) {
                continue;
            }

            // Check if a category with this name already exists
            $category = DB::table('categories')->where('name', $product->name)->first();

            if (!$category) {
                // Create a new category
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $product->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $category->id;
            }

            // Assign the product to this category
            DB::table('products')->where('id', $product->id)->update([
                'category_id' => $categoryId
            ]);
        }

        // 2. Drop the name column from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $category = DB::table('categories')->where('id', $product->category_id)->first();
            if ($category) {
                DB::table('products')->where('id', $product->id)->update([
                    'name' => $category->name
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
    }
};
