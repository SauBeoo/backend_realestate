<?php

namespace App\Http\Controllers\Admin;

use App\Application\Services\DashboardService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Dashboard Controller for Admin Panel
 * 
 * Handles the main dashboard view with analytics and overview data
 * following the comprehensive management requirements from technical documentation
 */
class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        // Add admin authentication middleware
        // $this->middleware('auth:admin');
    }

    /**
     * Display the admin dashboard with comprehensive analytics
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $dashboardData = $this->dashboardService->getDashboardData();
            
            return view('admin.dashboard.index', [
                'analytics' => $dashboardData['analytics'],
                'propertyStats' => $dashboardData['propertyStats'],
                'recentActivities' => $dashboardData['recentActivities'],
                'topProperties' => $dashboardData['topProperties'],
                'financialMetrics' => $dashboardData['financialMetrics'],
                'userMetrics' => $dashboardData['userMetrics'],
                'marketTrends' => $dashboardData['marketTrends'],
                'aiInsights' => $dashboardData['aiInsights'],
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('admin.dashboard.index')->with('error', 'Unable to load dashboard data');
        }
    }

    /**
     * Get dashboard data via AJAX for real-time updates
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            $data = $this->dashboardService->getDashboardData();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }
}