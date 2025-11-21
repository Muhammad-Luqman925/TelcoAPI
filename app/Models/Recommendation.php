<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        // 1. Data Relasi User
        'customer_id',
        'recommended_by',

        // 2. Data ML (Top 5 Recommendation)
        'rank_1_product_id', 'rank_1_score',
        'rank_2_product_id', 'rank_2_score',
        'rank_3_product_id', 'rank_3_score',
        'rank_4_product_id', 'rank_4_score',
        'rank_5_product_id', 'rank_5_score',

        // 3. Data Manual Override
        'is_overridden',
        'override_product_id',
        'override_reason',
    ];

    /* ========================
     * 🔗 RELATIONSHIPS
     * ======================== */

    // Setiap rekomendasi milik satu customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // User (Admin/Staff) yang melakukan generate
    public function recommendedBy() // Nanti dipanggil: $rec->recommendedBy->name
    {
        return $this->belongsTo(User::class, 'recommended_by');
    }

    /* --- RELASI KE PRODUK (RANKING 1-5) --- */
    // Supaya bisa ambil nama produknya: $rec->rank1Product->name

    public function rank1Product()
    {
        return $this->belongsTo(Product::class, 'rank_1_product_id');
    }

    public function rank2Product()
    {
        return $this->belongsTo(Product::class, 'rank_2_product_id');
    }

    public function rank3Product()
    {
        return $this->belongsTo(Product::class, 'rank_3_product_id');
    }

    public function rank4Product()
    {
        return $this->belongsTo(Product::class, 'rank_4_product_id');
    }

    public function rank5Product()
    {
        return $this->belongsTo(Product::class, 'rank_5_product_id');
    }

    /* --- RELASI OVERRIDE --- */
    
    public function overrideProduct()
    {
        return $this->belongsTo(Product::class, 'override_product_id');
    }
}