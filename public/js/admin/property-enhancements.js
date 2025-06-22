/**
 * Enhanced Property Management Interactions
 * Advanced UI interactions and animations
 */

class PropertyEnhancements {
    constructor() {
        // Check if required dependencies are available
        if (typeof bootstrap === 'undefined') {
            console.warn('Bootstrap is not loaded. PropertyEnhancements disabled.');
            return;
        }
        
        this.init();
        this.intersectionObserver = null;
        this.scrollListeners = [];
    }

    init() {
        this.initScrollAnimations();
        this.initAdvancedInteractions();
        this.initQuickActions();
        this.initKeyboardShortcuts();
        this.initProgressiveEnhancement();
    }

    /**
     * Initialize scroll-triggered animations
     */
    initScrollAnimations() {
        // Create intersection observer for scroll animations
        this.intersectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    // Add stagger effect for child elements
                    const children = entry.target.querySelectorAll('.stagger-item');
                    children.forEach((child, index) => {
                        setTimeout(() => {
                            child.classList.add('revealed');
                        }, index * 100);
                    });
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe elements for scroll animations
        document.querySelectorAll('.scroll-fade-in, .reveal').forEach(el => {
            this.intersectionObserver.observe(el);
        });

        // Add scroll-triggered parallax effect
        this.initParallaxEffect();
    }

    /**
     * Initialize parallax effect
     */
    initParallaxEffect() {
        const parallaxElements = document.querySelectorAll('.parallax');
        
        const handleScroll = () => {
            const scrolled = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const rate = scrolled * -0.5;
                element.style.transform = `translate3d(0, ${rate}px, 0)`;
            });
        };

        // Throttle scroll events for performance
        let ticking = false;
        const scrollListener = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', scrollListener, { passive: true });
        this.scrollListeners.push(scrollListener);
    }

    /**
     * Initialize advanced interactions
     */
    initAdvancedInteractions() {
        // Enhanced card interactions
        this.initCardInteractions();
        
        // Advanced form interactions
        this.initFormEnhancements();
        
        // Quick preview functionality
        this.initQuickPreview();
        
        // Batch operations
        this.initBatchOperations();
    }

    /**
     * Initialize enhanced card interactions
     */
    initCardInteractions() {
        const cards = document.querySelectorAll('.property-stats .card');
        
        cards.forEach(card => {
            // Add mouse move effect
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
            
            // Add click ripple effect
            card.addEventListener('click', (e) => {
                this.createRippleEffect(e, card);
            });
        });
    }

    /**
     * Create ripple effect
     */
    createRippleEffect(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(79, 70, 229, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        // Remove ripple after animation
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    /**
     * Initialize form enhancements
     */
    initFormEnhancements() {
        // Enhanced form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            this.enhanceFormValidation(form);
        });
        
        // Smart form filling
        this.initSmartFormFilling();
        
        // Auto-save functionality
        this.initAutoSave();
    }

    /**
     * Enhance form validation
     */
    enhanceFormValidation(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Real-time validation
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                // Clear validation state on input
                input.classList.remove('is-invalid', 'is-valid');
            });
        });
    }

    /**
     * Validate individual field
     */
    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const type = field.type;
        
        let isValid = true;
        let message = '';
        
        if (isRequired && !value) {
            isValid = false;
            message = 'This field is required';
        } else if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            message = 'Please enter a valid email address';
        }
        
        // Update field state
        field.classList.toggle('is-invalid', !isValid);
        field.classList.toggle('is-valid', isValid && value);
        
        // Show/hide feedback
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!isValid && message) {
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentNode.appendChild(feedback);
            }
            feedback.textContent = message;
        } else if (feedback) {
            feedback.remove();
        }
    }

    /**
     * Check if email is valid
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Initialize quick preview functionality
     */
    initQuickPreview() {
        const previewButtons = document.querySelectorAll('[data-quick-preview]');
        
        previewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const url = button.getAttribute('data-quick-preview');
                this.showQuickPreview(url);
            });
        });
    }    /**
     * Show quick preview modal
     */
    showQuickPreview(url) {
        // Create modal if it doesn't exist
        let modal = document.getElementById('quickPreviewModal');
        if (!modal) {
            modal = this.createQuickPreviewModal();
        }
        
        // Load content
        const modalBody = modal.querySelector('.modal-body');
        modalBody.innerHTML = '<div class="text-center p-4"><div class="loading-dots"><div></div><div></div><div></div><div></div></div></div>';
        
        // Show modal
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
        
        // Fetch content
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Failed to load preview</div>';
        });
    }

    /**
     * Create quick preview modal
     */
    createQuickPreviewModal() {
        const modalHtml = `
            <div class="modal fade" id="quickPreviewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quick Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        return document.getElementById('quickPreviewModal');
    }

    /**
     * Initialize batch operations
     */
    initBatchOperations() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-property-id]');
        const batchActions = document.querySelector('.batch-actions');
        
        if (checkboxes.length === 0) return;
        
        // Create batch actions toolbar
        if (!batchActions) {
            this.createBatchActionsToolbar();
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateBatchActions();
            });
        });
        
        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
                this.updateBatchActions();
            });
        }
    }

    /**
     * Initialize keyboard shortcuts
     */
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal.show');
                openModals.forEach(modal => {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                });
            }
            
            // Ctrl/Cmd + A to select all (in table context)
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.target.closest('.property-table')) {
                e.preventDefault();
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.dispatchEvent(new Event('change'));
                }
            }
        });
    }

    /**
     * Initialize progressive enhancement
     */
    initProgressiveEnhancement() {
        // Enhanced loading states
        this.initEnhancedLoading();
        
        // Offline detection
        this.initOfflineDetection();
        
        // Performance monitoring
        this.initPerformanceMonitoring();
    }

    /**
     * Initialize enhanced loading states
     */
    initEnhancedLoading() {
        // Intercept form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    this.setButtonLoading(submitButton, true);
                }
            });
        });
        
        // Intercept link clicks
        document.querySelectorAll('a[href]').forEach(link => {
            if (!link.hasAttribute('target') && !link.href.includes('#')) {
                link.addEventListener('click', () => {
                    this.showGlobalLoading();
                });
            }
        });
    }

    /**
     * Set button loading state
     */
    setButtonLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        } else {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || button.innerHTML;
        }
    }

    /**
     * Show global loading indicator
     */
    showGlobalLoading() {
        let loader = document.getElementById('globalLoader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'globalLoader';
            loader.className = 'global-loader';
            loader.innerHTML = `
                <div class="global-loader-content">
                    <div class="loading-dots">
                        <div></div><div></div><div></div><div></div>
                    </div>
                    <p>Loading...</p>
                </div>
            `;
            document.body.appendChild(loader);
        }
        
        loader.style.display = 'flex';
        setTimeout(() => loader.classList.add('show'), 10);
    }

    /**
     * Initialize offline detection
     */
    initOfflineDetection() {
        const showOfflineMessage = () => {
            if (window.propertyManager) {
                window.propertyManager.showToast('You are currently offline', 'warning');
            }
        };
        
        const showOnlineMessage = () => {
            if (window.propertyManager) {
                window.propertyManager.showToast('Connection restored', 'success');
            }
        };
        
        window.addEventListener('offline', showOfflineMessage);
        window.addEventListener('online', showOnlineMessage);
    }

    /**
     * Initialize performance monitoring
     */
    initPerformanceMonitoring() {
        // Monitor page load performance
        window.addEventListener('load', () => {
            const navigation = performance.getEntriesByType('navigation')[0];
            const loadTime = navigation.loadEventEnd - navigation.loadEventStart;
            
            if (loadTime > 3000) { // If load time > 3s
                console.warn('Slow page load detected:', loadTime + 'ms');
            }
        });
    }

    /**
     * Cleanup resources
     */
    destroy() {
        if (this.intersectionObserver) {
            this.intersectionObserver.disconnect();
        }
        
        this.scrollListeners.forEach(listener => {
            window.removeEventListener('scroll', listener);
        });
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.propertyEnhancements = new PropertyEnhancements();
});

// Add CSS for additional animations
const additionalCSS = `
    @keyframes ripple {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(1); opacity: 0; }
    }
    
    .global-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .global-loader.show {
        opacity: 1;
    }
    
    .global-loader-content {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
`;

// Inject additional CSS
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);
