/**
 * Enhanced Property Management JavaScript
 * Handles all interactive functionality for property management with modern animations
 */

class PropertyManager {
    constructor() {
        this.init();
        this.currentView = 'table';
        this.searchTimeout = null;
    }

    init() {
        // Check if required dependencies are available
        if (typeof bootstrap === 'undefined') {
            console.warn('Bootstrap is not loaded. Some features may not work.');
            return;
        }

        this.initializeTooltips();
        this.initializeDeleteModal();
        this.initializeSearchForm();
        this.initializeViewToggle();
        this.initializeAnimations();
        this.initializeLoadingStates();
        this.initializeFlashMessages();
        this.cleanupPagination();
    }    /**
     * Initialize Bootstrap tooltips with enhanced styling
     */
    initializeTooltips() {
        if (typeof bootstrap === 'undefined') return;
        
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 300, hide: 100 },
                placement: 'top'
            });
        });
    }    /**
     * Initialize enhanced delete confirmation modal
     */
    initializeDeleteModal() {
        const deleteModalEl = document.getElementById('deleteModal');
        if (!deleteModalEl || typeof bootstrap === 'undefined') return;

        const deleteModal = new bootstrap.Modal(deleteModalEl, {
            backdrop: 'static',
            keyboard: true
        });
        const deleteForm = document.getElementById('deleteForm');
        const propertyTitle = document.getElementById('propertyTitle');

        // Add shake animation to modal on show
        deleteModalEl.addEventListener('shown.bs.modal', () => {
            const modalContent = deleteModalEl.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    modalContent.style.animation = '';
                }, 500);
            }
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const title = button.getAttribute('data-title');
                
                if (propertyTitle && deleteForm) {
                    propertyTitle.textContent = title || 'this property';
                    deleteForm.action = `${window.location.pathname}/${id}`;
                    deleteModal.show();
                }
            });
        });

        // Add loading state to delete button
        if (deleteForm) {
            deleteForm.addEventListener('submit', (e) => {
                const submitBtn = deleteForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                    submitBtn.disabled = true;
                }
            });
        }
    }

    /**
     * Initialize view toggle functionality
     */
    initializeViewToggle() {
        const viewButtons = document.querySelectorAll('[data-view]');
        const propertyTableContainer = document.querySelector('.property-table-container');

        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const view = button.getAttribute('data-view');
                
                // Update active state
                viewButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Switch view with animation
                if (propertyTableContainer) {
                    propertyTableContainer.style.opacity = '0';
                    propertyTableContainer.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        if (view === 'grid') {
                            propertyTableContainer.classList.add('grid-view');
                        } else {
                            propertyTableContainer.classList.remove('grid-view');
                        }
                        
                        propertyTableContainer.style.opacity = '1';
                        propertyTableContainer.style.transform = 'translateY(0)';
                    }, 200);
                }
                
                this.currentView = view;
            });
        });
    }

    /**
     * Initialize animations and transitions
     */
    initializeAnimations() {
        // Animate cards on hover
        const cards = document.querySelectorAll('.property-stats .card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animate table rows
        const tableRows = document.querySelectorAll('.property-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('fade-in-row');
        });

        // Parallax effect for page header
        this.initializeParallax();
    }

    /**
     * Initialize parallax effect for header
     */
    initializeParallax() {
        const header = document.querySelector('.page-header-content');
        if (!header) return;

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        });
    }    /**
     * Initialize flash messages with auto-dismiss
     */
    initializeFlashMessages() {
        const alerts = document.querySelectorAll('.alert');
        
        alerts.forEach(alert => {
            // Add entrance animation
            alert.style.animation = 'slideInDown 0.5s ease-out';
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alert.style.animation = 'slideOutUp 0.5s ease-in';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    /**
     * Initialize search form enhancements
     */
    initializeSearchForm() {
        const searchForm = document.querySelector('form[action*="properties.index"]');
        if (!searchForm) return;

        // Add auto-submit on select change
        const selectElements = searchForm.querySelectorAll('select');
        selectElements.forEach(select => {
            select.addEventListener('change', () => {
                this.debounce(() => {
                    this.addLoadingState(searchForm);
                    searchForm.submit();
                }, 300)();
            });
        });

        // Add debounced search for text input
        const searchInput = searchForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                if (e.target.value.length >= 3 || e.target.value.length === 0) {
                    this.addLoadingState(searchForm);
                    searchForm.submit();
                }
            }, 500));
        }

        // Clear filters functionality
        const clearButton = searchForm.querySelector('a[href*="properties.index"]:not([href*="?"])');
        if (clearButton) {
            clearButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.addLoadingState(searchForm);
                window.location.href = clearButton.href;
            });
        }
    }

    /**
     * Clean up duplicate pagination elements
     */
    cleanupPagination() {
        const paginationElements = document.querySelectorAll('.pagination');
        if (paginationElements.length > 1) {
            for (let i = 1; i < paginationElements.length; i++) {
                paginationElements[i].remove();
            }
        }
    }

    /**
     * Initialize loading states for forms and buttons
     */
    initializeLoadingStates() {
        // Add loading state to pagination links
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', () => {
                this.addLoadingState(document.querySelector('.property-table'));
            });
        });

        // Add loading state to action buttons
        document.querySelectorAll('.btn:not(.delete-btn)').forEach(btn => {
            if (btn.type === 'submit' || btn.tagName === 'A') {
                btn.addEventListener('click', () => {
                    this.addLoadingState(btn);
                });
            }
        });
    }

    /**
     * Add loading state to element
     */
    addLoadingState(element) {
        if (!element) return;
        
        element.classList.add('loading-overlay', 'loading');
        
        // Remove loading state after timeout (fallback)
        setTimeout(() => {
            element.classList.remove('loading');
        }, 5000);
    }

    /**
     * Debounce function to limit function calls
     */
    debounce(func, wait) {
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

    /**
     * Show success toast message
     */
    showSuccessToast(message) {
        this.showToast(message, 'success');
    }

    /**
     * Show error toast message
     */
    showErrorToast(message) {
        this.showToast(message, 'error');
    }    /**
     * Show toast message
     */
    showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1080';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type}" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        // Initialize and show toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: type === 'error' ? 5000 : 3000
        });
        
        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    /**
     * Refresh property statistics
     */
    refreshStats() {
        const statsContainer = document.querySelector('.property-stats');
        if (!statsContainer) return;

        this.addLoadingState(statsContainer);

        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStats = doc.querySelector('.property-stats');
            
            if (newStats) {
                statsContainer.innerHTML = newStats.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
            this.showErrorToast('Failed to refresh statistics');
        })
        .finally(() => {
            statsContainer.classList.remove('loading');
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.propertyManager = new PropertyManager();
    
    // Handle flash messages
    const flashMessages = document.querySelectorAll('[data-flash-message]');
    flashMessages.forEach(msg => {
        const type = msg.getAttribute('data-flash-type') || 'info';
        const message = msg.getAttribute('data-flash-message');
        window.propertyManager.showToast(message, type);
    });
}); 