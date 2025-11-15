<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SANTAFE TAREPPA</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      scroll-behavior: smooth;
    }
    h1,h2,h3,.font-heading { font-family: 'Poppins', sans-serif; }

    /* Logo Running Animation */
    @keyframes tricycleRun {
      0%   { transform: translateX(-20%) rotate(0deg); }
      25%  { transform: translateX(25%) rotate(1deg); }
      50%  { transform: translateX(50%) rotate(-1deg); }
      75%  { transform: translateX(75%) rotate(1deg); }
      100% { transform: translateX(120%) rotate(0deg); }
    }

    @keyframes bounceSmall {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    .animate-tricycle {
      animation: tricycleRun 5s linear infinite, bounceSmall 0.8s ease-in-out infinite;
    }

    /* Long Description Fade Animation */
    @keyframes fadeSlide {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    .long-desc-animate {
      animation: fadeSlide 1.4s ease-out forwards;
      opacity: 0;
    }
  </style>
</head>

<body class="text-gray-800">

<!-- HEADER (same as before) -->
<header class="bg-white shadow fixed w-full z-50">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">

    <div class="flex items-center space-x-2">
      <img src="{{ asset('images/log.png') }}" class="h-10 w-10 rounded-full shadow-lg animate-tricycle" alt="Logo">
      <span class="font-heading font-bold text-lg md:text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <div class="md:hidden flex items-center">
      <button id="hamburgerBtn" class="focus:outline-none">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>

    <div id="navMenu" class="hidden md:flex md:items-center md:space-x-6">
      <nav class="flex flex-col md:flex-row md:space-x-6 font-semibold text-gray-700 md:mr-4">
        <a href="#home" class="hover:text-[#008cff] transition py-2 md:py-0">Home</a>
        <a href="#about" class="hover:text-[#008cff] transition py-2 md:py-0">About</a>
        <a href="#services" class="hover:text-[#008cff] transition py-2 md:py-0">Services</a>
        <a href="#contact" class="hover:text-[#008cff] transition py-2 md:py-0">Contact</a>
      </nav>
    </div>

  </div>

  <div id="mobileMenu" class="md:hidden hidden bg-white shadow-md w-full">
    <nav class="flex flex-col px-4 py-2 space-y-2 font-semibold text-gray-700">
      <a href="#home" onclick="toggleMobileMenu();">Home</a>
      <a href="#about" onclick="toggleMobileMenu();">About</a>
      <a href="#services" onclick="toggleMobileMenu();">Services</a>
      <a href="#contact" onclick="toggleMobileMenu();">Contact</a>
    </nav>
  </div>
</header>

<!-- HOME -->
<section id="home" class="pt-32 pb-20 bg-[#008cff] text-white text-center md:text-left">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between">

    <div class="md:w-1/2 mb-8 md:mb-0 long-desc-animate">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold mb-6 leading-tight">
        Send packages hassle-free with <span class="font-heading">SANTAFE TAREPPA</span>
      </h1>
      <p class="text-base sm:text-lg md:text-xl mb-8">
        The smarter, faster, and more reliable tricycle booking and delivery service designed for Santa Fe.
        Experience the convenience of digital booking, trusted drivers, and seamless parcel delivery.
      </p>
    </div>

    <div class="md:w-1/2 flex justify-center relative overflow-hidden h-60">
      <img src="{{ asset('images/log.png') }}" alt="Tricycle Logo" class="w-40 md:w-60 animate-tricycle">
    </div>

  </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-20 bg-white text-center long-desc-animate">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">About TAREPPA</h2>
    <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed">
      TAREPPA is built to bring modern convenience to local tricycle services—making them accessible with a simple tap.
      Whether you're sending parcels or booking a ride, trust that TAREPPA connects you with reliable and community-trusted drivers.
    </p>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="py-20 bg-gray-50 text-center long-desc-animate">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-10 text-[#008cff]">Our Services</h2>

    <div class="grid gap-6 md:grid-cols-3">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Quick Booking</h3>
        <p class="text-gray-600">Book tricycles instantly—no waiting, no hassle.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Digital Payments</h3>
        <p class="text-gray-600">Convenient cashless transactions with full fare transparency.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Driver Ratings</h3>
        <p class="text-gray-600">Rate drivers to keep service quality high for everyone.</p>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-20 bg-white text-center long-desc-animate">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-4 text-[#008cff]">Contact Us</h2>
    <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-4">
      Have questions or want to work with us? We're here to help.
    </p>

    <p>Email: support@tareppa.com</p>
    <p>Facebook: fb.com/tareppa</p>
  </div>
</section>

<!-- CREDITS -->
<section class="py-10 bg-gray-100 text-center long-desc-animate">
  <h3 class="text-xl font-heading font-bold text-[#008cff]">Developers & Team</h3>
  <p class="text-gray-700 mt-2">France Fernandez – Programmer</p>
  <p class="text-gray-700">Dave – Designer</p>
  <p class="text-gray-700">Jennifer Escalan & KC Joy veliganilao– Researchers</p>
</section>

<!-- FOOTER -->
<footer class="bg-[#008cff] text-white py-4 text-center text-sm">
  &copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
</footer>

<script>
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  function toggleMobileMenu() { mobileMenu.classList.toggle('hidden'); }
  hamburgerBtn.addEventListener('click', toggleMobileMenu);
</script>

</body>
</html>
