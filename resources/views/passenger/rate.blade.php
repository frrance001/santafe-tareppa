{{-- resources/views/passenger/rate.blade.php --}}
@extends('layouts.passenger')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-orange p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Rate Your Ride</h2>

    <form action="{{ route('passenger.rate.submit', ['ride' => $ride->id]) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700">Rating (1 to 5)</label>
            <select name="rating" id="rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                <option value="">Select rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback</label>
            <textarea name="feedback" id="feedback" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Rating</button>
    </form>
</div>
@endsection
