/**
 * Admin Panel JavaScript
 * Modern utilities and enhancements for the admin interface
 */

// Document ready state
document.addEventListener('DOMContentLoaded', function() {
    initializeAdminFeatures();
});

/**
 * Initialize all admin features
 */
function initializeAdminFeatures() {
    initializeTooltips();
    initializeConfirmDialogs();
    initializeFormValidation();
    initializeDataTables();
    initializeSearchFeatures();
    initializeThemeToggle();
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize confirmation dialogs for delete actions
 */
function initializeConfirmDialogs() {
    document.querySelectorAll('.btn-delete, .delete-action').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.message || 'Are you sure you want to delete this item?';
            const title = this.dataset.title || 'Confirm Deletion';
            
            showConfirmDialog(title, message, () => {
                // If it's a form submit
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else if (this.href) {
                    // If it's a link
                    window.location.href = this.href;
                }
            });
        });
    });
}

/**
 * Show confirmation dialog
 */
function showConfirmDialog(title, message, onConfirm) {
    const modalId = 'confirmModal' + Date.now();
    const modalHtml = `
        <div class="modal fade" id="${modalId}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    
    document.getElementById('confirmBtn').addEventListener('click', function() {
        onConfirm();
        modal.hide();
    });
    
    modal.show();
    
    // Clean up after modal is hidden
    document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Add loading state to forms on submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalText = submitBtn.textContent || submitBtn.value;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }, 10000);
            }
        });
    });
    
    // Real-time validation feedback
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

/**
 * Validate individual form field
 */
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const type = field.type;
    
    // Clear previous validation
    field.classList.remove('is-valid', 'is-invalid');
    
    // Required field validation
    if (isRequired && !value) {
        field.classList.add('is-invalid');
        return false;
    }
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    // Phone validation
    if (field.name && field.name.includes('phone') && value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/\s/g, ''))) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    if (value || !isRequired) {
        field.classList.add('is-valid');
    }
    
    return true;
}

/**
 * Initialize DataTables for data tables
 */
function initializeDataTables() {
    // Check if DataTables is available
    if (typeof $.fn.DataTable !== 'undefined') {
        document.querySelectorAll('.data-table').forEach(table => {
            $(table).DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    }
}

/**
 * Initialize search features
 */
function initializeSearchFeatures() {
    const searchInputs = document.querySelectorAll('.search-input');
    
    searchInputs.forEach(input => {
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                performSearch(this.value, this.dataset.target);
            }, 300);
        });
    });
}

/**
 * Perform search
 */
function performSearch(query, target) {
    if (!target) return;
    
    const targetElement = document.querySelector(target);
    if (!targetElement) return;
    
    const items = targetElement.querySelectorAll('.searchable-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const matches = text.includes(query.toLowerCase());
        item.style.display = matches ? '' : 'none';
    });
}

/**
 * Initialize theme toggle
 */
function initializeThemeToggle() {
    const themeToggle = document.querySelector('#themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
            
            // Update icon
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        });
        
        // Load saved theme
        const savedTheme = localStorage.getItem('admin-theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.className = 'fas fa-sun';
            }
        }
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info', duration = 3000) {
    const toastId = 'toast' + Date.now();
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" id="${toastId}">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toast = new bootstrap.Toast(document.getElementById(toastId), {
        delay: duration
    });
    toast.show();
    
    // Clean up after toast is hidden
    document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text, message = 'Copied to clipboard!') {
    navigator.clipboard.writeText(text).then(() => {
        showToast(message, 'success');
    }).catch(() => {
        showToast('Failed to copy to clipboard', 'danger');
    });
}

/**
 * Format numbers with thousand separators
 */
function formatNumber(num, decimals = 0) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(num);
}

/**
 * Format currency
 */
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

/**
 * Debounce function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

/**
 * Export functions to global scope for external use
 */
window.AdminPanel = {
    showToast,
    showConfirmDialog,
    copyToClipboard,
    formatNumber,
    formatCurrency,
    debounce,
    showLoading: () => document.getElementById('loadingOverlay')?.classList.remove('d-none'),
    hideLoading: () => document.getElementById('loadingOverlay')?.classList.add('d-none')
}; 