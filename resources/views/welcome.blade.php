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
@keyframes fadeIn { 0%{opacity:0;} 100%{opacity:1;} }
@keyframes slideInLeft { 0%{opacity:0;transform:translateX(-50px);} 100%{opacity:1;transform:translateX(0);} }
@keyframes slideInRight { 0%{opacity:0;transform:translateX(50px);} 100%{opacity:1;transform:translateX(0);} }
@keyframes rideStraight { 0% { transform: translateX(-150%); } 100% { transform: translateX(150%); } }
@keyframes smokeMove { 0% { transform: translate(0,0) scale(0.2); opacity:0.6; }
  50% { opacity:0.4; }
  100% { transform: translate(-20px,-60px) scale(1); opacity:0; }
}

.smoke-container { position: relative; width: 100%; height: 220px; margin-top: 2rem; overflow: visible; }
.smoke-container img { position: absolute; bottom: 0; left: 0; width: 200px; animation: rideStraight 5s linear infinite; z-index:2; }
.smoke { position: absolute; bottom: 20px; width: 18px; height: 18px; background: rgba(255,255,255,0.5); border-radius: 50%; filter: blur(4px); opacity:0; animation: smokeMove 2s linear infinite; z-index:1; }
.smoke2 { animation-delay:0.5s; } .smoke3 { animation-delay:1s; }

.page-section { display:none; opacity:0; transition: opacity 0.5s; }
.active-section { display:block; opacity:1; }

.animate-fadeInUp { animation: fadeInUp 1s ease forwards; }
.animate-fadeIn { animation: fadeIn 1s ease forwards; }
.animate-slideInLeft { animation: slideInLeft 1s ease forwards; }
.animate-slideInRight { animation: slideInRight 1s ease forwards; }
</style>
</head>

<body class="text-gray-800 bg-white">

<!-- HEADER -->
<header class="bg-white shadow fixed w-full z-50 animate-fadeIn">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-2">
      <img src="{{ asset('images/log.png') }}" class="h-10 w-10 rounded-full shadow-lg animate-fadeInUp" alt="Logo">
      <span class="font-heading font-bold text-lg md:text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <!-- Mobile Menu Button -->
    <button id="menu-btn" class="md:hidden text-[#008cff] text-2xl focus:outline-none">
      &#9776;
    </button>

    <!-- Desktop Nav -->
    <nav id="nav-menu" class="hidden md:flex space-x-6 font-semibold text-gray-700">
      <a href="#" onclick="showSection('home')" class="hover:text-[#008cff] transition">Home</a>
      <a href="#" onclick="showSection('about')" class="hover:text-[#008cff] transition">About</a>
      <a href="#" onclick="showSection('services')" class="hover:text-[#008cff] transition">Services</a>
      <a href="#" onclick="showSection('contact')" class="hover:text-[#008cff] transition">Contact</a>
    </nav>

    <div class="space-x-2 hidden md:block">
      <a href="{{ route('login') }}" class="px-4 py-2 bg-[#06b6d4] text-white rounded-lg hover:bg-[#0591a7] transition">Login</a>
      <a href="{{ route('register') }}" class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded-lg hover:bg-[#008cff] hover:text-white transition">Become a Driver</a>
    </div>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t text-center py-4 space-y-3">
    <a href="#" onclick="showSection('home')" class="block text-gray-700 font-semibold hover:text-[#008cff]">Home</a>
    <a href="#" onclick="showSection('about')" class="block text-gray-700 font-semibold hover:text-[#008cff]">About</a>
    <a href="#" onclick="showSection('services')" class="block text-gray-700 font-semibold hover:text-[#008cff]">Services</a>
    <a href="#" onclick="showSection('contact')" class="block text-gray-700 font-semibold hover:text-[#008cff]">Contact</a>
    <div class="pt-2">
      <a href="{{ route('login') }}" class="block mx-6 my-2 bg-[#06b6d4] text-white py-2 rounded-lg hover:bg-[#0591a7]">Login</a>
      <a href="{{ route('register') }}" class="block mx-6 border border-[#008cff] text-[#008cff] py-2 rounded-lg hover:bg-[#008cff] hover:text-white">Become a Driver</a>
    </div>
  </div>
</header>

<!-- HOME -->
<section id="home" class="page-section active-section pt-28 pb-20 bg-[#008cff] text-white">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-center md:text-left">
    <div class="md:w-1/2 mb-10 animate-slideInLeft">
      <h1 class="text-4xl md:text-5xl font-heading font-extrabold mb-4 leading-tight">Send packages hassle-free with <span class="font-heading">SANTAFE TAREPPA</span></h1>
      <p class="text-lg md:text-xl mb-6">The smarter way to book tricycles and send deliveries in Santa Fe.</p>
      <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-white text-[#008cff] rounded-lg font-semibold hover:bg-gray-100 transition">Get Started</a>
    </div>
    <div class="md:w-1/2 smoke-container flex justify-center animate-slideInRight">
      <img src="{{ asset('images/log.png') }}" alt="Rider Illustration" class="w-48 md:w-64">
      <span class="smoke"></span>
      <span class="smoke smoke2"></span>
      <span class="smoke smoke3"></span>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6 animate-fadeInUp">
    <h2 class="text-3xl md:text-4xl font-heading font-bold mb-6 text-[#008cff]">About TAREPPA</h2>
    <p class="text-gray-600 max-w-2xl mx-auto mb-6">TAREPPA is an innovative tricycle booking and fare system designed to help residents and tourists in Santa Fe connect with local drivers, track rides in real-time, and pay digitally.  
      We aim to modernize local transportation by blending technology and convenience for everyone.</p>

    <div class="mt-6">
      <h3 class="font-heading text-xl font-bold text-[#008cff] mb-2">Meet the Team</h3>
      <p class="text-gray-700"> <span class="font-semibold">Programmer:</span> France Fernandez</p>
      <p class="text-gray-700"> <span class="font-semibold">Designer:</span> Dave Jangzon</p>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="page-section py-20 bg-gray-50 text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl md:text-4xl font-heading font-bold mb-10 text-[#008cff] animate-fadeInUp">Our Services</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-slideInLeft">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Quick Booking</h3>
        <p class="text-gray-600">Book a tricycle with just a few taps from your device.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-fadeInUp">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Digital Payments</h3>
        <p class="text-gray-600">Enjoy cashless convenience with integrated fare transparency and GCash options.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-slideInRight">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Driver Ratings</h3>
        <p class="text-gray-600">Rate your ride to help us improve local transportation services.</p>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="page-section py-20 bg-white text-center">
  <div class="container mx-auto px-6 animate-fadeInUp">
    <h2 class="text-3xl font-heading font-bold mb-4 text-[#008cff]">Contact Us</h2>
    <p class="text-gray-600 mb-4">Have questions or feedback? We’re here to help and ready to assist you.</p>
    <p>Email: <a href="mailto:support@tareppa.com" class="text-[#008cff] hover:underline">support@tareppa.com</a></p>
    <p>Facebook: <a href="https://facebook.com/tareppa" class="text-[#008cff] hover:underline">fb.com/tareppa</a></p>
  </div>
</section>

<footer class="bg-[#008cff] text-white py-6 text-center text-sm">&copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.</footer>

<script>
function showSection(sectionId){
  document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active-section'));
  document.getElementById(sectionId).classList.add('active-section');
  document.getElementById('mobile-menu').classList.add('hidden');
}

// Mobile menu toggle
document.getElementById('menu-btn').addEventListener('click', () => {
  const menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden');
});
</script>
</body>
</html>
