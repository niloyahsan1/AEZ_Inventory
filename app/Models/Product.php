<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['buying_price', 'selling_price', 'quantity', 'image_path', 'category_id', 'size', 'rack_no'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
