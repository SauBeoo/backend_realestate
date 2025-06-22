<?php

namespace App\Application\Services;

use App\Domain\Property\Models\Property;
use App\Domain\Property\Enums\PropertyStatus;
use App\Domain\Property\Enums\PropertyType;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * Dashboard Service
 * 
 * Provides comprehensive analytics and dashboard data for the admin panel
 * Implements business logic for metrics, trends, and insights
 */
class DashboardService
{
    /**
     * Get comprehensive dashboard data
     * 
     * @return array
     */
    public function getDashboardData(): array
    {
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
    }

    /**
     * Get core analytics data
     * 
     * @return array
     */
    protected function getAnalyticsData(): array
    {
        $totalProperties = Property::count();
        $availableProperties = Property::whereIn('status', [
            PropertyStatus::FOR_SALE->value, 
            PropertyStatus::FOR_RENT->value
        ])->count();
        
        $soldProperties = Property::where('status', PropertyStatus::SOLD->value)->count();
        $rentedProperties = Property::where('status', PropertyStatus::RENTED->value)->count();
        
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        $averagePrice = Property::whereIn('status', [
            PropertyStatus::FOR_SALE->value, 
            PropertyStatus::FOR_RENT->value
        ])->avg('price');

        return [
            'total_properties' => $totalProperties,
            'available_properties' => $availableProperties,
            'sold_properties' => $soldProperties,
            'rented_properties' => $rentedProperties,
            'total_users' => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'average_price' => round($averagePrice ?? 0, 2),
            'occupancy_rate' => $totalProperties > 0 ? round((($soldProperties + $rentedProperties) / $totalProperties) * 100, 2) : 0,
        ];
    }

    /**
     * Get property statistics by type and status
     * 
     * @return array
     */
    protected function getPropertyStatistics(): array
    {
        // Get property counts by type using relationships
        $propertyByType = Property::with('propertyType')
            ->get()
            ->groupBy(function($property) {
                return $property->propertyType?->key ?? 'unknown';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        // Get property counts by status using relationships
        $propertyByStatus = Property::with('propertyStatus')
            ->get()
            ->groupBy(function($property) {
                return $property->propertyStatus?->key ?? 'unknown';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        $monthlyData = Property::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return [
            'by_type' => $propertyByType,
            'by_status' => $propertyByStatus,
            'monthly_listings' => $monthlyData,
            'type_distribution' => $this->calculatePercentageDistribution($propertyByType),
            'status_distribution' => $this->calculatePercentageDistribution($propertyByStatus),
        ];
    }

    /**
     * Get recent system activities
     * 
     * @return Collection
     */
    protected function getRecentActivities(): Collection
    {
        return Property::with(['owner', 'propertyStatus', 'propertyType'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'action' => $this->determineAction($property),
                    'owner' => $property->owner->name ?? 'Unknown',
                    'timestamp' => $property->updated_at,
                    'status' => $property->propertyStatus?->key ?? 'unknown',
                    'type' => $property->propertyType?->key ?? 'unknown',
                ];
            });
    }

    /**
     * Get top performing properties
     * 
     * @return Collection
     */
    protected function getTopProperties(): Collection
    {
        return Property::with('owner')
            ->whereIn('status', [PropertyStatus::SOLD->value, PropertyStatus::RENTED->value])
            ->orderBy('price', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get financial metrics
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
        $averageRentPrice = $rentedProperties->avg('price');

        // Calculate monthly revenue trends
        $monthlyRevenue = Property::select(
                DB::raw('YEAR(updated_at) as year'),
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('SUM(price) as revenue')
            )
            ->whereIn('status', [PropertyStatus::SOLD->value, PropertyStatus::RENTED->value])
            ->where('updated_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return [
            'total_sales_value' => round($totalSalesValue, 2),
            'total_rental_value' => round($totalRentalValue, 2),
            'average_sale_price' => round($averageSalePrice ?? 0, 2),
            'average_rent_price' => round($averageRentPrice ?? 0, 2),
            'monthly_revenue' => $monthlyRevenue,
            'total_portfolio_value' => Property::sum('price'),
        ];
    }

    /**
     * Get user metrics and statistics
     * 
     * @return array
     */
    protected function getUserMetrics(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('updated_at', '>=', Carbon::now()->subDays(30))->count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        $userGrowth = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'new_users_today' => $newUsersToday,
            'new_users_this_week' => $newUsersThisWeek,
            'user_growth' => $userGrowth,
            'activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0,
        ];
    }

    /**
     * Get market trends analysis
     * 
     * @return array
     */
    protected function getMarketTrends(): array
    {
        // Get price trends using relationships - this is more complex but necessary
        // For simplicity, we'll get the data and process it with collections
        $priceHistory = Property::with('propertyType')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->get()
            ->groupBy([
                function($property) { return $property->created_at->format('Y-m-d'); },
                function($property) { return $property->propertyType?->key ?? 'unknown'; }
            ])
            ->map(function ($dateGroup) {
                return $dateGroup->map(function ($typeGroup) {
                    return [
                        'avg_price' => $typeGroup->avg('price'),
                        'count' => $typeGroup->count()
                    ];
                });
            });

        // Get demand analysis using collections instead of raw SQL
        $demandAnalysis = Property::with(['propertyType', 'propertyStatus'])
            ->get()
            ->groupBy(function($property) {
                return $property->propertyType?->key ?? 'unknown';
            })
            ->map(function ($typeGroup, $type) {
                $listings = $typeGroup->count();
                $completed = $typeGroup->filter(function($property) {
                    $statusKey = $property->propertyStatus?->key;
                    return in_array($statusKey, ['sold', 'rented']);
                })->count();
                
                return (object) [
                    'type' => $type,
                    'listings' => $listings,
                    'completed' => $completed,
                    'success_rate' => $listings > 0 ? round(($completed / $listings) * 100, 2) : 0
                ];
            })
            ->values();

        return [
            'price_trends' => $priceHistory,
            'demand_analysis' => $demandAnalysis,
            'market_velocity' => $this->calculateMarketVelocity(),
            'seasonal_patterns' => $this->getSeasonalPatterns(),
        ];
    }

    /**
     * Get AI-powered insights and recommendations
     * 
     * @return array
     */
    protected function getAIInsights(): array
    {
        // Simulate AI insights based on data patterns
        $insights = [];
        
        // Property performance insights
        $performanceByType = Property::with(['propertyType', 'propertyStatus'])
            ->get()
            ->filter(function($property) {
                $statusKey = $property->propertyStatus?->key;
                return in_array($statusKey, [PropertyStatus::SOLD->value, PropertyStatus::RENTED->value]);
            })
            ->groupBy(function($property) {
                return $property->propertyType?->key ?? 'unknown';
            })
            ->map(function($typeGroup, $type) {
                return [
                    'type' => $type,
                    'avg_price' => $typeGroup->avg('price')
                ];
            })
            ->sortByDesc('avg_price')
            ->first();

        if ($performanceByType) {
            $insights[] = [
                'type' => 'performance',
                'title' => 'High Performing Property Type',
                'message' => "Properties of type '{$performanceByType['type']}' show the highest average transaction value.",
                'action' => 'Consider focusing marketing efforts on this property type.',
                'priority' => 'high'
            ];
        }

        // Market opportunity insights
        $underservedTypes = $this->identifyUnderservedMarkets();
        if (!empty($underservedTypes)) {
            $insights[] = [
                'type' => 'opportunity',
                'title' => 'Market Opportunity',
                'message' => "Low inventory detected for: " . implode(', ', $underservedTypes),
                'action' => 'Consider acquiring more properties in these categories.',
                'priority' => 'medium'
            ];
        }

        // Pricing recommendations
        $pricingInsights = $this->analyzePricingPatterns();
        if ($pricingInsights) {
            $insights[] = $pricingInsights;
        }

        return [
            'insights' => $insights,
            'recommendations' => $this->generateRecommendations(),
            'market_sentiment' => $this->calculateMarketSentiment(),
            'forecast' => $this->generateBasicForecast(),
        ];
    }

    /**
     * Helper method to calculate percentage distribution
     * 
     * @param array $data
     * @return array
     */
    protected function calculatePercentageDistribution(array $data): array
    {
        $total = array_sum($data);
        if ($total === 0) {
            return [];
        }

        return array_map(function ($value) use ($total) {
            return round(($value / $total) * 100, 2);
        }, $data);
    }

    /**
     * Determine action type for recent activities
     * 
     * @param Property $property
     * @return string
     */
    protected function determineAction(Property $property): string
    {
        $statusKey = $property->propertyStatus?->key;
        
        if ($statusKey === PropertyStatus::SOLD->value) {
            return 'Sold';
        } elseif ($statusKey === PropertyStatus::RENTED->value) {
            return 'Rented';
        } elseif ($property->created_at->isToday()) {
            return 'Listed';
        } else {
            return 'Updated';
        }
    }

    /**
     * Calculate market velocity metrics
     * 
     * @return array
     */
    protected function calculateMarketVelocity(): array
    {
        $avgTimeToSale = Property::where('status', PropertyStatus::SOLD->value)
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');

        $avgTimeToRent = Property::where('status', PropertyStatus::RENTED->value)
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');

        return [
            'avg_time_to_sale' => round($avgTimeToSale ?? 0, 1),
            'avg_time_to_rent' => round($avgTimeToRent ?? 0, 1),
            'market_activity' => $this->calculateMarketActivity(),
        ];
    }

    /**
     * Get seasonal patterns
     * 
     * @return array
     */
    protected function getSeasonalPatterns(): array
    {
        return Property::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as listings')
            )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }

    /**
     * Identify underserved markets
     * 
     * @return array
     */
    protected function identifyUnderservedMarkets(): array
    {
        $typeCounts = Property::with(['propertyType', 'propertyStatus'])
            ->get()
            ->filter(function($property) {
                $statusKey = $property->propertyStatus?->key;
                return in_array($statusKey, [PropertyStatus::FOR_SALE->value, PropertyStatus::FOR_RENT->value]);
            })
            ->groupBy(function($property) {
                return $property->propertyType?->key ?? 'unknown';
            })
            ->map(function($typeGroup) {
                return $typeGroup->count();
            })
            ->toArray();

        if (empty($typeCounts)) {
            return [];
        }

        $averageCount = array_sum($typeCounts) / count($typeCounts);
        
        return array_keys(array_filter($typeCounts, function ($count) use ($averageCount) {
            return $count < ($averageCount * 0.5); // Less than 50% of average
        }));
    }

    /**
     * Analyze pricing patterns for insights
     * 
     * @return array|null
     */
    protected function analyzePricingPatterns(): ?array
    {
        $overpriced = Property::whereIn('status', [PropertyStatus::FOR_SALE->value, PropertyStatus::FOR_RENT->value])
            ->where('created_at', '<', Carbon::now()->subDays(60))
            ->count();

        if ($overpriced > 0) {
            return [
                'type' => 'pricing',
                'title' => 'Pricing Analysis',
                'message' => "{$overpriced} properties have been on the market for over 60 days.",
                'action' => 'Consider reviewing pricing strategy for these properties.',
                'priority' => 'medium'
            ];
        }

        return null;
    }

    /**
     * Generate basic recommendations
     * 
     * @return array
     */
    protected function generateRecommendations(): array
    {
        return [
            'inventory_management' => 'Consider balancing property types based on demand patterns.',
            'pricing_strategy' => 'Review properties listed over 45 days for potential repricing.',
            'market_expansion' => 'Explore opportunities in high-demand, low-inventory areas.',
            'user_engagement' => 'Implement targeted marketing for inactive users.',
        ];
    }

    /**
     * Calculate market sentiment
     * 
     * @return string
     */
    protected function calculateMarketSentiment(): string
    {
        $recentActivity = Property::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $previousActivity = Property::whereBetween('created_at', [
            Carbon::now()->subDays(60),
            Carbon::now()->subDays(30)
        ])->count();

        if ($recentActivity > $previousActivity * 1.2) {
            return 'Bullish';
        } elseif ($recentActivity < $previousActivity * 0.8) {
            return 'Bearish';
        } else {
            return 'Stable';
        }
    }

    /**
     * Generate basic forecast
     * 
     * @return array
     */
    protected function generateBasicForecast(): array
    {
        $monthlyGrowth = Property::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->pluck('count')
            ->toArray();

        $avgGrowth = count($monthlyGrowth) > 1 ? 
            array_sum(array_slice($monthlyGrowth, -3)) / 3 : 
            ($monthlyGrowth[0] ?? 0);

        return [
            'next_month_listings' => round($avgGrowth * 1.05),
            'growth_trend' => $this->calculateGrowthTrend($monthlyGrowth),
            'confidence' => 'medium',
        ];
    }

    /**
     * Calculate market activity score
     * 
     * @return float
     */
    protected function calculateMarketActivity(): float
    {
        $recentTransactions = Property::whereIn('status', [PropertyStatus::SOLD->value, PropertyStatus::RENTED->value])
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $totalActive = Property::whereIn('status', [PropertyStatus::FOR_SALE->value, PropertyStatus::FOR_RENT->value])
            ->count();

        return $totalActive > 0 ? round(($recentTransactions / $totalActive) * 100, 2) : 0;
    }

    /**
     * Calculate growth trend from data array
     * 
     * @param array $data
     * @return string
     */
    protected function calculateGrowthTrend(array $data): string
    {
        if (count($data) < 2) {
            return 'stable';
        }

        $recent = array_slice($data, -2);
        if ($recent[1] > $recent[0] * 1.1) {
            return 'growing';
        } elseif ($recent[1] < $recent[0] * 0.9) {
            return 'declining';
        } else {
            return 'stable';
        }
    }
}