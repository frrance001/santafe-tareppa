<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-image: url('/images/hoii.png'); /* ✅ Change to your actual image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center overlay px-4">

    <div class="w-full max-w-lg bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-8 shadow-xl space-y-6 text-white">
        
        <!-- Guide / Description -->
        <div class="bg-emerald-600/30 border border-emerald-400 p-4 rounded-lg shadow-md text-sm">
            <h3 class="text-xl font-semibold text-emerald-300 mb-2">🚖 How to Become a Rider in Santa Fe TAREPPA</h3>
            <ul class="list-disc pl-5 space-y-1 text-white/90">
                <li>Register with your <strong>full name, phone number, email, and profile photo</strong>.</li>
                <li>Provide your <strong>valid location</strong> within Santa Fe and your <strong>age & sex</strong>.</li>
                <li>Choose your role as <strong>Driver</strong> and enter a referral code if available.</li>
                <li>Upload a <strong>clear profile photo</strong> for identification.</li>
                <li>Once approved, you can start accepting ride requests in the TAREPPA system.</li>
            </ul>
            <p class="mt-2 text-white/80 text-xs italic">
                * Only registered and approved drivers can officially operate within Santa Fe TAREPPA.
            </p>
        </div>

        <!-- ✅ Requirements Section -->
        <div class="bg-yellow-500/20 border border-yellow-400 p-4 rounded-lg shadow-md text-sm">
            <h3 class="text-xl font-semibold text-yellow-300 mb-2">📋 Requirements for Applying</h3>
            <ul class="list-disc pl-5 space-y-1 text-white/90">
                <li>Valid government-issued ID (e.g., Driver’s License, Passport, Voter’s ID)</li>
                <li>Proof of Address (Barangay Certificate, Utility Bill, etc.)</li>
                <li>Recent 2x2 ID Photo</li>
                <li>For Drivers: 
                    <ul class="list-disc pl-5 mt-1">
                        <li>Driver’s License (Non-Professional/Professional)</li>
                        <li>Vehicle OR/CR (Official Receipt & Certificate of Registration)</li>
                        <li>Barangay Clearance or Police Clearance</li>
                    </ul>
                </li>
                <li>For Passengers: Valid ID only</li>
            </ul>
            <p class="mt-2 text-white/80 text-xs italic">
                * Please prepare scanned copies or photos of your requirements before submitting.
            </p>
        </div>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-center">User Registration</h2>

        {{-- Show Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Show Success Message --}}
        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Registration Form --}}
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-sm mb-1">Full Name</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}" required
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm mb-1">Phone (11-digit PH number)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                       pattern="^09\d{9}$" maxlength="11" inputmode="numeric"
                       title="Enter 11-digit Philippine mobile number (e.g., 09123456789)"
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Age -->
            <div>
                <label class="block text-sm mb-1">Age</label>
                <input type="number" name="age" value="{{ old('age') }}" required
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Sex -->
            <div>
                <label class="block text-sm mb-1">Sex</label>
                <select name="sex" required
                        class="w-full px-4 py-2 rounded-lg bg-white/20 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 appearance-none">
                    <option value="" class="text-gray-300">Select</option>
                    <option value="Male" class="text-black" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" class="text-black" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" class="text-black" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- City -->
            <div>
                <label class="block text-sm mb-1">City</label>
                <select name="city" required
                        class="w-full px-4 py-2 rounded-lg bg-white/20 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 appearance-none">
                    <option value="" class="text-gray-300">Select City</option>
                    <option value="Cebu City" class="text-black" {{ old('city') == 'Cebu City' ? 'selected' : '' }}>Cebu City</option>
                    <option value="Mandaue" class="text-black" {{ old('city') == 'Mandaue' ? 'selected' : '' }}>Mandaue</option>
                    <option value="Lapu-Lapu" class="text-black" {{ old('city') == 'Lapu-Lapu' ? 'selected' : '' }}>Lapu-Lapu</option>
                    <option value="Santa Fe" class="text-black" {{ old('city') == 'Santa Fe' ? 'selected' : '' }}>Santa Fe</option>
                </select>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Enter specific location"
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Driver Referral -->
            <div>
                <label class="block text-sm mb-1">Driver Referral Code (Optional)</label>
                <input type="text" name="referral" value="{{ old('referral') }}" 
                       placeholder="Enter referral code if any"
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Upload Photo -->
            <div>
                <label class="block text-sm mb-1">Profile Photo</label>
                <input type="file" name="photo" accept="image/*" required
                       class="w-full text-white">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm mb-1">Role</label>
                <select name="role" required
                        class="w-full px-4 py-2 rounded-lg bg-white/20 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 appearance-none">
                    <option value="" class="text-gray-300">Select</option>
                    <option value="Driver" class="text-black" {{ old('role') == 'Driver' ? 'selected' : '' }}>Driver</option>
                    <option value="Passenger" class="text-black" {{ old('role') == 'Passenger' ? 'selected' : '' }}>Passenger</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 transition-all duration-300 rounded-lg text-white font-semibold shadow-md">
                Register
            </button>
        </form>
    </div>

</body>
</html>
