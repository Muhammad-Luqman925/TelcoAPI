<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $dashboardRepository;

    // Inject Repository ke dalam Service
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * Menggabungkan semua statistik dari Repository
     */
    public function getDashboardStats()
    {
        // 1. Ambil semua data terpisah dari Repository
        $kpi = $this->dashboardRepository->getKpi();
        $recTrend = $this->dashboardRepository->getRecommendationTrend();
        $revTrend = $this->dashboardRepository->getRevenueTrend();
        $topProducts = $this->dashboardRepository->getTopProducts();
        $topCustomers = $this->dashboardRepository->getTopCustomers();

        // 2. Bungkus jadi format JSON standar API kita
        return [
            'status' => 'success',
            'data' => [
                'kpi' => $kpi,
                'charts' => [
                    'recommendation_trend' => $recTrend,
                    'revenue_trend' => $revTrend,
                ],
                'lists' => [
                    'top_products' => $topProducts,
                    'top_customers' => $topCustomers,
                ]
            ]
        ];
    }
}