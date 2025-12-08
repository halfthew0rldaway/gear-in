/**
 * Gear-In Web Animations
 * Lightweight, performant JavaScript animations for the entire web
 */

// Check for reduced motion preference
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// ============================================
// 1. Scroll Reveal Animation (Intersection Observer)
// ============================================
function initScrollReveal() {
    if (prefersReducedMotion) return;

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
}

// ============================================
// 2. Smooth Number Counter Animation
// ============================================
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function animateCounter(element, target, duration = 2000) {
    if (prefersReducedMotion) {
        element.textContent = formatNumber(target);
        return;
    }

    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = formatNumber(target);
            clearInterval(timer);
        } else {
            element.textContent = formatNumber(Math.floor(current));
        }
    }, 16);
}

function initCounterAnimation() {
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                // Read from data-counter-target attribute
                const target = parseInt(entry.target.getAttribute('data-counter-target'));
                if (!isNaN(target) && target >= 0) {
                    animateCounter(entry.target, target);
                    entry.target.dataset.animated = 'true';
                } else {
                    // If animation fails or value is invalid, show the value directly
                    const fallbackValue = entry.target.getAttribute('data-counter-target');
                    if (fallbackValue !== null) {
                        entry.target.textContent = formatNumber(parseInt(fallbackValue) || 0);
                        entry.target.dataset.animated = 'true';
                    }
                }
            }
        });
    }, { threshold: 0.1 }); // Lower threshold for faster trigger

    document.querySelectorAll('[data-counter-target]').forEach(el => {
        counterObserver.observe(el);
    });
}

// ============================================
// 3. Table Row Highlight Animation
// ============================================
function initTableRowHighlight() {
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            if (!prefersReducedMotion) {
                this.style.backgroundColor = 'rgba(0, 0, 0, 0.02)';
                this.style.transition = 'background-color 0.2s ease';
            }
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

// ============================================
// 4. Loading Skeleton Animation
// ============================================
function showSkeleton(container, count = 3) {
    if (!container) return;
    
    container.innerHTML = Array.from({ length: count }, () => 
        '<div class="skeleton h-20 rounded-lg mb-4"></div>'
    ).join('');
}

function hideSkeleton(container) {
    if (!container) return;
    const skeletons = container.querySelectorAll('.skeleton');
    skeletons.forEach(skeleton => {
        skeleton.style.opacity = '0';
        skeleton.style.transition = 'opacity 0.3s ease';
        setTimeout(() => skeleton.remove(), 300);
    });
}

// ============================================
// 5. Toast Notification dengan Slide Animation
// ============================================
function showToast(message, type = 'success', duration = 3000) {
    if (prefersReducedMotion) {
        alert(message);
        return;
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    const colors = {
        success: { bg: '#10b981', text: 'white' },
        error: { bg: '#ef4444', text: 'white' },
        info: { bg: '#3b82f6', text: 'white' },
        warning: { bg: '#f59e0b', text: 'white' }
    };
    
    const color = colors[type] || colors.success;
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 24px;
        background: ${color.bg};
        color: ${color.text};
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease-out;
        font-size: 14px;
        max-width: 400px;
    `;
    
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
    });
    
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Make toast available globally
window.showToast = showToast;

// Auto-show toast from session messages
document.addEventListener('DOMContentLoaded', () => {
    const statusMessage = document.querySelector('.notification-slide');
    if (statusMessage) {
        const message = statusMessage.textContent.trim();
        if (message) {
            showToast(message, 'success');
        }
    }
});

// ============================================
// 6. Image Lazy Loading dengan Fade-in
// ============================================
function initLazyLoading() {
    if (prefersReducedMotion) {
        // Just load all images immediately
        document.querySelectorAll('img[data-src]').forEach(img => {
            img.src = img.dataset.src;
        });
        return;
    }

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.4s ease-in';
                
                img.onload = () => {
                    img.style.opacity = '1';
                };
                
                imageObserver.unobserve(img);
            }
        });
    }, { rootMargin: '50px' });

    document.querySelectorAll('img[data-src]').forEach(img => {
        img.style.opacity = '0';
        imageObserver.observe(img);
    });
}

// ============================================
// 7. Smooth Scroll to Top Button
// ============================================
function initScrollToTop() {
    if (prefersReducedMotion) return;

    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '↑';
    scrollTopBtn.className = 'scroll-top-btn';
    scrollTopBtn.setAttribute('aria-label', 'Scroll to top');
    scrollTopBtn.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #111827;
        color: white;
        border: none;
        cursor: pointer;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.2s ease;
        z-index: 1000;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    scrollTopBtn.addEventListener('mouseenter', () => {
        scrollTopBtn.style.transform = 'scale(1.1)';
    });
    
    scrollTopBtn.addEventListener('mouseleave', () => {
        scrollTopBtn.style.transform = 'scale(1)';
    });
    
    document.body.appendChild(scrollTopBtn);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    }, { passive: true });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ============================================
// 8. Form Validation dengan Shake Animation
// ============================================
function shakeElement(element) {
    if (prefersReducedMotion) return;
    
    element.style.animation = 'shake 0.5s';
    setTimeout(() => {
        element.style.animation = '';
    }, 500);
}

// Make shake available globally
window.shakeElement = shakeElement;

// Auto-shake invalid form fields
document.addEventListener('DOMContentLoaded', () => {
    const invalidFields = document.querySelectorAll('.is-invalid, input:invalid, select:invalid, textarea:invalid');
    invalidFields.forEach(field => {
        shakeElement(field);
    });
});

// ============================================
// 9. Progress Bar untuk Multi-step Forms
// ============================================
function updateProgressBar(currentStep, totalSteps) {
    const progressBar = document.querySelector('.progress-bar');
    if (!progressBar) return;
    
    const percentage = (currentStep / totalSteps) * 100;
    progressBar.style.width = `${percentage}%`;
    progressBar.style.transition = 'width 0.3s ease';
}

// Make progress bar available globally
window.updateProgressBar = updateProgressBar;

// ============================================
// 10. Stagger Animation untuk List Items
// ============================================
function staggerAnimation(selector, delay = 50) {
    if (prefersReducedMotion) {
        document.querySelectorAll(selector).forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
        return;
    }

    const elements = document.querySelectorAll(selector);
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * delay);
    });
}

// Make stagger available globally
window.staggerAnimation = staggerAnimation;

// Auto-stagger for elements with data-stagger attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-stagger]').forEach(container => {
        const delay = parseInt(container.dataset.stagger) || 50;
        const selector = container.dataset.staggerSelector || '> *';
        const items = container.querySelectorAll(selector);
        
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * delay);
        });
    });
});

// ============================================
// Initialize All Animations
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initCounterAnimation();
    initTableRowHighlight();
    initLazyLoading();
    initScrollToTop();
});

// Export functions for use in other scripts
export {
    showToast,
    shakeElement,
    updateProgressBar,
    staggerAnimation,
    showSkeleton,
    hideSkeleton,
    animateCounter
};

