<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #0d6efd;
            --dark-bg: #f1f5f9;
            --sidebar-width: 250px;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            transition: padding-left 0.3s ease;
        }

        /* Sidebar Overlay for Mobile */
        body.sidebar-open::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
        }

        .topbar {
            background-color: var(--dark-bg);
            color: #212529;
            padding: 12px 20px;
            display: none;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .topbar .menu-toggle {
            font-size: 24px;
            cursor: pointer;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: rgba(33, 37, 41, 0.95);
            backdrop-filter: blur(10px);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            z-index: 1050;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 15px rgba(0,0,0,0.2);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar .welcome {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #adb5bd;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255,255,255,0.1);
            padding-left: 22px;
        }

        .sidebar ul li.active a {
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px 30px;
            transition: margin-left 0.3s ease, padding 0.3s ease;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Typography */
        .main-content h1, .main-content h2, .main-content h3, .main-content h4 {
            font-weight: 700;
            color: #212529;
            margin-top: 24px;
            margin-bottom: 16px;
        }

        .main-content h1 { font-size: 2rem; }
        .main-content h2 { font-size: 1.6rem; }
        .main-content h3 { font-size: 1.3rem; }
        .main-content h4 { font-size: 1.1rem; }

        .main-content p {
            font-size: 15px;
            color: #343a40;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .main-content a {
            color: var(--primary);
            text-decoration: underline;
        }

        .main-content a:hover {
            color: #0b5ed7;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .topbar { display: flex; }

            .sidebar {
                transform: translateX(-100%);
                width: 230px;
            }

            .main-content {
                margin-left: 0;
                padding: 25px 20px;
            }

            .sidebar ul li a { font-size: 14px; }
        }

        @media (max-width: 576px) {
            .main-content h1 { font-size: 1.5rem; }
            .main-content h2 { font-size: 1.3rem; }
            .main-content p { font-size: 14px; }
        }
    </style>
</head>
<body>

    <!-- Topbar for Mobile -->
    <div class="topbar d-md-none">
        <div><strong>Passenger Dashboard</strong></div>
        <div class="menu-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @include('partials.sidebar-passenger')
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    @yield('scripts')

    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
            document.body.classList.toggle('sidebar-open', sidebar.classList.contains('active'));
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(e.target) && !e.target.closest('.menu-toggle')) {
                    sidebar.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                }
            }
        });

        // Adjust main content margin on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main-content');
            if(window.innerWidth > 768) {
                main.style.marginLeft = 'var(--sidebar-width)';
                sidebar.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            } else {
                main.style.marginLeft = '0';
            }
        });
    </script>
</body>
</html>
