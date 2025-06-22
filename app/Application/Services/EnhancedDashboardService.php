<?php

namespace App\Application\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Enums\PropertyStatus;
use App\Domain\Property\Enums\PropertyType;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * Enhanced Dashboard Service
 * 
 * Provides comprehensive analytics and dashboard data with caching and optimization
 * Implements business logic for metrics, trends, and insights
 */
class EnhancedDashboardService
{
    protected int $cacheTimeout = 300; // 5 minutes

    /**
     * Get comprehensive dashboard data with caching
     * 
     * @return array
     */
    public function getDashboardData(): array
    {
        return Cache::remember('dashboard_data', $this->cacheTimeout, function () {
            return [
                'analytics' => $this->getAnalyticsData(),
                'propertyStats' => $this->getPropertyStatistics(),
                'recentActivities' => $this->getRecentActivities(),
                'topProperties' => $this->getTopProperties(),
                'financialMetrics' => $this->getFinancialMetrics(),
                'userMetrics' => $this->getUserMetrics(),
                'marketTrends' => $this->getMarketTrends(),
                'aiInsights' => $this->getAIInsights(),
            ];
        });
    }

    /**
     * Get core analytics data with trends
     * 
     * @return array
     */
    protected function getAnalyticsData(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Current metrics
        $totalProperties = Property::count();
        $availableProperties = Property::whereIn('status', [
            PropertyStatus::FOR_SALE->value, 
            PropertyStatus::FOR_RENT->value
        ])->count();
        
        $soldProperties = Property::where('status', PropertyStatus::SOLD->value)->count();
        $rentedProperties = Property::where('status', PropertyStatus::RENTED->value)->count();
        
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', $currentMonth)->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        
        $averagePrice = Property::whereIn('status', [
            PropertyStatus::FOR_SALE->value, 
            PropertyStatus::FOR_RENT->value
        ])->avg('price');

        // Calculate trends
        $userGrowthTrend = $newUsersLastMonth > 0 
            ? (($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100 
            : 0;

        $propertiesThisMonth = Property::where('created_at', '>=', $currentMonth)->count();
        $propertiesLastMonth = Property::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        
        $propertyGrowthTrend = $propertiesLastMonth > 0 
            ? (($propertiesThisMonth - $propertiesLastMonth) / $propertiesLastMonth) * 100 
            : 0;

        return [
            'total_properties' => $totalProperties,
            'available_properties' => $availableProperties,
            'sold_properties' => $soldProperties,
            'rented_properties' => $rentedProperties,
            'total_users' => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'average_price' => $averagePrice,
            'trends' => [
                'property_growth' => round($propertyGrowthTrend, 1),
                'user_growth' => round($userGrowthTrend, 1),
                'available_percentage' => $totalProperties > 0 ? round(($availableProperties / $totalProperties) * 100, 1) : 0,
            ]
        ];
    }

    /**
     * Get enhanced property statistics
     * 
     * @return array
     */
    protected function getPropertyStatistics(): array
    {
        // Property distribution by type
        $byType = Property::select('property_type_id', DB::raw('count(*) as count'))
            ->with('propertyType')
            ->groupBy('property_type_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->propertyType?->name ?? 'Unknown' => $item->count];
            })
            ->toArray();

        // Property distribution by status
        $byStatus = Property::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->status) => $item->count];
            })
            ->toArray();

        // Price ranges
        $priceRanges = [
            'Under $100K' => Property::where('price', '<', 100000)->count(),
            '$100K - $500K' => Property::whereBetween('price', [100000, 500000])->count(),
            '$500K - $1M' => Property::whereBetween('price', [500000, 1000000])->count(),
            'Over $1M' => Property::where('price', '>', 1000000)->count(),
        ];

        return [
            'by_type' => $byType,
            'by_status' => $byStatus,
            'by_price_range' => $priceRanges,
            'total_listings' => array_sum($byStatus),
        ];
    }

    /**
     * Get recent activities with enhanced details
     * 
     * @return Collection
     */
    protected function getRecentActivities(): Collection
    {
        // Get recent property activities
        $recentProperties = Property::with(['propertyType', 'user'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($property) {
                return [
                    'title' => $property->title,
                    'action' => $this->getActionFromStatus($property->status),
                    'owner' => $property->user?->name ?? 'Unknown',
                    'timestamp' => $property->created_at,
                    'type' => 'property',
                    'icon' => $this->getIconFromStatus($property->status),
                    'color' => $this->getColorFromStatus($property->status),
                ];
            });

        // Get recent user registrations
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'title' => $user->name,
                    'action' => 'Registered',
                    'owner' => 'System',
                    'timestamp' => $user->created_at,
                    'type' => 'user',
                    'icon' => 'fa-user-plus',
                    'color' => 'success',
                ];
            });

        return $recentProperties->concat($recentUsers)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values();
    }

    /**
     * Get top properties with enhanced metrics
     * 
     * @return Collection
     */
    protected function getTopProperties(): Collection
    {
        return Property::with(['propertyType', 'user'])
            ->whereIn('status', [
                PropertyStatus::FOR_SALE->value,
                PropertyStatus::FOR_RENT->value,
                PropertyStatus::SOLD->value
            ])
            ->orderBy('price', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($property) {
                return (object) [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->price,
                    'area' => $property->area ?? 0,
                    'status' => $property->status,
                    'propertyType' => $property->propertyType,
                    'location' => $property->location ?? 'N/A',
                    'created_at' => $property->created_at,
                ];
            });
    }

    /**
     * Get enhanced financial metrics
     * 
     * @return array
     */
    protected function getFinancialMetrics(): array
    {
        $soldProperties = Property::where('status', PropertyStatus::SOLD->value);
        $rentedProperties = Property::where('status', PropertyStatus::RENTED->value);
        
        $totalSalesValue = $soldProperties->sum('price');
        $totalRentalValue = $rentedProperties->sum('price');
        $averageSalePrice = $soldProperties->avg('price');
        $averageRentalPrice = $rentedProperties->avg('price');
        
        $totalPortfolioValue = Property::whereIn('status', [
            PropertyStatus::FOR_SALE->value,
            PropertyStatus::FOR_RENT->value,
            PropertyStatus::SOLD->value,
            PropertyStatus::RENTED->value
        ])->sum('price');

        // Monthly trends
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $salesThisMonth = Property::where('status', PropertyStatus::SOLD->value)
            ->where('updated_at', '>=', $currentMonth)
            ->sum('price');
            
        $salesLastMonth = Property::where('status', PropertyStatus::SOLD->value)
            ->whereBetween('updated_at', [$lastMonth, $currentMonth])
            ->sum('price');

        $salesTrend = $salesLastMonth > 0 
            ? (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100 
            : 0;

        return [
            'total_sales_value' => $totalSalesValue,
            'total_rental_value' => $totalRentalValue,
            'average_sale_price' => $averageSalePrice,
            'average_rental_price' => $averageRentalPrice,
            'total_portfolio_value' => $totalPortfolioValue,
            'sales_count' => $soldProperties->count(),
            'rental_count' => $rentedProperties->count(),
            'trends' => [
                'sales_growth' => round($salesTrend, 1),
                'monthly_sales' => $salesThisMonth,
            ]
        ];
    }

    /**
     * Get user metrics and analytics
     * 
     * @return array
     */
    protected function getUserMetrics(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
        $newUsersThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        $userGrowthByMonth = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'user_growth_by_month' => $userGrowthByMonth,
            'activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0,
        ];
    }

    /**
     * Get market trends and velocity metrics
     * 
     * @return array
     */
    protected function getMarketTrends(): array
    {
        // Calculate average time to sale/rent
        $soldProperties = Property::where('status', PropertyStatus::SOLD->value)
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();
            
        $avgTimeToSale = $soldProperties->avg(function ($property) {
            return $property->created_at->diffInDays($property->updated_at);
        });

        $rentedProperties = Property::where('status', PropertyStatus::RENTED->value)
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();
            
        $avgTimeToRent = $rentedProperties->avg(function ($property) {
            return $property->created_at->diffInDays($property->updated_at);
        });

        // Market activity calculation
        $totalListings = Property::count();
        $recentActivity = Property::where('updated_at', '>=', Carbon::now()->subDays(30))->count();
        $marketActivity = $totalListings > 0 ? round(($recentActivity / $totalListings) * 100, 1) : 0;

        // Price trends
        $currentMonthAvgPrice = Property::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->avg('price');
        $lastMonthAvgPrice = Property::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->startOfMonth()
        ])->avg('price');

        $priceGrowth = $lastMonthAvgPrice > 0 
            ? (($currentMonthAvgPrice - $lastMonthAvgPrice) / $lastMonthAvgPrice) * 100 
            : 0;

        return [
            'market_velocity' => [
                'market_activity' => $marketActivity,
                'avg_time_to_sale' => round($avgTimeToSale ?? 0),
                'avg_time_to_rent' => round($avgTimeToRent ?? 0),
            ],
            'price_trends' => [
                'current_month_avg' => $currentMonthAvgPrice,
                'last_month_avg' => $lastMonthAvgPrice,
                'growth_percentage' => round($priceGrowth, 1),
            ],
            'demand_indicators' => [
                'high_demand_areas' => $this->getHighDemandAreas(),
                'trending_types' => $this->getTrendingPropertyTypes(),
            ]
        ];
    }

    /**
     * Get AI-powered insights and recommendations
     * 
     * @return array
     */
    protected function getAIInsights(): array
    {
        $insights = [];
        
        // Analyze market conditions
        $analytics = $this->getAnalyticsData();
        $marketTrends = $this->getMarketTrends();
        
        // Generate insights based on data
        if ($analytics['trends']['property_growth'] > 10) {
            $insights[] = [
                'title' => 'High Property Growth Detected',
                'message' => 'Property listings have increased by ' . $analytics['trends']['property_growth'] . '% this month.',
                'action' => 'Consider adjusting marketing strategies to handle increased inventory.',
                'priority' => 'info',
                'type' => 'growth'
            ];
        }

        if ($marketTrends['market_velocity']['avg_time_to_sale'] > 60) {
            $insights[] = [
                'title' => 'Slow Sales Velocity',
                'message' => 'Properties are taking longer than average to sell.',
                'action' => 'Review pricing strategies and market positioning.',
                'priority' => 'high',
                'type' => 'velocity'
            ];
        }

        if ($analytics['trends']['user_growth'] > 15) {
            $insights[] = [
                'title' => 'User Growth Surge',
                'message' => 'New user registrations have increased significantly.',
                'action' => 'Prepare for increased demand and optimize user onboarding.',
                'priority' => 'info',
                'type' => 'users'
            ];
        }

        // Market sentiment analysis
        $sentiment = $this->calculateMarketSentiment($analytics, $marketTrends);

        return [
            'insights' => $insights,
            'market_sentiment' => $sentiment,
            'confidence_score' => $this->calculateConfidenceScore($analytics),
            'recommendations' => $this->generateRecommendations($analytics, $marketTrends)
        ];
    }

    /**
     * Calculate market sentiment
     */
    protected function calculateMarketSentiment(array $analytics, array $marketTrends): string
    {
        $score = 0;
        
        if ($analytics['trends']['property_growth'] > 0) $score += 1;
        if ($analytics['trends']['user_growth'] > 0) $score += 1;
        if ($marketTrends['price_trends']['growth_percentage'] > 0) $score += 1;
        if ($marketTrends['market_velocity']['market_activity'] > 50) $score += 1;
        
        return match (true) {
            $score >= 3 => 'Bullish - Strong market growth',
            $score >= 2 => 'Stable - Moderate market activity',
            $score >= 1 => 'Cautious - Mixed market signals',
            default => 'Bearish - Market challenges detected'
        };
    }

    /**
     * Calculate confidence score for predictions
     */
    protected function calculateConfidenceScore(array $analytics): int
    {
        $dataPoints = count(array_filter([
            $analytics['total_properties'],
            $analytics['total_users'],
            $analytics['average_price']
        ]));
        
        return min(95, $dataPoints * 30 + 5);
    }

    /**
     * Generate AI recommendations
     */
    protected function generateRecommendations(array $analytics, array $marketTrends): array
    {
        $recommendations = [];
        
        if ($marketTrends['market_velocity']['avg_time_to_sale'] > 45) {
            $recommendations[] = 'Consider implementing dynamic pricing strategies';
        }
        
        if ($analytics['trends']['user_growth'] > 10) {
            $recommendations[] = 'Expand customer support capacity to handle growth';
        }
        
        if ($analytics['trends']['property_growth'] < 0) {
            $recommendations[] = 'Implement incentives to attract more property listings';
        }

        return $recommendations;
    }

    /**
     * Helper methods
     */
    protected function getActionFromStatus(string $status): string
    {
        return match ($status) {
            PropertyStatus::SOLD->value => 'Sold',
            PropertyStatus::RENTED->value => 'Rented',
            PropertyStatus::FOR_SALE->value => 'Listed for Sale',
            PropertyStatus::FOR_RENT->value => 'Listed for Rent',
            default => 'Updated'
        };
    }

    protected function getIconFromStatus(string $status): string
    {
        return match ($status) {
            PropertyStatus::SOLD->value => 'fa-check',
            PropertyStatus::RENTED->value => 'fa-key',
            PropertyStatus::FOR_SALE->value => 'fa-plus',
            PropertyStatus::FOR_RENT->value => 'fa-home',
            default => 'fa-edit'
        };
    }

    protected function getColorFromStatus(string $status): string
    {
        return match ($status) {
            PropertyStatus::SOLD->value => 'success',
            PropertyStatus::RENTED->value => 'info',
            PropertyStatus::FOR_SALE->value => 'primary',
            PropertyStatus::FOR_RENT->value => 'warning',
            default => 'secondary'
        };
    }

    protected function getHighDemandAreas(): array
    {
        // This would typically analyze location data
        return ['Downtown', 'Waterfront', 'Tech District'];
    }

    protected function getTrendingPropertyTypes(): array
    {
        return Property::select('property_type_id', DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->with('propertyType')
            ->groupBy('property_type_id')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get()
            ->pluck('propertyType.name')
            ->filter()
            ->toArray();
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache(): void
    {
        Cache::forget('dashboard_data');
    }

    /**
     * Get real-time updates
     */
    public function getRealTimeUpdates(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'active_users' => User::where('last_login_at', '>=', Carbon::now()->subMinutes(5))->count(),
            'recent_activity_count' => Property::where('updated_at', '>=', Carbon::now()->subMinutes(5))->count(),
            'system_status' => 'operational'
        ];
    }
}
