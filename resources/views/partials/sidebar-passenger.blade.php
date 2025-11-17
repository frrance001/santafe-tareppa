<!-- ✅ Mobile Burger Button -->
<button class="sidebar-toggle">
    <span class="bar"></span>
</button>

<!-- ✅ Background Overlay (Mobile) -->
<div class="sidebar-overlay"></div>

<!-- ✅ Sidebar -->
<div class="sidebar">
    <div class="sidebar-content">
        @if(Auth::check() && Auth::user()->role === 'Passenger')
            @php
                $profile = Auth::user()->profile_picture;
                $profileUrl = $profile
                    ? (Str::startsWith($profile, ['http://','https://']) 
                        ? $profile 
                        : asset('storage/profile_pictures/' . $profile))
                    : asset('images/default-profile.png');
            @endphp

            <div class="logo">
                <img src="{{ $profileUrl }}" alt="Passenger Profile">
            </div>

            <div class="welcome">
                Welcome,<br><span>{{ Auth::user()->email }}</span>
            </div>
        @endif

        <ul class="menu">
            <li class="{{ request()->routeIs('passenger.dashboard') ? 'active' : '' }}">
                <a href="{{ route('passenger.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </li>
            <li class="{{ request()->routeIs('passenger.view.drivers') ? 'active' : '' }}">
                <a href="{{ route('passenger.view.drivers') }}"><i class="bi bi-geo-alt"></i> View Available Riders</a>
            </li>
            <li class="{{ request()->routeIs('passenger.waiting') ? 'active' : '' }}">
                <a href="{{ route('passenger.waiting') }}"><i class="bi bi-clock-history"></i> Waiting for Driver</a>
            </li>
            <li class="{{ request()->routeIs('passenger.progress') ? 'active' : '' }}">
                <a href="{{ route('passenger.progress') }}"><i class="bi bi-map"></i> Ride Progress</a>
            </li>
            <li class="{{ request()->routeIs('passenger.payment.create') ? 'active' : '' }}">
                <a href="{{ route('passenger.payment.create') }}"><i class="bi bi-credit-card"></i> GCash Payment</a>
            </li>
        </ul>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<style>
:root {
    --sidebar-bg: linear-gradient(to bottom, #38bdf8, #60a5fa, #93c5fd);
    --text-light: #fff;
    --transition-speed: .3s;
}

/* --- BODY RESET --- */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

/* --- SIDEBAR --- */
.sidebar {
    width: 250px;
    height: 100vh;
    background: var(--sidebar-bg);
    color: var(--text-light);
    position: fixed;
    top: 0;
    left: 0;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transform: translateX(0);
    transition: transform var(--transition-speed);
    z-index: 1010;
}

.sidebar-content {
    flex: 1;
    overflow-y: auto;
}

/* --- PROFILE --- */
.logo {
    text-align: center;
    margin-bottom: 15px;
}

.logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,.7);
    object-fit: cover;
}

.welcome {
    text-align: center;
    font-weight: bold;
    margin-bottom: 25px;
    font-size: 14px;
}

.welcome span {
    font-size: 13px;
    opacity: .85;
}

/* --- MENU --- */
.menu { padding: 0; margin: 0; list-style: none; }

.menu li { margin: 6px 0; }

.menu li a {
    color: white;
    text-decoration: none;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
    transition: .3s;
}

.menu li a:hover {
    background: rgba(255,255,255,.25);
    transform: translateX(5px);
}

.menu li.active a {
    background: rgba(255,255,255,.35);
    font-weight: bold;
}

/* --- LOGOUT BUTTON --- */
.logout-form button {
    width: 100%;
    padding: 11px;
    background: #0ea5e9;
    border-radius: 6px;
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: .3s;
}

.logout-form button:hover {
    background: #0284c7;
}

/* --- BURGER BUTTON --- */
.sidebar-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    width: 42px;
    height: 42px;
    background: #38bdf8;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    z-index: 1100;
    display: none;
    align-items: center;
    justify-content: center;
}

/* Animated bars */
.sidebar-toggle .bar {
    width: 24px;
    height: 3px;
    background: white;
    border-radius: 3px;
    position: relative;
    transition: .3s;
}

.sidebar-toggle .bar::before,
.sidebar-toggle .bar::after {
    content: "";
    width: 24px;
    height: 3px;
    background: white;
    border-radius: 3px;
    position: absolute;
    transition: .3s;
}

.sidebar-toggle .bar::before { top: -7px; }
.sidebar-toggle .bar::after { top: 7px; }

/* --- ANIMATION WHEN SIDEBAR ACTIVE --- */
.sidebar.active ~ .sidebar-toggle .bar {
    background: transparent;
}

.sidebar.active ~ .sidebar-toggle .bar::before {
    top: 0;
    transform: rotate(45deg);
}

.sidebar.active ~ .sidebar-toggle .bar::after {
    top: 0;
    transform: rotate(-45deg);
}

/* --- OVERLAY FOR MOBILE --- */
.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 1000;
    opacity: 0;
    transition: .3s;
}

.sidebar.active + .sidebar-overlay {
    display: block;
    opacity: 1;
}

/* --- RESPONSIVE --- */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: flex;
    }
}

@media (max-width: 480px) {
    .sidebar {
        width: 80vw;
        padding: 15px;
    }

    .logo img {
        width: 65px;
        height: 65px;
    }

    .menu li a {
        font-size: 14px;
    }
}
</style>

<script>
// Sidebar toggle + overlay
const sidebar = document.querySelector('.sidebar');
const toggle = document.querySelector('.sidebar-toggle');
const overlay = document.querySelector('.sidebar-overlay');

toggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});

// Close sidebar when clicking overlay
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

// Auto-close when clicking menu on mobile
document.querySelectorAll('.sidebar a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
});
</script>
