<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id','name_en','name_ar','sku','image','qty'];

    protected $hidden = ['created_at','updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
