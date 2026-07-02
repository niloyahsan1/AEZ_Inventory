<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

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
        $totalBuyingPriceSum = Product::selectRaw('SUM(buying_price * quantity) as total')->value('total') ?? 0;
        $allCategories = Category::all();
        $allProducts = Product::with('category')->get();

        return view('products.index', compact('categories', 'products', 'currentCategory', 'breadcrumbs', 'allProductsSum', 'totalBuyingPriceSum', 'allCategories', 'allProducts'));
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
            'size' => 'nullable|string|max:255',
            'rack_no' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeAndCompressImage($request->file('image'));
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
            'size' => 'nullable|string|max:255',
            'rack_no' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $this->storeAndCompressImage($request->file('image'));
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
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        
        return redirect('/?category_id=' . $categoryId)->with('success', 'Product deleted.');
    }

    /**
     * AJAX action to adjust product stock quantity directly.
     */
    public function updateQuantity(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update($data);

        return response()->json([
            'success' => true,
            'quantity' => $product->quantity,
            'categories' => Category::all()->mapWithKeys(function ($cat) {
                return [$cat->id => $cat->totalProductsQuantity()];
            }),
            'active_category_direct_sum' => $product->category->products->sum('quantity'),
            'total_quantity' => Product::sum('quantity'),
            'total_buying_price' => Product::selectRaw('SUM(buying_price * quantity) as total')->value('total') ?? 0
        ]);
    }

    /**
     * Store and compress the uploaded product image.
     */
    private function storeAndCompressImage($file)
    {
        // Temporarily increase PHP memory limit for processing large images
        @ini_set('memory_limit', '512M');

        $manager = new ImageManager(new Driver());
        
        // Read/decode the image
        $image = $manager->decode($file);
        
        // Downscale image if width is larger than 800px (retains aspect ratio)
        $image->scale(width: 800);
        
        // Encode as WebP with 75% quality
        $encoded = $image->encode(new WebpEncoder(75));
        
        // Generate a unique filename with .webp extension
        $filename = 'products/' . time() . '_' . uniqid() . '.webp';
        
        // Store on the public storage disk
        Storage::disk('public')->put($filename, (string) $encoded);
        
        return $filename;
    }
}
