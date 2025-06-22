// Enhanced Dashboard JavaScript
class DashboardManager {
    constructor() {
        this.charts = {};
        this.refreshInterval = null;
        this.realTimeInterval = null;
        this.animationDuration = 1000;
        this.apiEndpoints = {
            data: '/admin/dashboard/data',
            realTime: '/admin/dashboard/real-time-updates',
            refreshCache: '/admin/dashboard/refresh-cache',
            export: '/admin/dashboard/export'
        };
        this.init();
    }

    init() {
        this.initializeCharts();
        this.setupEventListeners();
        this.animateCounters();
        this.setupProgressCircles();
        this.startAutoRefresh();
        this.setupRealTimeUpdates();
        this.setupConnectionMonitoring();
    }    // Initialize all charts
    initializeCharts() {
        this.initPropertyChart();
        this.initPropertyTypeChart();
        this.initPerformanceChart();
    }

    // Property listings chart
    initPropertyChart() {
        const ctx = document.getElementById('propertyChart');
        if (!ctx) return;

        // Sample data - in real app, this would come from backend
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Listings',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'Sales',
                data: [8, 11, 13, 15, 16, 18],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: 'Inter',
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    intersect: false,
                    mode: 'index'
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 12
                        },
                        color: '#6b7280'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 12
                        },
                        color: '#6b7280'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: this.animationDuration,
                easing: 'easeInOutQuart'
            }
        };

        this.charts.propertyChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }

    // Property type distribution chart
    initPropertyTypeChart() {
        const ctx = document.getElementById('propertyTypeChart');
        if (!ctx) return;

        const data = {
            labels: ['Apartments', 'Houses', 'Villas', 'Land'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: [
                    '#4f46e5',
                    '#10b981',
                    '#06b6d4',
                    '#f59e0b'
                ],
                borderWidth: 0,
                hoverOffset: 8
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: this.animationDuration
            }
        };        this.charts.propertyTypeChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    }

    // Performance metrics chart
    initPerformanceChart() {
        const ctx = document.getElementById('performanceChart');
        if (!ctx) return;

        const data = {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            datasets: [{
                label: 'Response Time (ms)',
                data: [250, 280, 320, 290, 260, 240, 230],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            animation: {
                duration: this.animationDuration
            }
        };

        this.charts.performanceChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }

    // Setup event listeners
    setupEventListeners() {
        // Refresh button
        const refreshBtn = document.getElementById('refresh-dashboard');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.refreshDashboard());
        }

        // Chart period filters
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const period = e.target.dataset.period;
                this.updateChartPeriod(period);
            });
        });

        // Property filters
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const filter = e.target.dataset.filter;
                this.updatePropertyFilter(filter);
            });
        });

        // AI insights refresh
        document.querySelectorAll('.btn:contains("Refresh Analysis")').forEach(btn => {
            btn.addEventListener('click', () => this.refreshAIInsights());
        });        // Real-time updates
        this.setupRealTimeUpdates();
    }

    // Setup real-time updates via API
    setupRealTimeUpdates() {
        // Update real-time data every 30 seconds
        this.realTimeInterval = setInterval(() => {
            this.fetchRealTimeUpdates();
        }, 30000);
    }

    async fetchRealTimeUpdates() {
        try {
            const response = await fetch(this.apiEndpoints.realTime);
            if (!response.ok) throw new Error('Failed to fetch real-time updates');
            
            const data = await response.json();
            if (data.success) {
                this.updateRealTimeIndicators(data.updates);
            }
        } catch (error) {
            console.warn('Real-time update failed:', error);
            this.updateConnectionStatus('warning');
        }
    }

    updateRealTimeIndicators(updates) {
        // Update status indicator
        const statusElement = document.getElementById('realTimeStatus');
        if (statusElement) {
            const statusIndicator = statusElement.querySelector('.status-indicator');
            statusIndicator.className = 'status-indicator bg-success rounded-pill px-3 py-2 text-white small';
            statusIndicator.querySelector('.status-text').textContent = 'Live';
        }

        // Update any real-time metrics
        if (updates.active_users !== undefined) {
            // Update active users count if displayed
            console.log('Active users:', updates.active_users);
        }
    }

    // Setup connection monitoring
    setupConnectionMonitoring() {
        window.addEventListener('online', () => {
            this.updateConnectionStatus('online');
            this.hideOfflineIndicator();
            this.startAutoRefresh();
        });

        window.addEventListener('offline', () => {
            this.updateConnectionStatus('offline');
            this.showOfflineIndicator();
            this.stopAutoRefresh();
        });
    }

    updateConnectionStatus(status) {
        const statusElement = document.getElementById('realTimeStatus');
        if (!statusElement) return;

        const indicator = statusElement.querySelector('.status-indicator');
        const text = statusElement.querySelector('.status-text');

        switch (status) {
            case 'online':
                indicator.className = 'status-indicator bg-success rounded-pill px-3 py-2 text-white small';
                text.textContent = 'Live';
                break;
            case 'offline':
                indicator.className = 'status-indicator bg-danger rounded-pill px-3 py-2 text-white small';
                text.textContent = 'Offline';
                break;
            case 'warning':
                indicator.className = 'status-indicator bg-warning rounded-pill px-3 py-2 text-white small';
                text.textContent = 'Limited';
                break;
        }
    }

    showOfflineIndicator() {
        const element = document.getElementById('offlineIndicator');
        if (element) {
            element.classList.remove('d-none');
        }
    }

    hideOfflineIndicator() {
        const element = document.getElementById('offlineIndicator');
        if (element) {
            element.classList.add('d-none');
        }
    }

    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
        if (this.realTimeInterval) {
            clearInterval(this.realTimeInterval);
            this.realTimeInterval = null;
        }
    }

    // Animate counter values
    animateCounters() {
        const counters = document.querySelectorAll('.stats-value');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateValue(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });

        counters.forEach(counter => observer.observe(counter));
    }

    animateValue(element) {
        const target = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format number based on original format
            const formatted = this.formatNumber(Math.floor(current), element.textContent);
            element.textContent = formatted;
        }, 16);
    }

    formatNumber(number, original) {
        if (original.includes('$')) {
            return '$' + number.toLocaleString();
        }
        return number.toLocaleString();
    }

    // Setup progress circles
    setupProgressCircles() {
        document.querySelectorAll('.progress-circle').forEach(circle => {
            const percentage = parseInt(circle.dataset.percentage);
            this.animateProgressCircle(circle, percentage);
        });
    }

    animateProgressCircle(circle, targetPercentage) {
        let currentPercentage = 0;
        const increment = targetPercentage / 100;
        
        const timer = setInterval(() => {
            currentPercentage += increment;
            if (currentPercentage >= targetPercentage) {
                currentPercentage = targetPercentage;
                clearInterval(timer);
            }
            
            const angle = (currentPercentage / 100) * 360;
            circle.style.background = `conic-gradient(#4f46e5 ${angle}deg, #e5e7eb ${angle}deg)`;
        }, 20);
    }    // Refresh dashboard data
    async refreshDashboard() {
        const refreshBtn = document.getElementById('refresh-dashboard');
        const originalText = refreshBtn.innerHTML;
        
        // Show loading state
        this.showLoading();
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...';
        refreshBtn.disabled = true;
        
        try {
            const response = await fetch(this.apiEndpoints.refreshCache, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (!response.ok) throw new Error('Failed to refresh dashboard');
            
            const data = await response.json();
            if (data.success) {
                // Update charts with new data
                this.updateChartsWithData(data.data);
                this.showNotification('Dashboard refreshed successfully', 'success');
            } else {
                throw new Error(data.error || 'Refresh failed');
            }
        } catch (error) {
            console.error('Refresh error:', error);
            this.showNotification('Failed to refresh dashboard', 'error');
        } finally {
            this.hideLoading();
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        }
    }

    showLoading() {
        const loadingElement = document.getElementById('dashboardLoading');
        if (loadingElement) {
            loadingElement.classList.remove('d-none');
        }
    }

    hideLoading() {
        const loadingElement = document.getElementById('dashboardLoading');
        if (loadingElement) {
            loadingElement.classList.add('d-none');
        }
    }

    updateChartsWithData(data) {
        // Update property chart
        if (this.charts.propertyChart && data.propertyStats) {
            // This would update with real data from the API
            this.charts.propertyChart.update('active');
        }

        // Update other charts similarly
        Object.values(this.charts).forEach(chart => {
            chart.update('active');
        });
    }

    // Update chart data
    updateCharts() {
        Object.values(this.charts).forEach(chart => {
            chart.update('active');
        });
    }

    // Update chart period
    updateChartPeriod(period) {
        // Update button states
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-period="${period}"]`).classList.add('active');
        
        // Update chart data based on period
        // In real implementation, fetch data for the selected period
        this.showNotification(`Updated to show last ${period} months`, 'info');
    }

    // Update property filter
    updatePropertyFilter(filter) {
        // Update button states
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
        
        // Update property list based on filter
        this.showNotification(`Properties filtered by ${filter}`, 'info');
    }

    // Refresh AI insights
    async refreshAIInsights() {
        const insightsCard = document.querySelector('.card:has(.fa-robot)');
        if (!insightsCard) return;
        
        // Add loading state
        insightsCard.style.opacity = '0.6';
        
        try {
            // Simulate AI analysis
            await new Promise(resolve => setTimeout(resolve, 2000));
            this.showNotification('AI insights updated', 'success');
        } catch (error) {
            this.showNotification('Failed to refresh AI insights', 'error');
        } finally {
            insightsCard.style.opacity = '1';
        }
    }

    // Setup real-time updates
    setupRealTimeUpdates() {
        // In real implementation, use WebSockets or Server-Sent Events
        this.refreshInterval = setInterval(() => {
            this.updateRealTimeData();
        }, 30000); // Update every 30 seconds
    }

    updateRealTimeData() {
        // Update live metrics without full page refresh
        console.log('Updating real-time data...');
    }    // Show notifications using Bootstrap toasts
    showNotification(message, type = 'info') {
        const toastId = type + 'Toast';
        const toastElement = document.getElementById(toastId);
        
        if (toastElement) {
            const messageElement = toastElement.querySelector('.toast-message');
            if (messageElement) {
                messageElement.textContent = message;
            }
            
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        } else {
            // Fallback to creating a new toast
            this.createToast(message, type);
        }
    }

    createToast(message, type) {
        const toastContainer = document.querySelector('.toast-container') || document.body;
        const colorClass = type === 'error' ? 'bg-danger' : `bg-${type}`;
        
        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white ${colorClass} border-0`;
        toastElement.setAttribute('role', 'alert');
        
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${this.getToastIcon(type)} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastElement);
        
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove after hiding
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }    // Export functionality
    setupExportHandlers() {
        const exportForm = document.getElementById('exportForm');
        if (exportForm) {
            exportForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleExport(new FormData(exportForm));
            });
        }
    }

    async handleExport(formData) {
        const submitBtn = document.querySelector('#exportModal .btn-primary');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Exporting...';
        submitBtn.disabled = true;
        
        try {
            const queryParams = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                queryParams.append(key, value);
            }

            const response = await fetch(`${this.apiEndpoints.export}?${queryParams}`);
            if (!response.ok) throw new Error('Export failed');
            
            const data = await response.json();
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
                modal.hide();
                
                this.showNotification('Export completed successfully. Download will start shortly.', 'success');
                
                // If there's a download URL, trigger download
                if (data.download_url) {
                    window.open(data.download_url, '_blank');
                }
            } else {
                throw new Error(data.error || 'Export failed');
            }
        } catch (error) {
            console.error('Export error:', error);
            this.showNotification('Export failed: ' + error.message, 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Cleanup
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        Object.values(this.charts).forEach(chart => {
            chart.destroy();
        });
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard manager
    window.dashboardManager = new DashboardManager();
    
    // Setup export handlers
    window.dashboardManager.setupExportHandlers();
    
    // Setup tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
    
    // Setup responsive behavior
    window.addEventListener('resize', function() {
        Object.values(window.dashboardManager.charts).forEach(chart => {
            chart.resize();
        });
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.dashboardManager) {
        window.dashboardManager.destroy();
    }
});

// Global functions for component interactions
window.optimizePerformance = function() {
    if (window.dashboardManager) {
        window.dashboardManager.showNotification('Performance optimization started', 'info');
        // Simulate optimization process
        setTimeout(() => {
            window.dashboardManager.showNotification('Performance optimized successfully', 'success');
        }, 2000);
    }
};

window.viewDetailedMetrics = function() {
    // This would redirect to detailed analytics page
    window.location.href = '/admin/analytics';
};

// Performance monitoring utilities
window.DashboardUtils = {
    formatNumber: function(number) {
        if (number >= 1000000) {
            return (number / 1000000).toFixed(1) + 'M';
        } else if (number >= 1000) {
            return (number / 1000).toFixed(1) + 'K';
        }
        return number.toString();
    },
    
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    formatPercentage: function(value) {
        return value.toFixed(1) + '%';
    },
    
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};
