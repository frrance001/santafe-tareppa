@extends('layouts.driver')

@section('content')
    <div class="container py-4">
        <h2>Accepted Ride Assignments</h2>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-4 p-4 text-red-800 bg-red-100 border border-red-300 rounded-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($rides->isEmpty())
            <p>No accepted rides found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pickup Location</th>
                        <th>Drop-off Location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rides as $ride)
                        @if($ride->status === 'accepted')
                            <tr class="ride-row"
                                data-passenger-name="{{ $ride->user->fullname ?? 'N/A' }}"
                                data-passenger-email="{{ $ride->user->email ?? 'N/A' }}"
                                data-passenger-phone="{{ $ride->phone_number ?? 'N/A' }}">
                                <td>{{ $ride->pickup_location }}</td>
                                <td>{{ $ride->dropoff_location }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Optional Map Modal --}}
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Passenger Info & Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Passenger Name:</strong> <span id="passengerName"></span></p>
                    <p><strong>Passenger Email:</strong> <span id="passengerEmail"></span></p>
                    <p><strong>Passenger Phone:</strong> <span id="passengerPhone"></span></p>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62878.59934401586!2d123.69296307274998!3d11.195128150419343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab2f3b4b0f5e0f%3A0x6a084a0b5b3bfa36!2sBantayan%20Island%2C%20Cebu!5e0!3m2!1sen!2sph!4v1700000000000!5m2!1sen!2sph"
                        width="100%" height="500" class="rounded-lg shadow-md border border-gray-300"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JS for Modal Trigger --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('mapModal'));
            const passengerNameSpan = document.getElementById('passengerName');
            const passengerEmailSpan = document.getElementById('passengerEmail');
            const passengerPhoneSpan = document.getElementById('passengerPhone');

            document.querySelectorAll('.ride-row').forEach(row => {
                row.addEventListener('click', () => {
                    passengerNameSpan.textContent = row.dataset.passengerName;
                    passengerEmailSpan.textContent = row.dataset.passengerEmail;
                    passengerPhoneSpan.textContent = row.dataset.passengerPhone;
                    modal.show();
                });
            });
        });
    </script>
@endsection
