{{--
    Native Feel Enhancement for PWA
    ─────────────────────────────────
    1. Progress bar indicator (thin top bar like native Android)
    2. Fullscreen loading overlay on form submit
    3. Material Design ripple effect on bottom nav
    4. Haptic feedback on nav tap & form submit
    5. Overscroll / pull-to-refresh prevention
    6. Smooth page-exit fade on navigation (MPA-compatible)
--}}

{{-- Progress Bar --}}
<div id="native-progress" class="native-progress" aria-hidden="true">
    <div class="native-progress-bar"></div>
</div>

{{-- Loading Overlay --}}
<div id="native-loading-overlay" class="native-overlay" aria-hidden="true">
    <div class="native-overlay-backdrop"></div>
    <div class="native-overlay-content">
        <div class="native-spinner">
            <svg viewBox="0 0 50 50">
                <circle cx="25" cy="25" r="20" fill="none" stroke-width="4" stroke-linecap="round"></circle>
            </svg>
        </div>
        <p class="native-overlay-text">Memproses...</p>
    </div>
</div>

<script>
(function() {
    'use strict';

    // ─────────────────────────────────────────
    // 1. OVERSCROLL / PULL-TO-REFRESH PREVENTION
    // ─────────────────────────────────────────
    document.documentElement.style.overscrollBehavior = 'contain';
    document.body.style.overscrollBehavior = 'contain';

    // ─────────────────────────────────────────
    // 2. PROGRESS BAR (thin top bar)
    // ─────────────────────────────────────────
    var progressEl = document.getElementById('native-progress');
    var progressBar = progressEl ? progressEl.querySelector('.native-progress-bar') : null;
    var progressTimer = null;
    var progressValue = 0;

    function startProgress() {
        if (!progressBar || !progressEl) return;
        progressValue = 0;
        progressBar.style.transition = 'none';
        progressBar.style.width = '0%';
        progressBar.style.opacity = '1';
        progressEl.classList.add('is-active');

        // Force reflow
        progressBar.offsetWidth;

        progressBar.style.transition = 'width 300ms ease-out';

        // Animate to ~85% in steps
        clearInterval(progressTimer);
        progressTimer = setInterval(function() {
            if (progressValue < 30) {
                progressValue += 8;
            } else if (progressValue < 60) {
                progressValue += 4;
            } else if (progressValue < 85) {
                progressValue += 1.5;
            } else {
                clearInterval(progressTimer);
                return;
            }
            progressBar.style.width = progressValue + '%';
        }, 200);
    }

    function finishProgress() {
        if (!progressBar || !progressEl) return;
        clearInterval(progressTimer);
        progressBar.style.transition = 'width 200ms ease-out';
        progressBar.style.width = '100%';
        setTimeout(function() {
            progressBar.style.transition = 'opacity 300ms ease';
            progressBar.style.opacity = '0';
            setTimeout(function() {
                progressEl.classList.remove('is-active');
                progressBar.style.width = '0%';
            }, 300);
        }, 150);
    }

    // ─────────────────────────────────────────
    // 3. PAGE NAVIGATION — simple fade-out + progress bar
    //    (NO startViewTransition — that's for SPA only)
    //    MPA View Transitions handled purely by CSS @view-transition rule
    // ─────────────────────────────────────────
    var isNavigating = false;
    var mainEl = document.getElementById('main-content') || document.querySelector('main');

    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href]');
        if (!link) return;

        var href = link.getAttribute('href');
        if (!href) return;

        // Skip external links, anchors, javascript:, mailto:, tel:
        if (href.charAt(0) === '#' || href.indexOf('javascript:') === 0 ||
            href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) {
            return;
        }

        // Skip external URLs
        if (href.indexOf('http') === 0 && href.indexOf(window.location.origin) !== 0) {
            return;
        }

        // Skip links with target="_blank" or download attribute
        if (link.target === '_blank' || link.hasAttribute('download')) return;

        // Skip if modifier key pressed (ctrl+click, cmd+click for new tab)
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;

        // Don't double-trigger
        if (isNavigating) {
            e.preventDefault();
            return;
        }

        // Haptic feedback on navigation
        triggerHaptic(10);

        // Start progress bar immediately
        startProgress();

        // Simple fade-out on main content, then navigate
        // Only for browsers that DON'T support MPA View Transitions
        // (browsers with MPA VT handle it via CSS @view-transition)
        var hasMpaViewTransitions = CSS.supports && CSS.supports('view-transition-name', 'none');

        if (!hasMpaViewTransitions && mainEl) {
            e.preventDefault();
            isNavigating = true;
            mainEl.style.transition = 'opacity 120ms ease-out, transform 120ms ease-out';
            mainEl.style.opacity = '0';
            mainEl.style.transform = 'translateY(4px)';

            setTimeout(function() {
                window.location.href = href;
            }, 120);
        }
        // For VT-supporting browsers: let the browser handle it natively
        // the @view-transition CSS rule does the work
    });

    // On pageshow (back/forward), reset state
    window.addEventListener('pageshow', function(e) {
        isNavigating = false;
        finishProgress();

        if (mainEl) {
            mainEl.style.transition = '';
            mainEl.style.opacity = '';
            mainEl.style.transform = '';
        }

        // Restore submit buttons from bfcache
        if (e.persisted) {
            hideOverlay();
            document.querySelectorAll('.btn-loading').forEach(function(btn) {
                btn.disabled = false;
                btn.classList.remove('btn-loading');
                var orig = btn.getAttribute('data-original-html');
                if (orig) btn.innerHTML = orig;
            });
        }
    });

    // ─────────────────────────────────────────
    // 4. FORM SUBMIT LOADING OVERLAY
    // ─────────────────────────────────────────
    var overlay = document.getElementById('native-loading-overlay');
    var overlayText = overlay ? overlay.querySelector('.native-overlay-text') : null;

    function showOverlay(text) {
        if (!overlay) return;
        if (text && overlayText) overlayText.textContent = text;
        overlay.classList.add('is-visible');
        overlay.setAttribute('aria-hidden', 'false');
    }

    function hideOverlay() {
        if (!overlay) return;
        overlay.classList.remove('is-visible');
        overlay.setAttribute('aria-hidden', 'true');
    }

    // Listen to ALL form submits
    document.addEventListener('submit', function(e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        // Skip forms explicitly marked to not show overlay
        if (form.hasAttribute('data-no-overlay')) return;

        // Skip search/filter forms (GET method without explicit marking)
        if (form.method.toUpperCase() === 'GET' && !form.hasAttribute('data-native-submit')) return;

        // Determine loading text
        var loadingText = form.getAttribute('data-submit-text') || 'Memproses...';
        showOverlay(loadingText);

        // Also start progress bar
        startProgress();

        // Haptic feedback
        triggerHaptic(25);

        // Disable submit button and show inline spinner
        var submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitBtn && submitBtn.tagName === 'BUTTON') {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');

            // Store original content and replace with spinner + text
            submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
            var btnText = submitBtn.getAttribute('data-loading-text') || loadingText;
            submitBtn.innerHTML = '<span class="btn-spinner"></span>' + btnText;
        }
    });

    // ─────────────────────────────────────────
    // 5. MATERIAL DESIGN RIPPLE EFFECT
    // ─────────────────────────────────────────
    function createRipple(e) {
        var target = e.currentTarget;
        var rect = target.getBoundingClientRect();
        var ripple = document.createElement('span');

        var size = Math.max(rect.width, rect.height) * 2;
        var x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left - size / 2;
        var y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top - size / 2;

        ripple.className = 'native-ripple';
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        target.style.position = 'relative';
        target.style.overflow = 'hidden';
        target.appendChild(ripple);

        ripple.addEventListener('animationend', function() {
            ripple.remove();
        });
    }

    // Attach ripple to bottom nav items
    document.querySelectorAll('.bottom-nav-item').forEach(function(item) {
        item.addEventListener('touchstart', createRipple, { passive: true });
        item.addEventListener('mousedown', createRipple);
    });

    // Attach ripple to nav items in sidebar
    document.querySelectorAll('.nav-item').forEach(function(item) {
        item.addEventListener('mousedown', createRipple);
    });

    // ─────────────────────────────────────────
    // 6. HAPTIC FEEDBACK
    // ─────────────────────────────────────────
    function triggerHaptic(duration) {
        try {
            if (navigator.vibrate) {
                navigator.vibrate(duration || 10);
            }
        } catch (e) {}
    }

    // Haptic on bottom nav tap
    document.querySelectorAll('.bottom-nav-item').forEach(function(item) {
        item.addEventListener('touchstart', function() {
            triggerHaptic(8);
        }, { passive: true });
    });

})();
</script>
