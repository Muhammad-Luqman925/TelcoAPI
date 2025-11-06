<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recommendation;
class RecommendationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'product_id',
        'score',
        'rank',
    ];

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
