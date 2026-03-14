/**
 * Auto-Logout Activity Tracker
 * 
 * Tracks user activity and prevents auto-logout by updating last activity timestamp
 * If user is inactive for 10 minutes, they will be auto-logged out
 * 
 * Usage: Include this script in the main layout template
 * <script src="{{ asset('js/activity-tracker.js') }}"></script>
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        SESSION_TIMEOUT: 600000, // 10 minutes in milliseconds
        ACTIVITY_TRACK_INTERVAL: 60000, // Track activity every 1 minute
        WARNING_TIME: 300000, // Show warning at 5 minutes remaining
        CSRF_TOKEN: document.querySelector('meta[name="csrf-token"]')?.content || '',
    };

    let lastActivityTime = Date.now();
    let sessionActive = true;
    let warningShown = false;

    /**
     * Track user activity by making an AJAX call to update last activity time
     */
    function trackActivity() {
        if (!sessionActive) return;

        fetch('/activity/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CONFIG.CSRF_TOKEN,
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.status === 401) {
                // User is not authenticated
                handleSessionExpired();
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                lastActivityTime = Date.now();
                warningShown = false; // Reset warning flag
                console.log('[Activity Tracker] Activity tracked:', new Date().toLocaleTimeString());
            }
        })
        .catch(error => {
            console.error('[Activity Tracker] Error tracking activity:', error);
        });
    }

    /**
     * Get remaining time before session expires
     */
    function getRemainingTime() {
        if (!sessionActive) return;

        fetch('/activity/remaining-time', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': CONFIG.CSRF_TOKEN,
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.status === 401) {
                handleSessionExpired();
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                const remainingSeconds = data.remaining_seconds;
                
                console.log('[Activity Tracker] Remaining time:', remainingSeconds, 'seconds');
                
                // Show warning if less than 5 minutes remaining
                if (remainingSeconds <= 300 && remainingSeconds > 0 && !warningShown) {
                    console.log('[Activity Tracker] Showing warning - 5 minutes remaining');
                    showSessionWarning(remainingSeconds);
                    warningShown = true;
                }
                
                // Auto-logout if time has expired
                if (remainingSeconds <= 0) {
                    console.log('[Activity Tracker] Session time expired, logging out');
                    handleSessionExpired();
                }
            }
        })
        .catch(error => {
            console.error('[Activity Tracker] Error getting remaining time:', error);
        });
    }

    /**
     * Show session expiration warning to user
     */
    function showSessionWarning(secondsRemaining) {
        const minutes = Math.ceil(secondsRemaining / 60);
        
        // Create warning element
        const warningDiv = document.createElement('div');
        warningDiv.id = 'session-warning';
        warningDiv.style.cssText = `
            position: fixed;
            top: 80px;⏰ Session Expiring Soon</div>
            <div style="font-size: 14px; margin-bottom: 15px;">Your session will expire in ${minutes} minute${minutes !== 1 ? 's' : ''} due to inactivity.</div>
            <div style="display: flex; gap: 10px;">
                <button id="extend-session-btn" style="flex: 1; padding: 8px; background: #006d5b; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Extend Session</button>
                <button id="logout-btn" style="flex: 1; padding: 8px; background: 
            border-radius: 8px;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 9999;
            font-family: 'Inter', sans-serif;
        `;
        warningDiv.innerHTML = `
            <div style="font-weight: 700; margin-bottom: 10px;">Session Expiring Soon</div>
            <div style="font-size: 14px; margin-bottom: 15px;">Your session will expire in ${minutes} minute${minutes !== 1 ? 's' : ''} due to inactivity.</div>
            <div style="display: flex; gap: 10px;">
                <button id="extend-session-btn" style="flex: 1; padding: 8px; background: #006d5b; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Extend Session</button>
                <button id="logout-btn" style="flex: 1; padding: 8px; background: ##cc5600; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Logout Now</button>
            </div>
        `;
        
        document.body.appendChild(warningDiv);
        
        // Extend session button
        document.getElementById('extend-session-btn').addEventListener('click', function() {
            trackActivity();
            warningDiv.remove();
            showNotification('Session extended. You have 10 more minutes.', 'success');
        });
        
        // Logout button
        document.getElementById('logout-btn').addEventListener('click', function() {
            forceLogout();
        });
    }

    /**
     * Show notification to user
     */
    function showNotification(message, type = 'info') {
        // Use toastr if available (Bootstrap Toastr)
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            console.log('[Activity Tracker]', message);
        }
    }

    /**
     * Handle session expiration
     */
    function handleSessionExpired() {
        sessionActive = false;
        console.log('[Activity Tracker] Session expired');
        
        showNotification('Your session has expired due to inactivity. Please log in again.', 'warning');
        
        // Redirect to login after 2 seconds
        setTimeout(() => {
            window.location.href = '/login';
        }, 2000);
    }

    /**
     * Force logout the user
     */
    function forceLogout() {
        fetch('/activity/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CONFIG.CSRF_TOKEN,
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/login';
            }
        })
        .catch(error => {
            console.error('[Activity Tracker] Error logging out:', error);
            window.location.href = '/login';
        });
    }

    /**
     * Initialize activity tracking
     * Only initialize if user is logged in (check for auth indicator in page)
     */
    function init() {
        // Check if user is authenticated by looking for CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.log('[Activity Tracker] User not authenticated, skipping initialization');
            return;
        }

        console.log('[Activity Tracker] Initializing activity tracker...');

        // Track activity on user interactions
        document.addEventListener('click', trackActivity, true);
        document.addEventListener('keypress', trackActivity, true);
        document.addEventListener('scroll', trackActivity, true);
        document.addEventListener('mousemove', trackActivity, true);
        document.addEventListener('touchstart', trackActivity, true);

        // Check remaining time more frequently (every 20 seconds instead of 30)
        setInterval(getRemainingTime, 20000);

        // Initial check
        getRemainingTime();

        console.log('[Activity Tracker] Activity tracker initialized (10 minute timeout)');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose functions to global scope for manual use
    window.ActivityTracker = {
        track: trackActivity,
        getRemainingTime: getRemainingTime,
        logout: forceLogout,
        showWarning: showSessionWarning
    };
})();
