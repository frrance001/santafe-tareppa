<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', ' SANTAFE TAREPPA') }}</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    body {
      background: url('/images/splash-screen.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Inter', sans-serif;
    }

    h1, h2, h3, .font-heading {
      font-family: 'Poppins', sans-serif;
    }

    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 1rem;
      color: #fff;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
    }

    .gradient-btn {
      background: linear-gradient(90deg, #3b82f6, #06b6d4); /* Blue gradient */
      color: #fff;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .gradient-btn:hover {
      background: linear-gradient(90deg, #06b6d4, #3b82f6); /* Reversed blue */
      transform: translateY(-2px);
    }

    .gradient-text {
      background: linear-gradient(90deg, #3b82f6, #06b6d4); /* Blue gradient */
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
  </style>
</head>
<body class="text-gray-800">

  <!-- Navigation -->
  <header class="bg-white/80 backdrop-blur-md shadow fixed w-full z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 rounded-full" alt="Logo">
        <span class="font-heading font-bold text-xl gradient-text">SANTAFE TAREPPA</span>
      </div>
      <nav class="hidden md:flex space-x-6 font-semibold text-gray-700">
        <a href="#home" class="hover:text-[#3b82f6] transition">Home</a>
        <a href="#about" class="hover:text-[#3b82f6] transition">About</a>
        <a href="#services" class="hover:text-[#3b82f6] transition">Services</a>
        <a href="#contact" class="hover:text-[#3b82f6] transition">Contact</a>
      </nav>
      <div class="space-x-2 hidden md:block">
        <a href="{{ route('login') }}" class="px-4 py-2 gradient-btn rounded">Login</a>
        <a href="{{ route('register') }}" class="px-4 py-2 border border-[#3b82f6] text-[#3b82f6] rounded hover:gradient-btn hover:border-transparent">Register</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="home" class="pt-32 pb-24 text-white text-center">
    <div class="container mx-auto px-4">
      <div class="glass py-12 px-6 md:px-12 animate__animated animate__fadeInUp">
        <h1 class="text-4xl md:text-6xl font-heading font-extrabold mb-6 animate__animated animate__fadeIn animate__delay-1s">
          Ride Local. Ride Smart. Ride <span class="gradient-text">SANTAFE TAREPPA</span>.
        </h1>
        <p class="text-lg md:text-xl mb-8 text-blue-100 animate__animated animate__fadeIn animate__delay-2s">
          The smarter way to book tricycles in Santa Fe. Fast. Fair. Reliable.
        </p>
        <a href="#about" class="inline-block px-6 py-3 gradient-btn rounded-full shadow transition animate__animated animate__bounceIn animate__delay-3s">
          Learn More
        </a>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20">
    <div class="container mx-auto px-4 max-w-2xl">
      <div class="glass py-10 px-6 md:px-12 text-center animate__animated animate__fadeIn animate__delay-1s">
        <h2 class="text-3xl md:text-4xl font-heading font-bold mb-4 gradient-text">About TAREPPA</h2>
        <p class="text-lg text-blue-100">TAREPPA is an innovative tricycle booking and fare system designed to help residents and tourists in Santa Fe connect with local drivers, track rides, and pay digitally.</p>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-20">
    <div class="container mx-auto px-4">
      <div class="glass py-10 px-6 md:px-12 text-center animate__animated animate__fadeIn animate__delay-1s">
        <h2 class="text-3xl font-heading font-bold mb-10 gradient-text">Our Services</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white/70 backdrop-blur-lg p-6 rounded shadow hover:shadow-lg transition text-gray-800">
            <h3 class="text-xl font-bold mb-2 gradient-text">Quick Booking</h3>
            <p class="text-gray-600">Book a tricycle with just a few taps from your device.</p>
          </div>
          <div class="bg-white/70 backdrop-blur-lg p-6 rounded shadow hover:shadow-lg transition text-gray-800">
            <h3 class="text-xl font-bold mb-2 gradient-text">Digital Payments</h3>
            <p class="text-gray-600">Cashless convenience with integrated fare transparency.</p>
          </div>
          <div class="bg-white/70 backdrop-blur-lg p-6 rounded shadow hover:shadow-lg transition text-gray-800">
            <h3 class="text-xl font-bold mb-2 gradient-text">Driver Ratings</h3>
            <p class="text-gray-600">Rate your ride to help us improve local transportation services.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-20">
    <div class="container mx-auto px-4 max-w-2xl">
      <div class="glass py-10 px-6 md:px-12 text-center animate__animated animate__fadeIn animate__delay-1s">
        <h2 class="text-3xl font-heading font-bold mb-4 gradient-text">Contact Us</h2>
        <p class="mb-4 text-blue-100">Have questions or feedback? We’re here to help.</p>
        <p>Email: <a href="mailto:support@tareppa.com" class="text-[#3b82f6] hover:underline">support@tareppa.com</a></p>
        <p>Facebook: <a href="https://facebook.com/tareppa" class="text-[#3b82f6] hover:underline">fb.com/tareppa</a></p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gradient-to-r from-[#3b82f6] to-[#06b6d4] text-white py-6 text-center text-sm">
    &copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
  </footer>

</body>
</html>
