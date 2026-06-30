<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ]);

        Category::create($data);

        $redirectUrl = $request->parent_id ? '/?category_id=' . $request->parent_id : '/';
        return redirect($redirectUrl)->with('success', 'Category added.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        $redirectUrl = $category->parent_id ? '/?category_id=' . $category->parent_id : '/';
        return redirect($redirectUrl)->with('success', 'Category updated.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $parentId = $category->parent_id;

        // Recursively clean up all product images in this category and all its children subfolders
        $this->cleanupCategoryFiles($category);

        $category->delete();

        $redirectUrl = $parentId ? '/?category_id=' . $parentId : '/';
        return redirect($redirectUrl)->with('success', 'Category and its contents deleted.');
    }

    /**
     * Recursively delete product image files from storage for a category and its children.
     */
    private function cleanupCategoryFiles(Category $category)
    {
        foreach ($category->products as $product) {
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
        }

        foreach ($category->children as $child) {
            $this->cleanupCategoryFiles($child);
        }
    }
}
