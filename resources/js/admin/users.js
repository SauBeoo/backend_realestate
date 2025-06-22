/**
 * Modern User Management JavaScript
 * Enhanced with animations and modern features
 */

class UserManagement {
    constructor() {
        this.selectedUsers = new Set();
        this.init();
    }

    init() {
        this.initializeSelectionControls();
        this.initializeBulkActions();
        this.initializeTooltips();
        this.initializeAnimations();
        this.initializeAdvancedSearch();
        this.initializeTableEnhancements();
    }

    initializeSelectionControls() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    this.toggleRowSelection(checkbox.closest('tr'), isChecked);
                    
                    if (isChecked) {
                        this.selectedUsers.add(checkbox.value);
                    } else {
                        this.selectedUsers.delete(checkbox.value);
                    }
                });
                this.updateBulkActions();
            });
        }

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const row = e.target.closest('tr');
                this.toggleRowSelection(row, e.target.checked);
                
                if (e.target.checked) {
                    this.selectedUsers.add(e.target.value);
                } else {
                    this.selectedUsers.delete(e.target.value);
                }
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = this.selectedUsers.size === userCheckboxes.length;
                    selectAllCheckbox.indeterminate = this.selectedUsers.size > 0 && this.selectedUsers.size < userCheckboxes.length;
                }
                
                this.updateBulkActions();
            });
        });
    }

    toggleRowSelection(row, isSelected) {
        if (isSelected) {
            row.classList.add('table-active');
            row.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%)';
        } else {
            row.classList.remove('table-active');
            row.style.background = '';
        }
    }

    initializeBulkActions() {
        const bulkActionForm = document.getElementById('bulkActionForm');
        const executeActionBtn = document.getElementById('executeActionBtn');

        if (bulkActionForm) {
            bulkActionForm.addEventListener('submit', (e) => {
                if (this.selectedUsers.size === 0) {
                    e.preventDefault();
                    this.showNotification('Please select at least one customer.', 'warning');
                    return;
                }

                const selectedAction = bulkActionForm.querySelector('select[name="action"]')?.value;
                if (selectedAction === 'delete') {
                    const confirmed = confirm(
                        `⚠️ Delete ${this.selectedUsers.size} customer(s)?\n\nThis action cannot be undone and will permanently remove all associated data.`
                    );
                    if (!confirmed) {
                        e.preventDefault();
                        return;
                    }
                }

                this.addSelectedUsersToForm(bulkActionForm);
                this.showLoadingState(executeActionBtn);
            });
        }
    }

    updateBulkActions() {
        const selectedCountElement = document.getElementById('selectedCount');
        const executeActionBtn = document.getElementById('executeActionBtn');

        if (selectedCountElement) {
            selectedCountElement.textContent = this.selectedUsers.size;
            
            // Animate count change
            selectedCountElement.style.transform = 'scale(1.2)';
            setTimeout(() => {
                selectedCountElement.style.transform = 'scale(1)';
            }, 200);
        }

        if (executeActionBtn) {
            executeActionBtn.disabled = this.selectedUsers.size === 0;
            
            if (this.selectedUsers.size > 0) {
                executeActionBtn.classList.remove('btn-secondary');
                executeActionBtn.classList.add('btn-primary');
            } else {
                executeActionBtn.classList.remove('btn-primary');
                executeActionBtn.classList.add('btn-secondary');
            }
        }
    }

    addSelectedUsersToForm(form) {
        const existingInputs = form.querySelectorAll('input[name="user_ids[]"]');
        existingInputs.forEach(input => input.remove());

        this.selectedUsers.forEach(userId => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'user_ids[]';
            hiddenInput.value = userId;
            form.appendChild(hiddenInput);
        });
    }

    initializeTooltips() {
        // Modern tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 500, hide: 100 }
            });
        });
    }

    initializeAnimations() {
        // Staggered animations for cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Table row hover effects
        this.initializeTableRowEffects();
    }

    initializeTableRowEffects() {
        const tableRows = document.querySelectorAll('.user-row');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.transform = 'scale(1.01)';
                row.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
            });
            
            row.addEventListener('mouseleave', () => {
                if (!row.classList.contains('table-active')) {
                    row.style.transform = 'scale(1)';
                    row.style.boxShadow = '';
                }
            });
        });
    }

    initializeAdvancedSearch() {
        // Advanced filters toggle
        const advancedFiltersToggle = document.getElementById('advancedFilters');
        const advancedFiltersContent = document.getElementById('advancedFiltersContent');
        
        if (advancedFiltersToggle && advancedFiltersContent) {
            advancedFiltersToggle.addEventListener('change', function() {
                if (this.checked) {
                    advancedFiltersContent.style.maxHeight = advancedFiltersContent.scrollHeight + 'px';
                    advancedFiltersContent.classList.add('show');
                } else {
                    advancedFiltersContent.style.maxHeight = '0';
                    advancedFiltersContent.classList.remove('show');
                }
            });
        }

        // Search input enhancements
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                
                // Add loading indicator
                searchInput.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'%23666\' viewBox=\'0 0 16 16\'%3E%3Cpath d=\'M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z\'/%3E%3Cpath d=\'M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13z\'/%3E%3C/svg%3E")';
                searchInput.style.backgroundRepeat = 'no-repeat';
                searchInput.style.backgroundPosition = 'right 12px center';
                
                searchTimeout = setTimeout(() => {
                    searchInput.style.backgroundImage = '';
                }, 1000);
            });
        }
    }

    initializeTableEnhancements() {
        // Sortable column hover effects
        const sortableHeaders = document.querySelectorAll('th a');
        sortableHeaders.forEach(header => {
            header.addEventListener('mouseenter', () => {
                header.style.color = '#667eea';
                header.style.transform = 'translateX(2px)';
            });
            
            header.addEventListener('mouseleave', () => {
                header.style.color = '';
                header.style.transform = 'translateX(0)';
            });
        });

        // Badge hover effects
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.addEventListener('mouseenter', () => {
                badge.style.transform = 'scale(1.05)';
                badge.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            });
            
            badge.addEventListener('mouseleave', () => {
                badge.style.transform = 'scale(1)';
                badge.style.boxShadow = '';
            });
        });
    }

    showLoadingState(button) {
        const originalText = button.innerHTML;
        button.classList.add('loading');
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        button.disabled = true;
        
        // Reset after 3 seconds (or when form actually submits)
        setTimeout(() => {
            button.classList.remove('loading');
            button.innerHTML = originalText;
            button.disabled = false;
        }, 3000);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 12px;
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${this.getNotificationIcon(type)} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            'success': 'check-circle',
            'warning': 'exclamation-triangle',
            'danger': 'exclamation-circle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Enhanced delete confirmation
window.confirmDelete = (userName) => {
    return confirm(`⚠️ Delete Customer: ${userName}\n\nThis action will permanently remove:\n• Customer profile and data\n• Associated properties and listings\n• All related records\n\nThis cannot be undone. Continue?`);
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new UserManagement();
    
    // Add smooth scrolling to page
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Page load animations
    document.body.style.opacity = '0';
    document.body.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        document.body.style.transition = 'all 0.6s ease-out';
        document.body.style.opacity = '1';
        document.body.style.transform = 'translateY(0)';
    }, 100);
});

// Utility functions
window.UserUtils = {
    formatNumber: (num) => new Intl.NumberFormat().format(num),
    formatCurrency: (amount) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount),
    formatDate: (date) => new Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'short', day: 'numeric' }).format(new Date(date)),
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    copyToClipboard: async (text) => {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            return false;
        }
    }
};