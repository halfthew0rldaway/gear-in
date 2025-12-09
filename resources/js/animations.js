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
// 4.1. Loading Spinner Helper
// ============================================
function showLoadingSpinner(container, size = 40) {
    if (!container) return null;
    
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        border: 3px solid #e5e7eb;
        border-top-color: #111827;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 20px auto;
    `;
    
    container.innerHTML = '';
    container.appendChild(spinner);
    return spinner;
}

function hideLoadingSpinner(container) {
    if (!container) return;
    const spinner = container.querySelector('.loading-spinner');
    if (spinner) {
        spinner.style.opacity = '0';
        spinner.style.transition = 'opacity 0.3s ease';
        setTimeout(() => spinner.remove(), 300);
    }
}

// ============================================
// 4.2. Dots Loader Helper
// ============================================
function showDotsLoader(container) {
    if (!container) return null;
    
    const dots = document.createElement('div');
    dots.className = 'loading-dots';
    dots.innerHTML = '<span></span><span></span><span></span>';
    
    container.innerHTML = '';
    container.appendChild(dots);
    return dots;
}

function hideDotsLoader(container) {
    if (!container) return;
    const loader = container.querySelector('.loading-dots');
    if (loader) {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity 0.3s ease';
        setTimeout(() => loader.remove(), 300);
    }
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

// ============================================
// 5.5. Custom Alert & Confirm Dialogs
// ============================================
function showCustomAlert(message, title = 'Perhatian') {
    return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'custom-dialog-overlay';
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;

        const dialog = document.createElement('div');
        dialog.className = 'custom-dialog';
        dialog.style.cssText = `
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transform: scale(0.95) translateY(10px);
            transition: transform 0.2s ease;
        `;

        const icon = document.createElement('div');
        icon.style.cssText = `
            width: 56px;
            height: 56px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        icon.innerHTML = `
            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        `;

        const titleEl = document.createElement('h3');
        titleEl.textContent = title;
        titleEl.style.cssText = `
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
            text-align: center;
        `;

        const messageEl = document.createElement('p');
        messageEl.textContent = message;
        messageEl.style.cssText = `
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.5;
        `;

        const button = document.createElement('button');
        button.textContent = 'Oke';
        button.style.cssText = `
            width: 100%;
            padding: 12px 24px;
            background: #111827;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
        `;

        button.addEventListener('mouseenter', () => {
            button.style.background = '#000';
        });
        button.addEventListener('mouseleave', () => {
            button.style.background = '#111827';
        });
        button.addEventListener('click', () => {
            overlay.style.opacity = '0';
            dialog.style.transform = 'scale(0.95) translateY(10px)';
            setTimeout(() => {
                overlay.remove();
                resolve();
            }, 200);
        });

        dialog.appendChild(icon);
        dialog.appendChild(titleEl);
        dialog.appendChild(messageEl);
        dialog.appendChild(button);
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);

        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            dialog.style.transform = 'scale(1) translateY(0)';
        });
    });
}

function showCustomConfirm(message, title = 'Konfirmasi') {
    return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'custom-dialog-overlay';
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;

        const dialog = document.createElement('div');
        dialog.className = 'custom-dialog';
        dialog.style.cssText = `
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transform: scale(0.95) translateY(10px);
            transition: transform 0.2s ease;
        `;

        const icon = document.createElement('div');
        icon.style.cssText = `
            width: 56px;
            height: 56px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: ${prefersReducedMotion ? 'none' : 'bounce 0.5s ease'};
        `;
        icon.innerHTML = `
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        `;

        const titleEl = document.createElement('h3');
        titleEl.textContent = title;
        titleEl.style.cssText = `
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
            text-align: center;
        `;

        const messageEl = document.createElement('p');
        messageEl.textContent = message;
        messageEl.style.cssText = `
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.5;
        `;

        const buttonContainer = document.createElement('div');
        buttonContainer.style.cssText = `
            display: flex;
            gap: 12px;
        `;

        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Batal';
        cancelButton.style.cssText = `
            flex: 1;
            padding: 12px 24px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background 0.2s ease;
        `;

        const confirmButton = document.createElement('button');
        confirmButton.textContent = 'Ya, Hapus';
        confirmButton.style.cssText = `
            flex: 1;
            padding: 12px 24px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background 0.2s ease;
        `;

        const closeDialog = (result) => {
            overlay.style.opacity = '0';
            dialog.style.transform = 'scale(0.95) translateY(10px)';
            setTimeout(() => {
                overlay.remove();
                resolve(result);
            }, 200);
        };

        cancelButton.addEventListener('mouseenter', () => {
            cancelButton.style.background = '#e5e7eb';
        });
        cancelButton.addEventListener('mouseleave', () => {
            cancelButton.style.background = '#f3f4f6';
        });
        cancelButton.addEventListener('click', () => closeDialog(false));

        confirmButton.addEventListener('mouseenter', () => {
            confirmButton.style.background = '#dc2626';
        });
        confirmButton.addEventListener('mouseleave', () => {
            confirmButton.style.background = '#ef4444';
        });
        confirmButton.addEventListener('click', () => closeDialog(true));

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeDialog(false);
            }
        });

        buttonContainer.appendChild(cancelButton);
        buttonContainer.appendChild(confirmButton);

        dialog.appendChild(icon);
        dialog.appendChild(titleEl);
        dialog.appendChild(messageEl);
        dialog.appendChild(buttonContainer);
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);

        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            dialog.style.transform = 'scale(1) translateY(0)';
        });
    });
}

// Replace native alert and confirm
window.customAlert = showCustomAlert;
window.customConfirm = showCustomConfirm;

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

// Make loading functions available globally
window.showLoadingSpinner = showLoadingSpinner;
window.hideLoadingSpinner = hideLoadingSpinner;
window.showDotsLoader = showDotsLoader;
window.hideDotsLoader = hideDotsLoader;

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
        let selector = container.dataset.staggerSelector || '> *';
        
        let items = [];
        
        // Fix invalid selector: if selector starts with '>', use :scope > selector
        if (selector.trim().startsWith('>')) {
            // Use :scope to make it a valid selector
            const childSelector = selector.trim().substring(1).trim();
            try {
                items = container.querySelectorAll(`:scope > ${childSelector}`);
            } catch (e) {
                // Fallback: get direct children and filter
                const allChildren = Array.from(container.children);
                if (childSelector === '*') {
                    items = allChildren;
                } else {
                    items = allChildren.filter(child => {
                        try {
                            return child.matches(childSelector);
                        } catch (err) {
                            return false;
                        }
                    });
                }
            }
        } else {
            // Regular selector
            try {
                items = container.querySelectorAll(selector);
            } catch (e) {
                console.warn('Invalid stagger selector:', selector, e);
                // Fallback to direct children
                items = Array.from(container.children);
            }
        }
        
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
    animateCounter,
    showLoadingSpinner,
    hideLoadingSpinner,
    showDotsLoader,
    hideDotsLoader
};

