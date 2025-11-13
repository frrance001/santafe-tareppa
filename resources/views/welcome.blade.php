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
    h1,h2,h3,.font-heading { font-family: 'Poppins', sans-serif; }

    /* Animations */
    @keyframes fadeInUp { 0%{opacity:0;transform:translateY(40px);} 100%{opacity:1;transform:translateY(0);} }
    @keyframes rideStraight { 0% { transform: translateX(-150%); } 100% { transform: translateX(150%); } }

    .page-section { display:none; opacity:0; transition:opacity 0.5s; }
    .active-section { display:block; opacity:1; }
    .animate-fadeInUp { animation: fadeInUp 1s ease forwards; }

    /* Loading Screen */
    #loadingScreen {
      position: fixed; inset: 0; background: rgba(255,255,255,0.95);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      z-index: 9999; display: none;
    }

    #loadingScreen img { width: 100px; height: 100px; animation: bounce 1.2s infinite; }
    @keyframes bounce { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-20px);} }
  </style>
</head>

<body class="text-gray-800">

<!-- ✅ Loading Screen -->
<div id="loadingScreen">
  <img src="{{ asset('images/log.png') }}" alt="Loading...">
  <p class="mt-4 text-[#008cff] font-semibold text-lg">Loading, please wait...</p>
</div>

<!-- ✅ Header with Mobile Menu -->
<header class="bg-white shadow fixed w-full z-50">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
      <img src="{{ asset('images/log.png') }}" class="h-10 w-10 rounded-full shadow-lg" alt="Logo">
      <span class="font-heading font-bold text-lg md:text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <!-- Hamburger -->
    <button id="menuToggle" class="md:hidden text-[#008cff] focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
      </svg>
    </button>

    <!-- Navigation -->
    <nav id="navMenu" class="hidden md:flex space-x-6 font-semibold text-gray-700">
      <a href="#" onclick="showSection('home')" class="hover:text-[#008cff] transition">Home</a>
      <a href="#" onclick="showSection('about')" class="hover:text-[#008cff] transition">About</a>
      <a href="#" onclick="showSection('services')" class="hover:text-[#008cff] transition">Services</a>
      <a href="#" onclick="showSection('contact')" class="hover:text-[#008cff] transition">Contact</a>
    </nav>

    <!-- Buttons -->
    <div class="hidden md:flex space-x-2">
      <a href="{{ route('login') }}" id="loginBtn" class="px-4 py-2 bg-[#06b6d4] text-white rounded hover:bg-[#0591a7] transition">Login</a>
      <a href="{{ route('register') }}" id="registerBtn" class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded hover:bg-[#008cff] hover:text-white transition">Become a Driver</a>
    </div>
  </div>

  <!-- ✅ Mobile Dropdown -->
  <div id="mobileMenu" class="md:hidden hidden bg-gray-50 border-t border-gray-200">
    <nav class="flex flex-col items-center py-3 space-y-2 font-medium">
      <a href="#" onclick="showSection('home')" class="hover:text-[#008cff]">Home</a>
      <a href="#" onclick="showSection('about')" class="hover:text-[#008cff]">About</a>
      <a href="#" onclick="showSection('services')" class="hover:text-[#008cff]">Services</a>
      <a href="#" onclick="showSection('contact')" class="hover:text-[#008cff]">Contact</a>
      <a href="{{ route('login') }}" class="mt-2 px-4 py-2 bg-[#06b6d4] text-white rounded">Login</a>
      <a href="{{ route('register') }}" class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded">Register</a>
    </nav>
  </div>
</header>

<!-- ✅ Home Section -->
<section id="home" class="page-section active-section pt-32 pb-20 bg-[#008cff] text-white text-center md:text-left">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between">
    <div class="md:w-1/2 mb-8 md:mb-0">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold mb-6 leading-tight">
        Send packages hassle-free with <span class="font-heading">SANTAFE TAREPPA</span>
      </h1>
      <p class="text-base sm:text-lg md:text-xl mb-8">The smarter way to book tricycles and send deliveries in Santa Fe.</p>
    </div>
    <div class="md:w-1/2 flex justify-center">
      <img src="{{ asset('images/log.png') }}" alt="Rider" class="w-40 md:w-60 animate-fadeInUp">
    </div>
  </div>
</section>

<!-- ✅ About Section -->
<section id="about" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">About TAREPPA</h2>
    <p class="text-gray-600 max-w-2xl mx-auto">TAREPPA is an innovative tricycle booking and fare system designed to help residents and tourists in Santa Fe connect with local drivers, track rides, and pay digitally.</p>
  </div>
</section>

<!-- ✅ Services Section -->
<section id="services" class="page-section py-20 bg-gray-50 text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-10 text-[#008cff]">Our Services</h2>
    <div class="grid gap-6 md:grid-cols-3">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Quick Booking</h3>
        <p class="text-gray-600">Book a tricycle with just a few taps from your device.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Digital Payments</h3>
        <p class="text-gray-600">Cashless convenience with integrated fare transparency.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Driver Ratings</h3>
        <p class="text-gray-600">Rate your ride to help us improve local transportation services.</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ Contact Section -->
<section id="contact" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">Contact Us</h2>
    <p class="text-gray-600 mb-3">Have questions or feedback? We’re here to help.</p>
    <p>Email: <a href="mailto:support@tareppa.com" class="text-[#008cff] hover:underline">support@tareppa.com</a></p>
    <p>Facebook: <a href="https://facebook.com/tareppa" class="text-[#008cff] hover:underline">fb.com/tareppa</a></p>
  </div>
</section>

<!-- ✅ Footer -->
<footer class="bg-[#008cff] text-white py-4 text-center text-sm">
  &copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
</footer>

<!-- ✅ Scripts -->
<script>
  function showSection(id){
    document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active-section'));
    document.getElementById(id).classList.add('active-section');
    window.scrollTo({top:0, behavior:'smooth'});
  }

  // Toggle mobile menu
  const menuToggle = document.getElementById('menuToggle');
  const mobileMenu = document.getElementById('mobileMenu');
  menuToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

  // Loading Screen
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
