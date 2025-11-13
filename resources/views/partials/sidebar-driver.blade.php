<style>
    :root {
        --sidebar-width: 260px;
        --bg-gradient: linear-gradient(180deg, #56ccf2, #2f80ed);
        --bg-hover: rgba(255, 255, 255, 0.15);
        --accent: #ffffff;
        --text-light: #ffffff;
        --text-muted: #d0e7ff;
        --transition-speed: 0.3s;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar base */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--bg-gradient);
        color: var(--text-light);
        position: fixed;
        top: 0;
        left: 0;
        padding: 25px 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.2);
        transition: transform var(--transition-speed) ease;
        z-index: 1000;
    }

    .driver-profile {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 15px;
        border: 2px solid #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .welcome {
        font-size: 16px;
        text-align: center;
        margin-bottom: 30px;
        font-weight: 600;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin-bottom: 10px;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-light);
        transition: all var(--transition-speed) ease;
        font-size: 15px;
    }

    .sidebar ul li a i {
        font-size: 18px;
        color: var(--text-muted);
        transition: color var(--transition-speed);
    }

    .sidebar ul li a:hover {
        background-color: var(--bg-hover);
        color: var(--accent);
    }

    .sidebar ul li.active a {
        background: rgba(255,255,255,0.25);
        color: var(--accent);
        font-weight: 600;
        box-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    .sidebar ul li a:hover i {
        color: var(--accent);
    }

    .logout-form button {
        width: 100%;
        background-color: rgba(255,255,255,0.2);
        border: none;
        color: white;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .logout-form button:hover {
        background-color: rgba(255,255,255,0.35);
    }

    .sidebar-footer {
        text-align: center;
        font-size: 12px;
        padding-top: 20px;
        color: var(--text-light);
    }

    /* ===== Responsive / Mobile ===== */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #2f80ed;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1100;
            font-size: 20px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .overlay.active {
            display: block;
        }
    }

    @media (min-width: 993px) {
        .sidebar-toggle,
        .overlay {
            display: none;
        }

        .content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 20px;
        }
    }
</style>

<!-- Toggle Button -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<!-- Overlay -->
<div class="overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar">
    @if(Auth::check() && Auth::user()->role === 'Driver')
        <img src="{{ Auth::user()->profile_picture ?? asset('images/default-profile.png') }}" 
             alt="Driver Profile" class="driver-profile">

        <div class="welcome">
            Welcome, <strong>{{ Auth::user()->fullname }}</strong>
        </div>
    @endif

    <ul>
        <li class="{{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <a href="{{ route('driver.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('driver.showAvailability') ? 'active' : '' }}">
            <a href="{{ route('driver.showAvailability') }}"><i class="bi bi-calendar-check"></i> Set Availability</a>
        </li>
        <li class="{{ request()->routeIs('driver.accept-rides') ? 'active' : '' }}">
            <a href="{{ route('driver.accept-rides') }}"><i class="bi bi-check2-circle"></i> Accept Ride Request</a>
        </li>
        <li class="{{ request()->routeIs('driver.pickup') ? 'active' : '' }}">
            <a href="{{ route('driver.pickup') }}"><i class="bi bi-person-walking"></i> Pick Up Passenger</a>
        </li>
        <li class="{{ request()->routeIs('driver.completed.rides') ? 'active' : '' }}">
            <a href="{{ route('driver.completed.rides') }}"><i class="bi bi-flag-checkered"></i> Completed Rides</a>
        </li>
        <li>
            <a href="#"><i class="bi bi-cash-coin"></i> Receive Payment</a>
        </li>
    </ul>

    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} DriverZone Admin
    </div>
</div>

<!-- Page Content -->
<div class="content">
    @yield('content')
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.overlay').classList.toggle('active');
    }
</script>
