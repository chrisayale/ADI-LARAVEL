<footer class="bg-white border-top py-3 mt-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-muted small">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @if(request()->secure())
                                <i class="fas fa-shield-alt text-success me-1"></i>
                                <span>Secure Connection</span>
                            @else
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                <span>Not Secure</span>
                            @endif
                        </div>
                        <div>
                            <i class="fas fa-clock me-1"></i>
                            Last login: @auth
                                {{ auth()->user()->last_login_at?->format('M d, Y h:i A') ?? 'First login' }}
                            @else
                                First login
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="text-muted small">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3">
                            <i class="fas fa-server me-1"></i>
                            <span id="serverTime">{{ now()->format('h:i A') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-copyright me-1"></i>
                            {{ date('Y') }} Capital Investment Club
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <span class="badge bg-primary ms-2">Admin</span>
                                @elseif(auth()->user()->hasRole('treasurer'))
                                    <span class="badge bg-info ms-2">Treasurer</span>
                                @else
                                    <span class="badge bg-secondary ms-2">v1.0.0</span>
                                @endif
                            @else
                                <span class="badge bg-secondary ms-2">Guest</span>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    // Update server time
    function updateServerTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        const serverTimeElement = document.getElementById('serverTime');
        if (serverTimeElement) {
            serverTimeElement.textContent = timeString;
        }
    }

    // Update time every minute
    setInterval(updateServerTime, 60000);

    // Run immediately
    document.addEventListener('DOMContentLoaded', function() {
        updateServerTime();
    });
</script>
