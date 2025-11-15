<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'SANTAFE TAREPPA') }}</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
    h1,h2,h3 { font-family: 'Poppins', sans-serif; }

    /* Page Fade-in Animation */
    .page-section {
      display: none;
      opacity: 0;
      transition: opacity 0.5s ease;
    }
    .active-section {
      display: block;
      opacity: 1;
    }

    /* Loading Screen */
    #loadingScreen {
      position: fixed;
      inset: 0;
      background: rgba(255,255,255,0.95);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      display: none;
    }
    #loadingScreen img {
      animation: bounce 1.2s infinite;
      width: 90px;
    }
    @keyframes bounce {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }

    /* Tricycle Animation */
    @keyframes tricycleRun {
      0%   { transform: translateX(-20%) rotate(0deg); }
      50%  { transform: translateX(50%) rotate(2deg); }
      100% { transform: translateX(120%) rotate(0deg); }
    }
    @keyframes bounceSmall {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .animate-tricycle {
      animation: tricycleRun 6s linear infinite, bounceSmall 0.6s ease-in-out infinite;
    }

    /* Mobile Sidebar */
    #mobileMenu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    #mobileMenu.open {
      transform: translateX(0);
    }
  </style>
</head>

<body class="text-gray-800 bg-gray-50">

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
      <img src="{{ asset('images/log.png') }}" class="h-10 w-10 rounded-full shadow-md" alt="Logo">
      <span class="font-bold text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <!-- Mobile Hamburger -->
    <button id="hamburgerBtn" class="md:hidden focus:outline-none">
      <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" 
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <!-- Desktop Nav -->
    <nav class="hidden md:flex items-center space-x-6 font-semibold text-gray-700">
      <a href="#" onclick="showSection('home')" class="hover:text-[#008cff]">Home</a>
      <a href="#" onclick="showSection('about')" class="hover:text-[#008cff]">About</a>
      <a href="#" onclick="showSection('services')" class="hover:text-[#008cff]">Services</a>
      <a href="#" onclick="showSection('contact')" class="hover:text-[#008cff]">Contact</a>
      <a href="{{ route('login') }}" id="loginBtn"
        class="px-4 py-2 bg-[#06b6d4] text-white rounded-lg hover:bg-[#0591a7] transition">Login</a>
      <a href="{{ route('register') }}" id="registerBtn"
        class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded-lg hover:bg-[#008cff] hover:text-white transition">
        Become a Driver
      </a>
    </nav>
  </div>

  <!-- Mobile Slide Menu -->
  <div id="mobileMenu" class="fixed top-0 left-0 w-64 h-full bg-white shadow-xl z-50 p-6 md:hidden">
    <h3 class="text-xl font-bold text-[#008cff] mb-6">Menu</h3>
    <nav class="flex flex-col space-y-4 text-gray-700 font-semibold">
      <a href="#" onclick="closeMenu(); showSection('home')" class="hover:text-[#008cff]">Home</a>
      <a href="#" onclick="closeMenu(); showSection('about')" class="hover:text-[#008cff]">About</a>
      <a href="#" onclick="closeMenu(); showSection('services')" class="hover:text-[#008cff]">Services</a>
      <a href="#" onclick="closeMenu(); showSection('contact')" class="hover:text-[#008cff]">Contact</a>
      <a href="{{ route('login') }}" class="bg-[#06b6d4] text-white py-2 rounded-lg text-center">Login</a>
      <a href="{{ route('register') }}" class="border border-[#008cff] text-[#008cff] py-2 rounded-lg text-center">
        Become a Driver
      </a>
    </nav>
  </div>
</header>

<!-- HOME -->
<section id="home" class="page-section active-section pt-32 pb-20 bg-[#008cff] text-white">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between">

    <div class="md:w-1/2 mb-10 md:mb-0">
      <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
        Send packages faster with <span class="text-yellow-300">TAREPPA</span>
      </h1>
      <p class="text-lg mt-4">Smart, fast, and affordable tricycle booking in Santa Fe.</p>
    </div>

    <div class="md:w-1/2 flex justify-center relative overflow-hidden h-60">
      <img src="{{ asset('images/log.png') }}" class="w-44 md:w-60 animate-tricycle">
    </div>

  </div>
</section>

<!-- ABOUT -->
<section id="about" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6 max-w-2xl">
    <h2 class="text-3xl font-bold text-[#008cff] mb-4">About TAREPPA</h2>
    <p class="text-gray-600">
      TAREPPA helps residents and tourists easily book tricycles, send deliveries, and pay digitally with transparent pricing.
    </p>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="page-section py-20 bg-gray-100 text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-[#008cff] mb-10">Our Services</h2>
    <div class="grid gap-6 md:grid-cols-3">

      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold text-[#008cff] mb-2">Quick Booking</h3>
        <p class="text-gray-600">Book a tricycle with just a few taps.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold text-[#008cff] mb-2">Digital Payments</h3>
        <p class="text-gray-600">Cashless and transparent fare system.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold text-[#008cff] mb-2">Driver Ratings</h3>
        <p class="text-gray-600">Help improve public transportation.</p>
      </div>

    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-[#008cff] mb-4">Contact Us</h2>
    <p class="text-gray-600 mb-3">Have questions? We're here to help.</p>
    <p>Email: <a href="mailto:support@tareppa.com" class="text-[#008cff] underline">support@tareppa.com</a></p>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-[#008cff] text-white py-4 text-center text-sm">
  © {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
</footer>

<script>
/* Section Navigation */
function showSection(id) {
  document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active-section'));
  document.getElementById(id).classList.add('active-section');
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* Mobile Menu */
const mobileMenu = document.getElementById('mobileMenu');
document.getElementById('hamburgerBtn').onclick = () => mobileMenu.classList.toggle('open');
function closeMenu() { mobileMenu.classList.remove('open'); }

/* Loading Screen */
const loadingScreen = document.getElementById('loadingScreen');
function showLoadingThenRedirect(button, url) {
  button.addEventListener('click', function(e) {
    e.preventDefault();
    loadingScreen.style.display = 'flex';
    setTimeout(() => window.location.href = url, 1200);
  });
}
showLoadingThenRedirect(document.getElementById('loginBtn'), "{{ route('login') }}");
showLoadingThenRedirect(document.getElementById('registerBtn'), "{{ route('register') }}");
</script>

</body>
</html>
