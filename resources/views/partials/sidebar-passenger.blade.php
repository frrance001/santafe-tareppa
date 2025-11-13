<!-- ✅ Mobile Toggle Button -->
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    ☰
</button>

<!-- ✅ Sidebar -->
<div class="sidebar">
    <div class="sidebar-content">
        <!-- ✅ Profile Section -->
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
                Welcome,<br><span>{{ Auth::user()->email }}</span>
            </div>
        @endif

        <!-- ✅ Navigation Menu -->
        <ul class="menu">
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

    <!-- ✅ Logout Button -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<!-- ✅ Styles -->
<style>
:root {
    --sidebar-bg: linear-gradient(to bottom, #38bdf8, #60a5fa, #93c5fd);
    --text-light: #fff;
    --text-muted: rgba(255, 255, 255, 0.8);
    --transition-speed: 0.3s;
}

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
.sidebar {
    width: 250px;
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
    transition: transform var(--transition-speed) ease, width var(--transition-speed) ease;
    z-index: 1000;
}

.sidebar-content {
    flex: 1;
}

/* Profile */
.logo {
    text-align: center;
    margin-bottom: 15px;
}

.logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.7);
    background: white;
}

.welcome {
    text-align: center;
    font-weight: bold;
    margin-bottom: 25px;
    font-size: 15px;
}
.welcome span {
    font-weight: normal;
    font-size: 14px;
    color: var(--text-muted);
}

/* Menu */
.menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu li {
    margin: 8px 0;
}

.menu li a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    border-radius: 8px;
    transition: background 0.3s, transform 0.2s;
}

.menu li a:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(5px);
}

.menu li.active a {
    background: rgba(255, 255, 255, 0.4);
    font-weight: bold;
}

/* Logout button */
.logout-form {
    margin-top: auto;
}

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

/* ✅ Mobile toggle button */
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
    font-size: 20px;
    cursor: pointer;
}

/* ✅ Responsive Styles */
@media (max-width: 768px) {
    .sidebar {
        width: 70vw;
        transform: translateX(-100%);
    }
    .sidebar.active {
        transform: translateX(0);
        box-shadow: 4px 0 10px rgba(0,0,0,0.3);
    }
    .sidebar-toggle {
        display: block;
    }
}

/* ✅ Extra small mobiles */
@media (max-width: 480px) {
    .sidebar {
        width: 85vw;
        padding: 15px;
    }
    .logo img {
        width: 60px;
        height: 60px;
    }
    .menu li a {
        font-size: 14px;
        padding: 8px 12px;
    }
    .logout-form button {
        padding: 10px;
        font-size: 14px;
    }
}
</style>

<!-- ✅ Auto-collapse sidebar on mobile click -->
<script>
document.querySelectorAll('.sidebar ul li a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            document.querySelector('.sidebar').classList.remove('active');
        }
    });
});
</script>
