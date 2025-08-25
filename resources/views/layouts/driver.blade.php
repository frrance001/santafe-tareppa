<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(to bottom, #004e92, #000428);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 20px;
            transition: transform 0.3s ease;
            z-index: 1050;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar .welcome {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: #f1f1f1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 20px;
        }

        .sidebar ul li.active a {
            background-color: #0dcaf0;
            color: #000;
            font-weight: bold;
        }

        /* Topbar for Mobile */
        .topbar {
            background-color: #004e92;
            color: white;
            padding: 12px 20px;
            display: none;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .topbar .menu-toggle {
            font-size: 24px;
            cursor: pointer;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 40px 30px;
            transition: margin-left 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .topbar {
                display: flex;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Topbar for mobile --}}
    <div class="topbar d-md-none">
        <div><strong>Driver Dashboard</strong></div>
        <div class="menu-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
        @include('partials.sidebar-driver')
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Toggle Script --}}
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>
