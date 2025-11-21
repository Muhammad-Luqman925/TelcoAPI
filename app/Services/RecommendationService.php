<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Recommendation;
use App\Repositories\RecommendationRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RecommendationService
{
    protected $recommendationRepository;

    public function __construct(RecommendationRepository $recommendationRepository)
    {
        $this->recommendationRepository = $recommendationRepository;
    }

    public function generateRecommendation($customerId, $userId = null)
    {
        // 1. Ambil Data Customer
        $customer = Customer::findOrFail($customerId);

        // 2. Siapkan Payload (Karena nama kolom DB & API sudah sama, ini jadi simpel)
        $mlInput = [
            "avg_call_duration" => (float) $customer->avg_call_duration,
            "avg_data_usage_gb" => (float) $customer->avg_data_usage_gb,
            "complaint_count"   => (int)   $customer->complaint_count,
            "device_brand"      => (string)$customer->device_brand,
            "monthly_spend"     => (float) $customer->monthly_spend,
            "pct_video_usage"   => (float) $customer->pct_video_usage,
            "plan_type"         => (string)$customer->plan_type,
            "sms_freq"          => (int)   $customer->sms_freq,
            "topup_freq"        => (int)   $customer->topup_freq,
            "travel_score"      => (float) $customer->travel_score,
        ];

        // 3. Kirim ke API Machine Learning
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('ML_API_KEY'),
                 // Pastikan .env sudah diisi
            ])->timeout(15)->post(env('ML_API_URL'), $mlInput);
        Log::info('Cek Key yang dikirim: ' . env('ML_API_KEY'));
            if ($response->failed()) {
                throw new Exception("ML API Error: " . $response->body());
            }

            $mlResult = $response->json();
            Log::info('HASIL ASLI DARI ML:', $mlResult);
            
        } catch (Exception $e) {
            Log::error("Gagal konek ke ML: " . $e->getMessage());
            // Fallback: Kembalikan null atau error, biar controller yang handle
            return ['error' => 'Gagal mendapatkan rekomendasi dari AI'];
        }

        // 4. Mapping Hasil JSON ML ke Struktur Database Kita (Top 5)
        // JSON ML strukturnya: 'top_5_recommendations' => [ {product_name, confidence_score}, ... ]
        
        $recommendationData = [
            'customer_id'    => $customerId,
            'recommended_by' => $userId, // ID Admin/Staff
        ];

        $rawRecs = $mlResult['top_5_recommendations'] ?? [];

        // Loop maksimal 5 kali (untuk rank 1 sampai 5)
        for ($i = 0; $i < 5; $i++) {
            $rank = $i + 1; // 1, 2, 3, 4, 5
            
            if (isset($rawRecs[$i])) {
                $item = $rawRecs[$i];
                
                // Cari ID Produk berdasarkan NAMA dari ML
                $product = Product::where('name', $item['product_name'])->first();

                if ($product) {
                    $recommendationData["rank_{$rank}_product_id"] = $product->id;
                    $recommendationData["rank_{$rank}_score"]      = $item['confidence_score'];
                } else {
                    // Log kalau ada produk dari ML yang gak dikenal di Database kita
                    Log::warning("Produk ML tidak ditemukan di DB: " . $item['product_name']);
                }
            }
        }

        // 5. Simpan ke Tabel Recommendations
        $savedRec = Recommendation::create($recommendationData);

        return [
            'status' => 'success',
            'data'   => $savedRec->load(['rank1Product', 'rank2Product']) // Load relasi biar enak dilihat responnya
        ];
    }
    /**
     * 🔹 AMBIL HISTORY
     */
    public function getHistory()
    {
        return $this->recommendationRepository->gethistory();
    }

    /**
     * 🔹 MANUAL OVERRIDE
     */
    public function overrideRecommendation($id, $productId, $reason)
    {
        // 1. Cari data rekomendasi
        $recommendation = $this->recommendationRepository->find($id);

        // 2. Siapkan data update
        $updateData = [
            'is_overridden'       => true,
            'override_product_id' => $productId,
            'override_reason'     => $reason,
        ];

        // 3. Update di database
        $updated = $this->recommendationRepository->update($recommendation, $updateData);

        // 4. Load relasi produk baru biar di response kelihatan namanya
        return $updated->load('overrideProduct');
    }
}