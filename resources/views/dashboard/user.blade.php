@extends('layouts.app')

@section('content')
<div class="space-y-10 text-white">

    <!-- Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-yellow-300 mb-2">💪 User Dashboard</h1>
        <p class="text-gray-400">Check your upcoming workouts and schedule</p>
    </div>

    <!-- Welcome Box -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow">
        <h2 class="text-xl font-semibold text-white mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
        @php $trainer = auth()->user()->trainer; @endphp
        @if($trainer)
            <p class="text-sm text-gray-300">Your trainer: <strong>{{ $trainer->name }}</strong> ({{ $trainer->email }})</p>
        @else
            <p class="text-red-400 text-sm">⚠️ You are not assigned to any trainer yet.</p>
        @endif
    </div>

    <!-- Upcoming Workouts -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow">
    <h2 class="text-xl font-semibold text-blue-400 mb-4">📅 Upcoming Workouts</h2>
    @php
        $workouts = \App\Models\Workout::where('client_id', auth()->id())
            ->where('date', '>=', now()->toDateString())
            ->with('category', 'type')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    @endphp

    @if($workouts->isEmpty())
        <p class="text-gray-400 italic">You have no upcoming workouts scheduled.</p>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($workouts as $workout)
                <div class="bg-gray-700 border border-blue-500/50 p-4 rounded-xl shadow hover:bg-gray-600 transition">
                    <div class="text-white font-semibold text-lg">
                        {{ \Carbon\Carbon::parse($workout->date)->format('l, M d') }}
                    </div>
                    <div class="text-sm text-gray-300 mt-1">
                        {{ \Carbon\Carbon::parse($workout->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($workout->end_time)->format('H:i') }}
                        @if($workout->category && $workout->type)
    <div class="text-xs text-green-300 mt-1 italic">
        {{ $workout->category->name }} → {{ $workout->type->name }}
    </div>
@endif

                    </div>
                    @if($workout->description)
    <div class="text-xs text-blue-200 mt-2 italic">
        {{ $workout->description }}
    </div>
@endif
                </div>
            @endforeach
        </div>
    @endif
</div>


    <!-- Calendar Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow">
        <h2 class="text-xl font-semibold text-indigo-300 mb-4">📆 Calendar View</h2>
        <div id="calendar" class="rounded-lg bg-gray-900 p-2"></div>
    </div>

</div>
@endsection

@push('scripts')
<!-- ✅ Dark Mode Calendar Styling -->
<style>
    .fc .fc-col-header-cell-cushion {
        color:rgb(0, 0, 0) !important;
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

<!-- ✅ Tippy.js for Tooltips -->
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
                right: 'timeGridWeek,dayGridMonth'
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
    });
</script>
@endpush
