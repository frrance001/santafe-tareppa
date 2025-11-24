@extends('layouts.admin')

@section('content')
<style>
body {
    margin: 0;
    padding: 0;
    background: #f0f4f8;
    color: #1f2937;
    font-family: 'Inter', sans-serif;
}

.glass-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

h3 {
    background: linear-gradient(90deg, #38bdf8, #0ea5e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
}

.filter-container {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-input {
    flex: 1;
}

.user-entry {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    padding: 20px;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}

.user-entry:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.user-photo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 12px;
    margin-right: 15px;
    float: left;
}

.user-info { font-size: 0.9rem; overflow: hidden; }
.user-role { font-weight: 600; color: #0ea5e9; }
.status-approved { color: #10b981; font-weight: 600; }
.status-pending { color: #f59e0b; font-weight: 600; }
.status-disapproved { color: #ef4444; font-weight: 600; }

.action-buttons { margin-top: 10px; clear: both; }
</style>

<div class="container mt-5 glass-container">
    <h3>Users List</h3>

    <!-- Filters -->
    <div class="filter-container">
        <!-- SEARCH -->
        <input type="text" id="searchInput" class="form-control search-input" placeholder="Search users...">

        <!-- 🔽 ADDED DROPDOWN FILTER -->
        <select id="statusDropdown" class="form-select" style="max-width:200px; border-radius:8px;">
            <option value="all">All Users</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="disapproved">Disapproved</option>
        </select>
    </div>

    <div id="usersContainer"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    const users = [
        @foreach ($users as $role => $roleUsers)
            @foreach ($roleUsers as $user)
                {!! json_encode($user) !!},
            @endforeach
        @endforeach
    ];

    let currentStatus = 'all';

    // Main render function
    function renderUsers() {
        $('#usersContainer').empty();

        const search = $('#searchInput').val().toLowerCase();

        let filtered = users.filter(u => u.fullname.toLowerCase().includes(search));

        if (currentStatus !== 'all') {
            filtered = filtered.filter(u => u.status === currentStatus);
        }

        if (filtered.length === 0) {
            $('#usersContainer').append('<p>No users found.</p>');
            return;
        }

        filtered.forEach(user => appendUser(user));
    }

    // Creates a card for each user
    function appendUser(user) {
        const statusTime = user.status_updated_at
            ? ` (${new Date(user.status_updated_at).toLocaleString()})`
            : '';

        const showButtons = user.status === 'pending' ? '' : 'style="display:none;"';

        const html = `
            <div class="user-entry">
                <img src="${user.photo ?? '/images/no-image.png'}" class="user-photo" alt="User Photo">

                <div class="user-info">
                    <p><strong>${user.fullname}</strong></p>
                    <p>Email: ${user.email}</p>
                    <p>Phone: ${user.phone}</p>
                    <p>City: ${user.city}</p>

                    <p class="user-role">
                        ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                    </p>

                    <p class="status-${user.status}">
                        ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                        ${statusTime}
                    </p>

                    <div class="action-buttons" ${showButtons}>
                        <form action="/admin/users/${user.id}/approve" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>

                        <form action="/admin/users/${user.id}/disapprove" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Disapprove</button>
                        </form>
                    </div>
                </div>
            </div>
        `;

        $('#usersContainer').append(html);
    }

    // Search typing
    $('#searchInput').on('keyup', function() {
        renderUsers();
    });

    // 🔽 DROPDOWN FILTER LOGIC
    $('#statusDropdown').on('change', function() {
        currentStatus = $(this).val();
        renderUsers();
    });

    renderUsers();
});
</script>

@endsection
