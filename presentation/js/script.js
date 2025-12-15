// Presentation Controller
class PresentationController {
    constructor() {
        this.currentSlide = 1;
        this.totalSlides = document.querySelectorAll('.slide').length;
        document.getElementById('totalSlides').textContent = this.totalSlides;
        this.isTransitioning = false;
        
        this.init();
    }
    
    init() {
        this.createNavDots();
        this.updateProgress();
        this.bindEvents();
        this.updateSlideCounter();
        
        // Auto-start animations for first slide
        setTimeout(() => {
            this.animateSlide(1);
        }, 300);
    }
    
    createNavDots() {
        const navDots = document.getElementById('navDots');
        for (let i = 1; i <= this.totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = 'nav-dot';
            if (i === 1) dot.classList.add('active');
            dot.addEventListener('click', () => this.goToSlide(i));
            navDots.appendChild(dot);
        }
    }
    
    bindEvents() {
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (this.isTransitioning) return;
            
            // Check if first or last slide
            const isFirstSlide = this.currentSlide === 1;
            const isLastSlide = this.currentSlide === this.totalSlides;
            
            // Prevent slide navigation when scrolling inside slide content
            const activeSlide = document.querySelector('.slide.slide-active');
            const slideContent = activeSlide?.querySelector('.slide-content');
            if (slideContent) {
                const scrollTop = slideContent.scrollTop;
                const scrollHeight = slideContent.scrollHeight;
                const clientHeight = slideContent.clientHeight;
                const scrollable = scrollHeight > clientHeight + 10;
                const isAtTop = scrollTop <= 10;
                const isAtBottom = Math.abs(scrollHeight - clientHeight - scrollTop) <= 10;
                
                // If content is scrollable and not at boundaries, allow normal scroll
                if (scrollable) {
                    if ((e.key === 'ArrowDown' && !isAtBottom) || (e.key === 'ArrowUp' && !isAtTop)) {
                        return; // Let browser handle scroll
                    }
                }
            }
            
            if (e.key === 'ArrowRight' || e.key === ' ') {
                if (isLastSlide) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                this.nextSlide();
            } else if (e.key === 'ArrowLeft') {
                if (isFirstSlide) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                this.prevSlide();
            } else if (e.key === 'Home') {
                e.preventDefault();
                this.goToSlide(1);
            } else if (e.key === 'End') {
                e.preventDefault();
                this.goToSlide(this.totalSlides);
            }
        });
        
        // Button navigation
        document.getElementById('prevBtn').addEventListener('click', () => this.prevSlide());
        document.getElementById('nextBtn').addEventListener('click', () => this.nextSlide());
        
        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        });
        
        // Mouse wheel navigation - only when at top/bottom of slide content
        let wheelTimeout;
        let isScrollingContent = false;
        
        document.addEventListener('wheel', (e) => {
            const activeSlide = document.querySelector('.slide.slide-active');
            const slideContent = activeSlide?.querySelector('.slide-content');
            
            if (!slideContent) return;
            
            const scrollTop = slideContent.scrollTop;
            const scrollHeight = slideContent.scrollHeight;
            const clientHeight = slideContent.clientHeight;
            const scrollable = scrollHeight > clientHeight + 20; // Add buffer to detect scrollability
            const isAtTop = scrollTop <= 15; // Threshold for top
            const isAtBottom = Math.abs(scrollHeight - clientHeight - scrollTop) <= 15; // Threshold for bottom
            
            // Check if first or last slide
            const isFirstSlide = this.currentSlide === 1;
            const isLastSlide = this.currentSlide === this.totalSlides;
            
            // Prevent navigation on first slide when scrolling up, and last slide when scrolling down
            if (isFirstSlide && e.deltaY < 0) {
                e.preventDefault();
                return;
            }
            if (isLastSlide && e.deltaY > 0) {
                e.preventDefault();
                return;
            }
            
            // CRITICAL: If content is scrollable and not at boundaries, ALWAYS allow scroll
            if (scrollable) {
                if (!isAtTop && !isAtBottom) {
                    // Content can scroll - let browser handle it naturally, NO navigation
                    isScrollingContent = true;
                    clearTimeout(wheelTimeout);
                    wheelTimeout = setTimeout(() => {
                        isScrollingContent = false;
                    }, 100);
                    return; // Don't prevent default, don't navigate - just scroll
                }
                
                // At boundaries - check if we should navigate
                // Only navigate if user is clearly trying to go beyond boundaries
                if ((e.deltaY > 0 && isAtBottom) || (e.deltaY < 0 && isAtTop)) {
                    // Small delay to ensure we're not interrupting scroll
                    if (!isScrollingContent) {
                        e.preventDefault();
                        clearTimeout(wheelTimeout);
                        wheelTimeout = setTimeout(() => {
                            if (e.deltaY > 0) {
                                this.nextSlide();
                            } else {
                                this.prevSlide();
                            }
                        }, 300); // Longer delay to prevent accidental navigation
                    }
                }
            } else {
                // Content is not scrollable - navigate slides normally
                if (!isScrollingContent) {
                    e.preventDefault();
                    clearTimeout(wheelTimeout);
                    wheelTimeout = setTimeout(() => {
                        if (e.deltaY > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    }, 150);
                }
            }
        }, { passive: false });
    }
    
    handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                this.nextSlide();
            } else {
                this.prevSlide();
            }
        }
    }
    
    nextSlide() {
        if (this.currentSlide < this.totalSlides) {
            this.goToSlide(this.currentSlide + 1);
        }
    }
    
    prevSlide() {
        if (this.currentSlide > 1) {
            this.goToSlide(this.currentSlide - 1);
        }
    }
    
    goToSlide(slideNumber) {
        if (this.isTransitioning || slideNumber < 1 || slideNumber > this.totalSlides) return;
        if (slideNumber === this.currentSlide) return;
        
        this.isTransitioning = true;
        
        const prevSlide = this.currentSlide;
        this.currentSlide = slideNumber;
        
        // Update slide visibility
        document.querySelectorAll('.slide').forEach((slide, index) => {
            const slideNum = index + 1;
            slide.classList.remove('slide-active', 'slide-prev');
            
            if (slideNum === this.currentSlide) {
                slide.classList.add('slide-active');
                this.animateSlide(this.currentSlide);
            } else if (slideNum < this.currentSlide) {
                slide.classList.add('slide-prev');
            }
        });
        
        // Update navigation dots
        document.querySelectorAll('.nav-dot').forEach((dot, index) => {
            if (index + 1 === this.currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        
        // Update progress bar
        this.updateProgress();
        
        // Update slide counter
        this.updateSlideCounter();
        
        // Update navigation buttons
        this.updateNavButtons();
        
        // Reset transition lock
        setTimeout(() => {
            this.isTransitioning = false;
        }, 800);
    }
    
    animateSlide(slideNumber) {
        const slide = document.querySelector(`[data-slide="${slideNumber}"]`);
        if (!slide) return;
        
        // Animate elements with data-delay
        const elements = slide.querySelectorAll('[data-delay]');
        elements.forEach((el, index) => {
            const delay = parseFloat(el.getAttribute('data-delay')) || 0;
            el.style.animationDelay = `${delay}s`;
            
            // Reset and trigger animation
            el.style.animation = 'none';
            setTimeout(() => {
                el.style.animation = '';
            }, 10);
        });
    }
    
    updateProgress() {
        const progress = (this.currentSlide / this.totalSlides) * 100;
        document.getElementById('progressFill').style.width = `${progress}%`;
    }
    
    updateSlideCounter() {
        document.getElementById('currentSlide').textContent = this.currentSlide;
        document.getElementById('totalSlides').textContent = this.totalSlides;
    }
    
    updateNavButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        prevBtn.disabled = this.currentSlide === 1;
        nextBtn.disabled = this.currentSlide === this.totalSlides;
    }
}

// Initialize presentation when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const presentation = new PresentationController();
    
    // Expose to window for debugging
    window.presentation = presentation;
    
    // Add entrance animation
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease-in';
        document.body.style.opacity = '1';
    }, 100);
});

// Prevent context menu on long press (mobile)
document.addEventListener('contextmenu', (e) => {
    e.preventDefault();
});

// Fullscreen support
document.addEventListener('keydown', (e) => {
    if (e.key === 'f' || e.key === 'F') {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log('Fullscreen not supported');
            });
        } else {
            document.exitFullscreen();
        }
    }
});

