<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    /* ---------------- Sidebar ---------------- */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #38bdf8, #60a5fa, #93c5fd);
        color: #fff;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease-in-out;
        z-index: 1000;
    }

    .sidebar .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar .logo img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.7);
        background: white;
        object-fit: cover;
    }

    .welcome {
        text-align: center;
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .sidebar ul {
        padding: 0;
        margin: 0;
        list-style: none;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin: 10px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .sidebar ul li a:hover {
        background: rgba(255,255,255,0.25);
        transform: translateX(5px);
    }

    .sidebar ul li.active a {
        background: rgba(255,255,255,0.4);
        font-weight: bold;
    }

    .logout-form button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 6px;
        background: #0ea5e9;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .logout-form button:hover {
        background: #0284c7;
    }

    /* ---------------- Mobile Responsive ---------------- */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: #38bdf8;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 20px;
            cursor: pointer;
        }
    }

    @media (min-width: 769px) {
        .sidebar-toggle {
            display: none;
        }

        /* Prevent main content from hiding under sidebar */
        .content {
            margin-left: 250px;
        }
    }
</style>

<!-- Mobile Toggle Button -->
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    ☰
</button>

<!-- Sidebar -->
<div class="sidebar">

    <div>
        <div class="logo">
            <img src="/images/admin.png" alt="Admin Logo">
        </div>

        @if(Auth::check() && Auth::user()->role === 'Admin')
            <div class="welcome">
                Welcome,<br>{{ Auth::user()->fullname }}
            </div>
        @endif

        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.manage') ? 'active' : '' }}">
                <a href="{{ route('admin.manage') }}">
                    <i class="bi bi-people"></i> Manage Users
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.view-ride') ? 'active' : '' }}">
                <a href="{{ route('admin.view-ride') }}">
                    <i class="bi bi-map"></i> View Ride
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
                <a href="{{ route('admin.complaints.index') }}">
                    <i class="bi bi-exclamation-diamond"></i> Complaints/Reports
                </a>
            </li>

            <li class="{{ request()->is('admin/activity-logging*') ? 'active' : '' }}">
                <a href="{{ url('admin/activity-logging') }}">
                    <i class="bi bi-clock-history"></i> Activity Logging
                </a>
            </li>
        </ul>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
