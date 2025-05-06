<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'custom',
    ];
  
    public function getImage()
    {
        return $this->image ? asset('storage/categories/' . $this->image) : asset('storage/categories/category_no_image.png');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
