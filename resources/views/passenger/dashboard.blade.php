@extends('layouts.passenger')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');

    body {
        background: url('/images/bg-passenger.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
        position: relative;
        font-family: 'Poppins', sans-serif;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(248, 242, 244, 0.75);
        z-index: -1;
    }

    .animated-welcome {
        font-size: 2.2rem;
        font-weight: 700;
        color: orange;
        animation: slideFade 1s ease-out forwards;
        opacity: 0;
        margin-bottom: 30px;
        text-align: center;
    }

    @keyframes slideFade {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .glass-box {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 25px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        margin-bottom: 30px;
    }

    #map {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        background: #f0f8ff;
    }

    .table {
        background-color: rgba(255, 255, 255, 0.9);
        color: #000;
        border-radius: 10px;
        overflow: hidden;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .ride-row:hover {
        cursor: pointer;
        background-color: rgba(255, 235, 59, 0.25);
    }

    .alert {
        background-color: rgba(255, 204, 0, 0.15);
        color: #000;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 15px;
    }

    /* 🎯 Responsive Design Section */
    @media (max-width: 992px) {
        .animated-welcome {
            font-size: 1.8rem;
        }
        .glass-box {
            padding: 20px;
        }
        #map {
            height: 350px;
        }
    }

    @media (max-width: 768px) {
        body {
            background-attachment: scroll;
        }

        .animated-welcome {
            font-size: 1.6rem;
            margin-bottom: 20px;
        }

        .glass-box {
            padding: 15px;
        }

        .table {
            font-size: 13px;
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        #map {
            height: 300px;
        }

        .btn {
            font-size: 13px;
            padding: 6px 10px;
        }
    }

    @media (max-width: 576px) {
        .animated-welcome {
            font-size: 1.4rem;
        }

        .glass-box h3 {
            font-size: 1.1rem;
        }

        .table th,
        .table td {
            padding: 8px;
        }

        #map {
            height: 250px;
        }
    }
</style>
