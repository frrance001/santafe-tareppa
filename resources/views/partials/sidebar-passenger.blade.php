<!-- Mobile Toggle Button -->
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    ☰
</button>

<!-- Sidebar -->
<div class="sidebar">
    <div>
        <!-- ✅ Logo/Profile -->
        @if(Auth::check() && Auth::user()->role === 'Passenger')
            @php
                $profile = Auth::user()->profile_picture ?? null;

                if ($profile) {
                    if (Str::startsWith($profile, ['http://', 'https://'])) {
                        $profileUrl = $profile;
                    } else {
                        $profileUrl = asset('storage/profile_pictures/' . $profile);
                    }
                } else {
                    $profileUrl = asset('images/default-profile.png');
                }
            @endphp

            <div class="logo">
                <img src="{{ $profileUrl }}" alt="Passenger Profile">
            </div>

            <div class="welcome">
                Welcome,<br>{{ Auth::user()->email }}
            </div>
        @endif

        <ul>
            <li class="{{ request()->routeIs('passenger.dashboard') ? 'active' : '' }}">
                <a href="{{ route('passenger.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('passenger.view.drivers') ? 'active' : '' }}">
                <a href="{{ route('passenger.view.drivers') }}">
                    <i class="bi bi-geo-alt"></i> View Available Riders
                </a>
            </li>
            <li class="{{ request()->routeIs('passenger.waiting') ? 'active' : '' }}">
                <a href="{{ route('passenger.waiting') }}">
                    <i class="bi bi-clock-history"></i> Waiting for Driver
                </a>
            </li>
            <li class="{{ request()->routeIs('passenger.progress') ? 'active' : '' }}">
                <a href="{{ route('passenger.progress') }}">
                    <i class="bi bi-map"></i> Ride Progress
                </a>
            </li>
            <li class="{{ request()->routeIs('passenger.payment.create') ? 'active' : '' }}">
                <a href="{{ route('passenger.payment.create') }}">
                    <i class="bi bi-credit-card"></i> GCash Payment
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout button at the bottom -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<!-- Styles -->
<style>
:root {
    --sidebar-bg: linear-gradient(to bottom, #38bdf8, #60a5fa, #93c5fd);
    --text-light: #fff;
    --text-muted: rgba(255,255,255,0.8);
    --transition-speed: 0.3s;
}

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
.sidebar {
    width: 250px; /* desktop default */
    max-width: 80vw;
    height: 100vh;
    background: var(--sidebar-bg);
    color: var(--text-light);
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform var(--transition-speed) ease, width 0.3s ease;
    z-index: 1000;
}

/* Logo/Profile */
.sidebar .logo {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar .logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.7);
    background: white;
}

.sidebar .welcome {
    margin-bottom: 25px;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}

/* Sidebar menu */
.sidebar ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
    flex-grow: 1;
}

.sidebar ul li {
    margin: 10px 0;
}

.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s;
}

.sidebar ul li a:hover {
    background-color: rgba(255, 255, 255, 0.25);
    transform: translateX(5px);
}

.sidebar ul li.active a {
    background-color: rgba(255, 255, 255, 0.4);
    font-weight: bold;
}

/* Logout button */
.logout-form button {
    width: 100%;
    background-color: #0ea5e9;
    border: none;
    color: white;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.logout-form button:hover {
    background-color: #0284c7;
}

/* Mobile toggle */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1100;
    background-color: #38bdf8;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
}

/* Responsive for tablets */
@media (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }
}

/* Responsive for mobile */
@media (max-width: 768px) {
    .sidebar {
        width: 70vw; /* occupy 70% of screen width */
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }
}

/* Extra small mobiles */
@media (max-width: 480px) {
    .sidebar {
        width: 85vw; /* nearly full screen on small mobiles */
        padding: 15px;
    }

    .sidebar .logo img {
        width: 60px;
        height: 60px;
    }

    .sidebar ul li a {
        padding: 8px 12px;
        font-size: 14px;
    }

    .logout-form button {
        padding: 10px;
        font-size: 14px;
    }
}
</style>

<!-- Optional: Collapse sidebar when a menu item is clicked (mobile) -->
<script>
document.querySelectorAll('.sidebar ul li a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            document.querySelector('.sidebar').classList.remove('active');
        }
    });
});
</script>
