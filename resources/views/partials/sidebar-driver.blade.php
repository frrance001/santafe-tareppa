<!-- resources/views/partials/sidebar-admin.blade.php -->
<style>
    :root {
        --sidebar-width: 260px;
        --bg-dark: #1a1f2b;
        --bg-hover: #2c3444;
        --accent: #0d6efd;
        --text-light: #dee2e6;
        --text-muted: #adb5bd;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background-color: var(--bg-dark);
        color: var(--text-light);
        position: fixed;
        top: 0;
        left: 0;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.2);
        font-family: 'Inter', sans-serif;
        z-index: 1000;
    }

    .sidebar .welcome {
        font-size: 15px;
        margin-bottom: 30px;
        color: var(--text-muted);
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
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .sidebar ul li a i {
        font-size: 18px;
    }

    .sidebar ul li a:hover {
        background-color: var(--bg-hover);
        color: var(--accent);
    }

    .sidebar ul li.active a {
        background-color: var(--accent);
        color: #fff;
        font-weight: 600;
    }

    .sidebar-footer {
        margin-top: auto;
        font-size: 12px;
        color: var(--text-muted);
        text-align: center;
        padding-top: 20px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
        }

        .sidebar ul {
            display: flex;
            flex-direction: row;
            gap: 8px;
        }

        .sidebar ul li {
            margin-bottom: 0;
        }

        .sidebar ul li a {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>

<div class="sidebar">
    @if(Auth::check())
        <div class="welcome">
            Welcome, <strong>{{ Auth::user()->fullname }}</strong>
        </div>
    @endif

    <ul>
        <li class="{{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <a href="{{ route('driver.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
           <a href="{{ route('driver.showAvailability') }}">
    <i class="bi bi-calendar-check"></i> Set Availability
</a>

        </li>
        <li class="{{ request()->routeIs('driver.accept-rides') ? 'active' : '' }}">
            <a href="{{ route('driver.accept-rides') }}">
                <i class="bi bi-check2-circle"></i> Accept Ride Request
            </a>
        </li>
        <li>
            <li class="{{ request()->routeIs('driver.pickup') ? 'active' : '' }}">
    <a href="{{ route('driver.pickup') }}">
        <i class="bi bi-person-walking"></i> Pick Up Passenger
    </a>
</li>

        </li>
        <li>
            <a href="{{ route('driver.completed.rides') }}">Completed Rides</a>
        </li>
        <li>
            <a href="#"><i class="bi bi-cash-coin"></i> Receive Payment</a>
        </li>
    </ul>
    <!-- Logout button at the bottom -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
    <div class="sidebar-footer">
        &copy; {{ date('Y') }} DriverZone Admin
    </div>
</div>
