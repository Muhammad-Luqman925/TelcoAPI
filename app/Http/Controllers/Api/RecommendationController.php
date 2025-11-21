<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * POST /recommendations/generate
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        // Ambil user ID dari token yang login (Staff/Admin)
        $userId = $request->user()->id; 

        // Panggil Service Generate (pakai logic ML yang lama)
        // Pastikan di Service function ini sudah disesuaikan pakai Repository
        $result = $this->recommendationService->generateRecommendation(
            $request->customer_id,
            $userId
        );

        if (isset($result['error'])) {
            return response()->json(['status' => 'error', 'message' => $result['error']], 500);
        }

        return response()->json([
            'status' => 'success', 
            'message' => 'Rekomendasi berhasil dibuat',
            'data' => $result['data'] ?? $result
        ]);
    }

    /**
     * GET /recommendations/history
     */
    public function index()
    {
        $history = $this->recommendationService->getHistory();

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }

    /**
     * POST /recommendations/{id}/override
     */
    public function override(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'product_id' => 'required|exists:products,id', // Produk pengganti harus ada
            'reason'     => 'required|string|max:255',     // Alasan wajib diisi
        ]);

        // 2. Panggil Service Override
        $result = $this->recommendationService->overrideRecommendation(
            $id,
            $request->product_id,
            $request->reason
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Rekomendasi berhasil di-override manual',
            'data' => $result
        ]);
    }
}