@php
    $user = $user ?? auth()->user();
    if (!$user) return;
@endphp

<div class="login-info-card" style="
    background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
    border-radius: 12px;
    padding: 30px;
    color: white;
    margin-bottom: 25px;
">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
        <!-- Last Login -->
        <div>
            <div style="font-size: 12px; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">
                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Last Login
            </div>
            <div style="font-size: 18px; font-weight: 700;">
                {{ $user->getLastLoginDisplay() }}
            </div>
            @if($user->getDaysSinceLastLogin() >= 0)
                <div style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin-top: 5px;">
                    {{ $user->getDaysSinceLastLogin() }} day{{ $user->getDaysSinceLastLogin() !== 1 ? 's' : '' }} ago
                </div>
            @endif
        </div>

        <!-- Previous Login -->
        <div>
            <div style="font-size: 12px; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">
                <i class="fas fa-history" style="margin-right: 6px;"></i> Previous Login
            </div>
            <div style="font-size: 18px; font-weight: 700;">
                {{ $user->getPreviousLoginDisplay() }}
            </div>
        </div>

        <!-- Total Logins -->
        <div>
            <div style="font-size: 12px; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">
                <i class="fas fa-list" style="margin-right: 6px;"></i> Total Logins
            </div>
            <div style="font-size: 18px; font-weight: 700;">
                {{ $user->total_login_count ?? 0 }}
            </div>
        </div>

        <!-- Total Time Used -->
        <div>
            <div style="font-size: 12px; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">
                <i class="fas fa-hourglass" style="margin-right: 6px;"></i> Time Used
            </div>
            <div style="font-size: 18px; font-weight: 700;">
                {{ $user->getTotalTimeUsedDisplay() }}
            </div>
        </div>
    </div>

    <!-- Additional Details -->
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.2); font-size: 12px; color: rgba(255, 255, 255, 0.8); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Session Status:</strong>
            <br>
            @if(auth()->check() && auth()->id() === $user->id)
                <span style="color: #00d9a3; font-weight: 600;">
                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Active Now
                </span>
            @else
                <span>Offline</span>
            @endif
        </div>
        
        <div>
            <strong>Last Activity:</strong>
            <br>
            @if($user->last_activity_at)
                {{ $user->last_activity_at->diffForHumans() }}
            @else
                <span style="color: #999;">No activity recorded</span>
            @endif
        </div>

        <div>
            <strong>Average Time Per Session:</strong>
            <br>
            @if($user->total_login_count > 0)
                {{ intdiv($user->total_session_minutes ?? 0, $user->total_login_count) }}m
            @else
                <span style="color: #999;">N/A</span>
            @endif
        </div>
    </div>
</div>
