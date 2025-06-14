<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

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
