<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'SANTAFE TAREPPA') }}</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

  <style>
    body { 
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      scroll-behavior: smooth; 
    }
    h1,h2,h3,.font-heading { font-family: 'Poppins', sans-serif; }

    /* Loading Screen */
    #loadingScreen {
      position: fixed; inset: 0; background: rgba(255,255,255,0.95);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      z-index: 9999; display: none;
    }
    #loadingScreen img { width: 100px; height: 100px; animation: bounce 1.2s infinite; }
    @keyframes bounce { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-20px);} }

    /* Tricycle Animation */
    @keyframes tricycleRun {
      0%   { transform: translateX(-20%) rotate(0deg); }
      25%  { transform: translateX(25%) rotate(2deg); }
      50%  { transform: translateX(50%) rotate(-2deg); }
      75%  { transform: translateX(75%) rotate(1deg); }
      100% { transform: translateX(120%) rotate(0deg); }
    }

    @keyframes bounceSmall {
      0%, 100% { transform: translateY(0); }
      25% { transform: translateY(-4px); }
      50% { transform: translateY(-8px); }
      75% { transform: translateY(-4px); }
    }

    .animate-tricycle {
      display: inline-block;
      position: relative;
      animation: tricycleRun 6s linear infinite, bounceSmall 0.6s ease-in-out infinite;
    }
  </style>
</head>

<body class="text-gray-800">

<!-- Loading Screen -->
<div id="loadingScreen">
  <img src="{{ asset('images/log.png') }}" alt="Loading...">
  <p class="mt-4 text-[#008cff] font-semibold text-lg">Loading, please wait...</p>
</div>

<!-- Header -->
<header class="bg-white shadow fixed w-full z-50">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    
    <!-- Logo -->
    <div class="flex items-center space-x-2">
      <img src="{{ asset('images/log.png') }}" class="h-10 w-10 rounded-full shadow-lg" alt="Logo">
      <span class="font-heading font-bold text-lg md:text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <!-- Hamburger Button (Mobile) -->
    <div class="md:hidden flex items-center">
      <button id="hamburgerBtn" class="focus:outline-none">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" 
        viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" 
            d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>

    <!-- Desktop Navigation -->
    <div id="navMenu" class="hidden md:flex md:items-center md:space-x-6">
      <nav class="flex flex-col md:flex-row md:space-x-6 font-semibold text-gray-700 md:mr-4">
        <a href="#home" class="hover:text-[#008cff] transition py-2 md:py-0">Home</a>
        <a href="#about" class="hover:text-[#008cff] transition py-2 md:py-0">About</a>
        <a href="#services" class="hover:text-[#008cff] transition py-2 md:py-0">Services</a>
        <a href="#contact" class="hover:text-[#008cff] transition py-2 md:py-0">Contact</a>
      </nav>

      <div class="flex flex-col md:flex-row md:space-x-2 mt-2 md:mt-0">
        <a href="{{ route('login') }}" id="loginBtn" 
          class="px-4 py-2 bg-[#06b6d4] text-white rounded hover:bg-[#0591a7] transition mb-2 md:mb-0 text-center">Login</a>

        <a href="{{ route('register') }}" id="registerBtn"
          class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded hover:bg-[#008cff] hover:text-white transition text-center">Become a Driver</a>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="md:hidden hidden bg-white shadow-md w-full">
    <nav class="flex flex-col px-4 py-2 space-y-2 font-semibold text-gray-700">
      <a href="#home" onclick="toggleMobileMenu();" class="hover:text-[#008cff] transition">Home</a>
      <a href="#about" onclick="toggleMobileMenu();" class="hover:text-[#008cff] transition">About</a>
      <a href="#services" onclick="toggleMobileMenu();" class="hover:text-[#008cff] transition">Services</a>
      <a href="#contact" onclick="toggleMobileMenu();" class="hover:text-[#008cff] transition">Contact</a>
      <a href="{{ route('login') }}" onclick="toggleMobileMenu();" class="px-4 py-2 bg-[#06b6d4] text-white rounded text-center">Login</a>
      <a href="{{ route('register') }}" onclick="toggleMobileMenu();" class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded text-center">Become a Driver</a>
    </nav>
  </div>
</header>

<!-- HOME -->
<section id="home" class="pt-32 pb-20 bg-[#008cff] text-white text-center md:text-left">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between">
    
    <div class="md:w-1/2 mb-8 md:mb-0">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold mb-6 leading-tight">
        Send packages hassle-free with <span class="font-heading">SANTAFE TAREPPA</span>
      </h1>
      <p class="text-base sm:text-lg md:text-xl mb-8">
        The smarter way to book tricycles and send deliveries in Santa Fe.
      </p>
    </div>

    <div class="md:w-1/2 flex justify-center relative overflow-hidden h-60">
      <img src="{{ asset('images/log.png') }}" alt="Tricycle Logo" class="w-40 md:w-60 animate-tricycle">
    </div>

  </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-20 bg-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">About TAREPPA</h2>
    <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed">
      TAREPPA is a modern tricycle booking and delivery service created to make transportation in Santa Fe faster, 
      easier, and more reliable. We connect residents, tourists, and businesses with trusted local drivers using a 
      simple and user-friendly digital platform.  
      <br><br>
      Whether you're sending parcels, booking a tricycle ride, or supporting local drivers through digital payments, 
      TAREPPA brings convenience to your daily travel and delivery needs.
    </p>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="py-20 bg-gray-50 text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-10 text-[#008cff]">Our Services</h2>

    <div class="grid gap-6 md:grid-cols-3">

      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Quick Booking</h3>
        <p class="text-gray-600">
          Easily book a tricycle anytime. No more waiting on the road — simply tap, confirm, and ride.
        </p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Digital Payments</h3>
        <p class="text-gray-600">
          Enjoy secure and cashless payments. Fare transparency ensures you always know what you’re paying for.
        </p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Driver Ratings</h3>
        <p class="text-gray-600">
          Help maintain quality service by rating your driver after every trip or delivery.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-20 bg-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">Contact Us</h2>
    <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-4">
      Have questions, suggestions, or want to partner with us?  
      Our team is always ready to help you with concerns about bookings, driver accounts, or system features.
    </p>

    <p>Email: <a href="mailto:support@tareppa.com" class="text-[#008cff] hover:underline">support@tareppa.com</a></p>
    <p>Facebook: <a href="https://facebook.com/tareppa" class="text-[#008cff] hover:underline">fb.com/tareppa</a></p>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-[#008cff] text-white py-4 text-center text-sm">
  &copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
</footer>

<script>
  // Hamburger toggle
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  function toggleMobileMenu() {
    mobileMenu.classList.toggle('hidden');
  }

  hamburgerBtn.addEventListener('click', toggleMobileMenu);

  // Loading Screen for Login/Register
  const loadingScreen = document.getElementById('loadingScreen');
  const loginBtn = document.getElementById('loginBtn');
  const registerBtn = document.getElementById('registerBtn');

  function showLoadingThenRedirect(button, url) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      loadingScreen.style.display = 'flex';
      setTimeout(() => { window.location.href = url; }, 1200);
    });
  }

  if (loginBtn) showLoadingThenRedirect(loginBtn, "{{ route('login') }}");
  if (registerBtn) showLoadingThenRedirect(registerBtn, "{{ route('register') }}");
</script>

</body>
</html>
