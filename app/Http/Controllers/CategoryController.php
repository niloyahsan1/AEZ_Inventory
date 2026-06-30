<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($data);

        return redirect('/')->with('success', 'Category added.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($data);

        return redirect('/')->with('success', 'Category updated.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete associated product images from storage before cascade deleting
        foreach ($category->products as $product) {
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
        }

        $category->delete();

        return redirect('/')->with('success', 'Category and its products deleted.');
    }
}
