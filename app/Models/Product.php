<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'benefits',
    ];
    public function recommendations()
{
    return $this->hasMany(Recommendation::class);
}
public function recommendationItems()
{
    return $this->hasMany(RecommendationItem::class);
}

}
