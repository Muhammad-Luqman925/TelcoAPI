<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = Recommendation::with(['customer', 'items.product'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($recommendations);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customerId = $request->customer_id;
        $userId = $request->user()->id;

        try {
            $response = Http::timeout(5)->post(
                env('ML_API_URL', 'http://127.0.0.1:5000/api/recommend'),
                ['customer_id' => $customerId]
            );

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['recommended_products'])) {
                    throw new \Exception('Invalid ML API response format');
                }

                $recommendation = Recommendation::create([
                    'customer_id' => $customerId,
                    'recommended_by' => $userId,
                ]);

                foreach ($data['recommended_products'] as $index => $p) {
                    $recommendation->items()->create([
                        'product_id' => $p['id'],
                        'score' => $p['score'] ?? null,
                        'rank' => $index + 1,
                    ]);
                }

                return response()->json([
                    'message' => 'Recommendation generated via ML API',
                    'source' => 'ML',
                    'data' => $recommendation->load('items.product'),
                ]);
            }

            throw new \Exception('ML API error: ' . $response->status());
        }

        catch (\Throwable $th) {
            Log::warning('ML API unavailable, fallback activated: ' . $th->getMessage());

            $products = Product::inRandomOrder()->take(3)->get();

            $recommendation = Recommendation::create([
                'customer_id' => $customerId,
                'recommended_by' => $userId,
            ]);

            $rank = 1;
            foreach ($products as $p) {
                $recommendation->items()->create([
                    'product_id' => $p->id,
                    'score' => fake()->randomFloat(2, 0.5, 1.0),
                    'rank' => $rank++,
                ]);
            }

            return response()->json([
                'message' => 'ML API unavailable, fallback used',
                'source' => 'fallback',
                'data' => $recommendation->load('items.product'),
            ]);
        }
    }
}
