<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #fd7e14, #ffb347);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .welcome {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            padding-left: 20px;
        }

        .sidebar ul li.active a {
            background-color: rgba(255, 255, 255, 0.35);
            font-weight: bold;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }

        /* Responsive behavior */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar-admin')

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
