<?php

namespace App\Repositories;

use App\Models\Recommendation;

class RecommendationRepository
{
    /**
     * Simpan hasil rekomendasi ke database.
     */
    public function store(array $data)
    {
        return Recommendation::create($data);
    }

    /**
     * Ambil history rekomendasi (paginate).
     */
    public function getHistory($limit = 10)
    {
        return Recommendation::with([
                'customer',
                'recommendedBy',
                'rank1Product',
                'rank2Product',
                'rank3Product',
                'rank4Product',
                'rank5Product',
                'overrideProduct'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
/**
     * Cari Rekomendasi berdasarkan ID
     */
    public function find($id)
    {
        return Recommendation::findOrFail($id);
    }

    /**
     * Simpan Data Baru
     */
    public function create(array $data)
    {
        return Recommendation::create($data);
    }

    /**
     * Update Data (Khusus buat Override)
     */
    public function update(Recommendation $recommendation, array $data)
    {
        $recommendation->update($data);
        return $recommendation;
    }
}
