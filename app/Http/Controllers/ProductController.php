<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentCategoryId = $request->query('category_id');

        if ($currentCategoryId) {
            $currentCategory = Category::with(['children', 'products', 'parent'])->findOrFail($currentCategoryId);
            $categories = $currentCategory->children;
            $products = $currentCategory->products;

            // Generate breadcrumbs by traversing parent categories upwards
            $breadcrumbs = [];
            $temp = $currentCategory;
            while ($temp) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }
        } else {
            $currentCategory = null;
            // Retrieve only the categories that have NO parent (root level)
            $categories = Category::whereNull('parent_id')->with('products')->get();
            $products = collect(); // no products directly at the root level
            $breadcrumbs = [];
        }

        // Calculate total quantities of all products for the header badge
        $allProductsSum = Product::sum('quantity');
        $allCategories = Category::all();

        return view('products.index', compact('categories', 'products', 'currentCategory', 'breadcrumbs', 'allProductsSum', 'allCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect('/?category_id=' . $request->category_id)->with('success', 'Product added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, $id)
    {
        $data = $request->validate([
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/?category_id=' . $request->category_id)->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $categoryId = $product->category_id;
        
        if ($product->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        
        return redirect('/?category_id=' . $categoryId)->with('success', 'Product deleted.');
    }
}
