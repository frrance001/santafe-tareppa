@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>All Complaints</h3>

    <form class="mb-3" method="get">
        <div class="input-group">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search message / passenger / driver">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Passenger</th>
                <th>Passenger Rating</th>
                <th>Driver</th>
                <th>Message</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($complaints as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ optional($c->passenger)->name ?? '—' }}</td>
                <td>
                    @php
                        $avg = optional($c->passenger)->ratingsReceived()->avg('score') ?? 0;
                        $avgFormatted = number_format($avg, 2);
                        $stars = intval(round($avg)); // simple star view
                    @endphp
                    <span>{{ $avgFormatted }}</span>
                    <span>
                        @for($i=0;$i<$stars;$i++) ★ @endfor
                        @for($i=$stars;$i<5;$i++) ☆ @endfor
                    </span>
                </td>
                <td>{{ optional($c->driver)->name ?? '—' }}</td>
                <td style="max-width:320px;">{{ \Illuminate\Support\Str::limit($c->message, 80) }}</td>
                <td>{{ ucfirst($c->status) }}</td>
                <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.complaints.show', $c) }}" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $complaints->links() }}
</div>
@endsection
