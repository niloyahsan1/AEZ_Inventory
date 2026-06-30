<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function totalProductsQuantity()
    {
        $directQuantity = $this->products->sum('quantity');
        $childrenQuantity = $this->children->reduce(function ($carry, $child) {
            return $carry + $child->totalProductsQuantity();
        }, 0);
        return $directQuantity + $childrenQuantity;
    }
}
