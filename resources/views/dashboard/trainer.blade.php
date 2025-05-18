@extends('layouts.app')

@section('content')
<div class="space-y-10 text-white">

    <!-- Header Section -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-yellow-300">🏋️‍♂️ Trainer Dashboard</h1>
        <p class="text-gray-400">Manage your clients and workouts efficiently</p>
    </div>

    <!-- Action Button -->
    <div class="flex justify-end">
        <a href="{{ route('workouts.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-5 py-2 rounded shadow transition">
            ➕ Schedule Workout
        </a>
    </div>

    <!-- Clients Section -->
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-yellow-300 flex items-center gap-2">
                <i class="fas fa-users"></i> Your Clients
            </h2>
            <input type="text" id="clientSearch" placeholder="Search clients..." class="p-2 rounded w-48 text-black shadow-md">
        </div>

        @php
            $clients = \App\Models\User::where('trainer_id', auth()->id())->get();
        @endphp

        @if($clients->isEmpty())
            <p class="text-gray-400 italic">You have no assigned clients yet.</p>
        @else
            <ul id="clientList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($clients as $client)
                    <li class="clientItem bg-gray-700 p-4 rounded-xl hover:bg-gray-600 transition shadow-md">
                        <div class="font-semibold text-white">{{ $client->name }}</div>
                        <div class="text-sm text-gray-300">{{ $client->email }}</div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Today's Workouts -->
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold text-blue-400 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-day"></i> Today’s Workouts
        </h2>
        @php
    $workouts = \App\Models\Workout::where('trainer_id', auth()->id())
                ->where('date', now()->toDateString())
                ->with('client', 'category', 'type')
                ->orderBy('start_time')
                ->get();
@endphp



        @if($workouts->isEmpty())
            <p class="text-gray-400 italic">No workouts scheduled for today.</p>
        @else
            <table class="w-full text-sm text-white">
                <thead class="bg-gray-700 text-blue-100">
                    <tr>
                        <th class="py-2 px-4 text-left">Client</th>
                        <th class="py-2 px-4 text-left">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workouts as $workout)
                        <tr class="hover:bg-gray-700 transition">
                            <td class="py-2 px-4">{{ $workout->client->name }}</td>
                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                            @if($workout->details->count())
    <div class="text-xs text-green-300 mt-1 italic">
        @foreach($workout->details as $detail)
            • {{ $detail->category->name }} → {{ $detail->type->name }}<br>
        @endforeach
    </div>
@endif

<br>
    <span class="text-xs text-gray-300 italic">
        {{ $workout->description ?? 'No description' }}
    </span>
</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Calendar View -->
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold text-indigo-300 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-alt"></i> Calendar View
        </h2>
        <div id="calendar" class="rounded-lg bg-gray-900 p-2"></div>
    </div>

</div>
@endsection
@push('scripts')
<!-- ✅ FullCalendar Custom Styling for Dark Mode -->
<style>
    .fc .fc-col-header-cell-cushion {
        color:rgb(12, 12, 12) !important;
        font-weight: 600;
    }
    .fc .fc-daygrid-day-number {
        color: #d1d5db !important;
    }
    .fc .fc-toolbar-title {
        color: #fff !important;
    }
    .fc-event-title {
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>

<!-- ✅ Include Tippy.js -->
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: {
                url: '/calendar-events',
                failure: () => alert('There was an error while fetching events!')
            },
            eventDidMount: function (info) {
                tippy(info.el, {
                    content: `<strong>${info.event.title}</strong><br>${info.event.start.toLocaleString()}`,
                    allowHTML: true,
                    theme: 'light',
                    animation: 'fade',
                    placement: 'top',
                });
            },
            height: 'auto',
        });
        calendar.render();

        // 🔍 Client Live Filter
        const search = document.getElementById('clientSearch');
        const clients = document.querySelectorAll('.clientItem');
        search.addEventListener('input', () => {
            const term = search.value.toLowerCase();
            clients.forEach(client => {
                client.style.display = client.textContent.toLowerCase().includes(term) ? 'block' : 'none';
            });
        });
    });
</script>
@endpush
