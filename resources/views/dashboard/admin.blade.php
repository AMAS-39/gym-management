@extends('layouts.app')

@section('content')
<div class="space-y-10 text-white">

    <!-- 👑 Admin Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-yellow-300">👑 Admin Dashboard</h1>
        <p class="text-gray-400">Monitor users, trainers, and client activities</p>
    </div>

    <!-- 🔢 Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow">
            <h3 class="text-sm font-semibold text-gray-300">Total Users</h3>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow">
            <h3 class="text-sm font-semibold text-gray-300">Trainers</h3>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ \App\Models\User::where('role', 'trainer')->count() }}</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow">
            <h3 class="text-sm font-semibold text-gray-300">Clients</h3>
            <p class="text-3xl font-bold text-purple-400 mt-2">{{ \App\Models\User::where('role', 'user')->count() }}</p>
        </div>
    </div>

    <!-- 🔗 Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
    <a href="{{ route('users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-xl text-center font-semibold transition">
        👥 Manage Users
    </a>
    <a href="{{ route('admin.assign.clients') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl text-center font-semibold transition">
        🧑‍🤝‍🧑 Assign Clients
    </a>
    <a href="{{ route('workouts.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-xl text-center font-semibold transition">
        🗓️ Schedule Workout
    </a>
    <!-- 🧠 New Workout Library Button -->
    <a href="{{ route('admin.workout.library') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white p-4 rounded-xl text-center font-semibold transition">
        🧠 Workout Library
    </a>
</div>


    <!-- 📅 Today's Workouts -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow">
        <h2 class="text-2xl font-bold text-blue-300 mb-4">📅 Today's Workouts</h2>
        <table class="w-full text-sm text-white">
            <thead class="text-blue-200 border-b border-gray-600">
                <tr>
                    <th class="py-2 text-left">Client</th>
                    <th class="py-2 text-left">Trainer</th>
                    <th class="py-2 text-left">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Workout::where('date', now()->toDateString())->get() as $workout)
                    <tr class="border-b border-gray-700 hover:bg-gray-600">
                        <td class="py-2">{{ $workout->client->name }}</td>
                        <td class="py-2">{{ $workout->trainer->name }}</td>
                        <td class="py-2">
                            {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                            <br>
    <span class="text-xs text-gray-300 italic">
        {{ $workout->description ?? 'No description' }}
    </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 📆 Calendar -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow">
        <h2 class="text-2xl font-bold text-indigo-300 mb-4">📆 Calendar View</h2>
        <div id="calendar" class="rounded-xl overflow-hidden bg-gray-900 p-4"></div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .fc .fc-col-header-cell-cushion {
        color:rgb(0, 0, 0) !important; /* or try #f9fafb for soft white */
        font-weight: 600;
        font-size: 0.9rem;
    }

    .fc .fc-daygrid-day-number {
        color: #d1d5db !important; /* lighter gray for date numbers */
    }

    .fc .fc-toolbar-title {
        color: #ffffff; /* Month Title (e.g., May 2025) */
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'standard',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '/calendar-events',
            height: 'auto',
            eventDidMount: function(info) {
                const tooltip = document.createElement('div');
                tooltip.className = 'fc-event-tooltip';
                tooltip.innerHTML = `<strong>${info.event.title}</strong><br>${info.event.start.toLocaleString()}`;

                info.el.addEventListener('mouseenter', () => {
                    document.body.appendChild(tooltip);
                    tooltip.style.top = (info.jsEvent.pageY + 10) + 'px';
                    tooltip.style.left = (info.jsEvent.pageX + 10) + 'px';
                });

                info.el.addEventListener('mousemove', (e) => {
                    tooltip.style.top = (e.pageY + 10) + 'px';
                    tooltip.style.left = (e.pageX + 10) + 'px';
                });

                info.el.addEventListener('mouseleave', () => {
                    tooltip.remove();
                });
            }
        });
        calendar.render();
    });
</script>
@endpush
