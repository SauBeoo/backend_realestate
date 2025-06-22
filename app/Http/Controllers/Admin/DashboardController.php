<?php

namespace App\Http\Controllers\Admin;

use App\Application\Services\DashboardService;
use App\Application\Services\EnhancedDashboardService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

/**
 * Enhanced Dashboard Controller for Admin Panel
 * 
 * Handles the main dashboard view with comprehensive analytics and real-time data
 * Implements caching, error handling, and API endpoints for dynamic updates
 */
class DashboardController extends Controller
{
    protected EnhancedDashboardService $dashboardService;

    public function __construct(EnhancedDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        // Add admin authentication middleware
        // $this->middleware('auth:admin');
    }    /**
     * Display the enhanced admin dashboard with comprehensive analytics
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
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
            Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            return view('admin.dashboard.index')->with([
                'error' => 'Unable to load dashboard data. Please try refreshing the page.',
                'analytics' => $this->getEmptyAnalytics(),
                'propertyStats' => [],
                'recentActivities' => collect([]),
                'topProperties' => collect([]),
                'financialMetrics' => [],
                'userMetrics' => [],
                'marketTrends' => [],
                'aiInsights' => ['insights' => [], 'market_sentiment' => 'Unable to analyze'],
            ]);
        }
    }    /**
     * Get dashboard data via AJAX for real-time updates
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $data = $this->dashboardService->getDashboardData();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch dashboard data',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get real-time updates for dashboard
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getRealTimeUpdates(Request $request): JsonResponse
    {
        try {
            $updates = $this->dashboardService->getRealTimeUpdates();
            
            return response()->json([
                'success' => true,
                'updates' => $updates,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Real-time updates error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch real-time updates'
            ], 500);
        }
    }

    /**
     * Refresh dashboard cache
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshCache(Request $request): JsonResponse
    {
        try {
            $this->dashboardService->clearCache();
            $data = $this->dashboardService->getDashboardData();
            
            return response()->json([
                'success' => true,
                'message' => 'Dashboard cache refreshed successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Cache refresh error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to refresh cache'
            ], 500);
        }
    }

    /**
     * Get analytics for specific date range
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getAnalyticsByDateRange(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'metrics' => 'array|in:properties,users,financial,market'
        ]);

        try {
            // This would be implemented in the service layer
            $analytics = $this->dashboardService->getAnalyticsByDateRange(
                $request->start_date,
                $request->end_date,
                $request->metrics ?? ['properties', 'users', 'financial']
            );

            return response()->json([
                'success' => true,
                'analytics' => $analytics,
                'period' => [
                    'start' => $request->start_date,
                    'end' => $request->end_date
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Date range analytics error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch analytics for the specified date range'
            ], 500);
        }
    }

    /**
     * Export dashboard data
     * 
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,pdf,csv',
            'report_type' => 'required|in:overview,properties,users,financial,market',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        try {
            // This would integrate with an export service
            $exportData = $this->dashboardService->prepareExportData(
                $request->report_type,
                $request->date_from,
                $request->date_to
            );

            // For now, return JSON - in production this would generate actual files
            return response()->json([
                'success' => true,
                'message' => 'Export prepared successfully',
                'download_url' => route('admin.dashboard.download-export', [
                    'format' => $request->format,
                    'type' => $request->report_type,
                    'token' => encrypt(now()->timestamp)
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to prepare export'
            ], 500);
        }
    }

    /**
     * Get empty analytics structure for error states
     * 
     * @return array
     */
    protected function getEmptyAnalytics(): array
    {
        return [
            'total_properties' => 0,
            'available_properties' => 0,
            'sold_properties' => 0,
            'rented_properties' => 0,
            'total_users' => 0,
            'new_users_this_month' => 0,
            'average_price' => 0,
            'trends' => [
                'property_growth' => 0,
                'user_growth' => 0,
                'available_percentage' => 0,
            ]
        ];
    }
}