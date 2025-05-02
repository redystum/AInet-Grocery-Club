<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function getImage()
    {
        return $this->photo ? asset('storage/products/' . $this->photo) : asset('storage/producst/product_no_image.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
