<style>
    :root {
        --sidebar-bg: linear-gradient(180deg, #56ccf2, #2f80ed);
        --sidebar-hover: rgba(255, 255, 255, 0.15);
        --sidebar-active: rgba(255, 255, 255, 0.3);
        --text-light: #ffffff;
        --text-muted: #d0e7ff;
        --transition-speed: 0.3s;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background: var(--sidebar-bg);
        color: var(--text-light);
        position: fixed;
        top: 0;
        left: 0;
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1000;
        transition: transform var(--transition-speed) ease;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.25);
        border-radius: 0 20px 20px 0;
    }

    /* Profile */
    .passenger-profile {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .sidebar .welcome {
        margin-bottom: 30px;
        font-size: 16px;
        color: var(--text-light);
        font-weight: 600;
        text-align: center;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    /* Navigation links */
    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
        width: 100%;
    }

    .sidebar ul li {
        margin-bottom: 14px;
    }

    .sidebar ul li a,
    .sidebar ul li form button {
        color: var(--text-light);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        border-radius: 12px;
        background: transparent;
        border: none;
        font-size: 15px;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        width: 100%;
        text-align: left;
    }

    .sidebar ul li a:hover,
    .sidebar ul li form button:hover {
        background-color: var(--sidebar-hover);
        transform: translateX(5px);
    }

    .sidebar ul li.active a {
        background-color: var(--sidebar-active);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    .sidebar ul li a i,
    .sidebar ul li form button i {
        font-size: 18px;
        color: var(--text-muted);
        transition: color var(--transition-speed);
    }

    .sidebar ul li a:hover i,
    .sidebar ul li form button:hover i {
        color: #ffffff;
    }

    /* Sidebar toggle button */
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #2f80ed;
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 20px;
        cursor: pointer;
        z-index: 1100;
    }

    /* Overlay when sidebar is active */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 900;
    }

    .overlay.active {
        display: block;
    }

    /* Responsive behavior */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: block;
        }
    }

    @media (min-width: 993px) {
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    }
</style>

<!-- Sidebar Toggle -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
<div class="overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar">
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

        <img src="{{ $profileUrl }}" alt="Passenger Profile" class="passenger-profile">
        <div class="welcome">Welcome, <strong>{{ Auth::user()->email }}</strong></div>
    @endif

    <ul>
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

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>

<!-- Main Page Content -->
<div class="content">
    @yield('content')
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.overlay').classList.toggle('active');
    }
</script>
