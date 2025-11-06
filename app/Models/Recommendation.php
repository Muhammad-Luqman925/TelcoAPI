<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'recommended_by',
        'is_overridden',
        'override_product_id',
    ];

    /* ========================
     * 🔗 RELATIONSHIPS
     * ======================== */

    // Setiap rekomendasi milik satu customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Rekomendasi dibuat oleh user (admin/staff)
    public function recommendedBy()
    {
        return $this->belongsTo(User::class, 'recommended_by');
    }

    // Produk override (jika manual)
    public function overrideProduct()
    {
        return $this->belongsTo(Product::class, 'override_product_id');
    }

    // Daftar item produk yang direkomendasikan
    public function items()
    {
        return $this->hasMany(RecommendationItem::class);
    }
}
