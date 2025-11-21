<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Recommendation;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    /**
     * 🔹 KPI METRICS
     */
    public function getKpi()
    {
        $totalCustomers = Customer::count();
        $totalRecommendations = Recommendation::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('amount');

        // AVG confidence (Ambil dari Rank 1 saja biar akurat, atau rata-rata top 3)
        // Kita pakai Rank 1 Score aja biar simpel dan cepat
        $avgScore = Recommendation::avg('rank_1_score');

        $activeCustomers = Customer::where('status', 'active')->count();
        $churnedCustomers = Customer::where('status', 'churned')->count();

        return [
            'total_customers'       => $totalCustomers,
            'total_recommendations' => $totalRecommendations,
            'total_transactions'    => $totalTransactions,
            'total_revenue'         => $totalRevenue,
            // Kali 100 biar jadi persen, round 1 desimal
            'avg_confidence_score'  => $avgScore ? round($avgScore * 100, 1) : 0,
            'active_customers'      => $activeCustomers,
            'churned_customers'     => $churnedCustomers,
        ];
    }

    /**
     * 🔹 Weekly Recommendation Trend
     */
    public function getRecommendationTrend()
    {
        return Recommendation::select(
                DB::raw("YEARWEEK(created_at, 1) as week"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->take(10) // Ambil 10 minggu terakhir
            ->get();
    }

    /**
     * 🔹 Weekly Revenue Trend
     */
    public function getRevenueTrend()
    {
        return Transaction::select(
                DB::raw("YEARWEEK(transaction_date, 1) as week"),
                DB::raw("SUM(amount) as revenue")
            )
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * 🔹 Top 5 Most Recommended Products
     * (Menghitung berapa kali muncul di Rank 1 s/d 5)
     */
    public function getTopProducts()
    {
        return Product::select(
                'products.id',
                'products.name',
                'products.category',
                DB::raw("
                    (
                        SELECT COUNT(*) 
                        FROM recommendations 
                        WHERE rank_1_product_id = products.id
                           OR rank_2_product_id = products.id
                           OR rank_3_product_id = products.id
                           OR rank_4_product_id = products.id
                           OR rank_5_product_id = products.id
                    ) as recommended_count
                ")
            )
            ->orderByDesc('recommended_count')
            ->take(5)
            ->get();
    }

    /**
     * 🔹 Top 5 Customers Berdasarkan Revenue
     */
    public function getTopCustomers()
    {
        return Customer::select(
                'customers.id',
                'customers.name',
                // Subquery buat hitung total belanja
                DB::raw("(SELECT SUM(amount) FROM transactions WHERE customer_id = customers.id) AS total_spent")
            )
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();
    }
}