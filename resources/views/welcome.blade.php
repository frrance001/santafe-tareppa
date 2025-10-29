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

/* Ride + Smoke Animation */
@keyframes rideStraight { 0% { transform: translateX(-150%); } 100% { transform: translateX(150%); } }
.smoke-container { position: relative; width: 100%; height: 200px; margin-top: 2rem; overflow: visible; }
.smoke-container img { position: absolute; bottom: 0; left: 0; width: 180px; animation: rideStraight 5s linear infinite; z-index:2; }
.smoke { position: absolute; bottom: 20px; width: 18px; height: 18px; background: rgba(255,255,255,0.5); border-radius: 50%; filter: blur(4px); opacity:0; animation: smokeMove 2s linear infinite; z-index:1; }
.smoke2 { animation-delay:0.5s; } .smoke3 { animation-delay:1s; }
@keyframes smokeMove { 0% { transform: translate(0,0) scale(0.2); opacity:0.6; } 50% { opacity:0.4; } 100% { transform: translate(-20px,-60px) scale(1); opacity:0; } }

/* Page Sections */
.page-section { display:none; opacity:0; transition: opacity 0.5s; }
.active-section { display:block; opacity:1; }

/* Animation helpers */
.animate-fadeInUp { animation: fadeInUp 1s ease forwards; }
.animate-fadeIn { animation: fadeIn 1s ease forwards; }
.animate-slideInLeft { animation: slideInLeft 1s ease forwards; }
.animate-slideInRight { animation: slideInRight 1s ease forwards; }

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
  width: 100px;
  height: 100px;
  animation: bounce 1.2s infinite;
}
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

/* Responsive fixes */
@media (max-width: 768px) {
  header .container { flex-wrap: wrap; text-align: center; }
  header nav { display: none; }
  header .space-x-2 { display: block; margin-top: 0.5rem; }
  .smoke-container { height: 150px; }
  .smoke-container img { width: 140px; }
  h1 { font-size: 1.8rem !important; }
  p { font-size: 1rem !important; }
}
</style>
</head>

<body class="text-gray-800">

<!-- ✅ Loading Screen -->
<div id="loadingScreen">
  <img src="{{ asset('images/log.png') }}" alt="Loading...">
  <p class="mt-4 text-[#008cff] font-semibold text-lg">Loading, please wait...</p>
</div>

<!-- Navigation -->
<header class="bg-white shadow fixed w-full z-50 animate-fadeIn">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center flex-wrap">
    <div class="flex items-center space-x-2 mx-auto md:mx-0">
      <img src="{{ asset('images/log.png') }}" class="h-12 w-12 rounded-full shadow-lg animate-fadeInUp" alt="Logo">
      <span class="font-heading font-bold text-xl text-[#008cff]">SANTAFE TAREPPA</span>
    </div>

    <nav class="hidden md:flex space-x-6 font-semibold text-gray-700">
      <a href="#" onclick="showSection('home')" class="hover:text-[#008cff] transition">Home</a>
      <a href="#" onclick="showSection('about')" class="hover:text-[#008cff] transition">About</a>
      <a href="#" onclick="showSection('services')" class="hover:text-[#008cff] transition">Services</a>
      <a href="#" onclick="showSection('contact')" class="hover:text-[#008cff] transition">Contact</a>
    </nav>

    <div class="space-x-2 mt-2 md:mt-0 flex justify-center w-full md:w-auto">
      <a href="{{ route('login') }}" id="loginBtn" class="px-4 py-2 bg-[#06b6d4] text-white rounded hover:bg-[#0591a7] transition">Login</a>
      <a href="{{ route('register') }}" id="registerBtn" class="px-4 py-2 border border-[#008cff] text-[#008cff] rounded hover:bg-[#008cff] hover:text-white transition">Become a Driver</a>
    </div>
  </div>
</header>

<!-- Home Section -->
<section id="home" class="page-section active-section pt-32 pb-24 bg-[#008cff] text-white">
  <div class="container mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between text-center md:text-left">
    <div class="md:w-1/2 mb-10 md:mb-0 animate-slideInLeft">
      <h1 class="text-4xl md:text-6xl font-heading font-extrabold mb-6 leading-tight animate-fadeInUp">
        Send packages hassle-free with <span class="font-heading">SANTAFE TAREPPA</span>
      </h1>
      <p class="text-lg md:text-xl mb-8 animate-fadeIn">The smarter way to book tricycles and send deliveries in Santa Fe.</p>
    </div>

    <div class="md:w-1/2 smoke-container mx-auto">
      <img src="{{ asset('images/log.png') }}" alt="Rider Illustration">
      <span class="smoke"></span>
      <span class="smoke smoke2"></span>
      <span class="smoke smoke3"></span>
    </div>
  </div>
</section>

<!-- About Section -->
<section id="about" class="page-section py-20 bg-white">
  <div class="container mx-auto px-4 text-center animate-fadeInUp">
    <h2 class="text-3xl md:text-4xl font-heading font-bold mb-6 text-[#008cff]">About TAREPPA</h2>
    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
      TAREPPA is an innovative tricycle booking and fare system designed to help residents and tourists in Santa Fe connect with local drivers, track rides, and pay digitally.
    </p>
    <div class="smoke-container mt-8 mx-auto">
      <img src="{{ asset('images/log.png') }}" alt="About Illustration">
      <span class="smoke"></span>
      <span class="smoke smoke2"></span>
      <span class="smoke smoke3"></span>
    </div>
  </div>
</section>

<!-- Services Section -->
<section id="services" class="page-section py-20 bg-gray-50">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-3xl font-heading font-bold mb-10 text-[#008cff] animate-fadeInUp">Our Services</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-fadeIn-delay">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Quick Booking</h3>
        <p class="text-gray-600">Book a tricycle with just a few taps from your device.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-fadeIn-delay">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Digital Payments</h3>
        <p class="text-gray-600">Cashless convenience with integrated fare transparency.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 animate-fadeIn-delay">
        <h3 class="text-xl font-bold mb-2 text-[#008cff]">Driver Ratings</h3>
        <p class="text-gray-600">Rate your ride to help us improve local transportation services.</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="page-section py-20 bg-white">
  <div class="container mx-auto px-4 text-center animate-fadeInUp">
    <h2 class="text-3xl font-heading font-bold mb-4 text-[#008cff]">Contact Us</h2>
    <p class="mb-4 text-gray-600">Have questions or feedback? We’re here to help.</p>
    <p>Email: <a href="mailto:support@tareppa.com" class="text-[#008cff] hover:underline">support@tareppa.com</a></p>
    <p>Facebook: <a href="https://facebook.com/tareppa" class="text-[#008cff] hover:underline">fb.com/tareppa</a></p>
  </div>
</section>

<footer class="bg-[#008cff] text-white py-6 text-center text-sm animate-fadeIn">
  &copy; {{ date('Y') }} SANTAFE TAREPPA. All rights reserved.
</footer>

<script>
function showSection(sectionId){
  document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active-section'));
  document.getElementById(sectionId).classList.add('active-section');
}

// ✅ Show loading screen when clicking login/register
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
