@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-gray-900 text-white border border-gray-700 p-6 mt-12 rounded-2xl shadow-xl space-y-6">
    <h2 class="text-3xl font-bold text-yellow-400 flex items-center gap-2">
        <i class="fas fa-calendar-plus"></i> Schedule a Workout
    </h2>

    @if(session('success'))
        <div class="bg-green-800 border border-green-500 text-green-300 font-semibold px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-800 border border-red-500 text-red-300 font-semibold px-4 py-2 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $userRole = auth()->user()->role;
        $dashboardRoute = $userRole === 'admin' ? route('dashboard.admin') : route('dashboard.trainer');
    @endphp

    <a href="{{ $dashboardRoute }}"
       class="inline-block bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded shadow transition">
        ← Back to Dashboard
    </a>

    <form method="POST" action="{{ route('workouts.store') }}" id="workoutForm" class="space-y-4">
        @csrf

        <!-- Client Select -->
        <div>
            <label class="block font-semibold mb-1">👤 Client</label>
            <select name="client_id" id="clientSelect" required class="w-full bg-gray-800 text-white border border-gray-600 p-2 rounded shadow">
                <option value="">-- Select Client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                @endforeach
            </select>
        </div>

        <!-- Date -->
        <div>
            <label class="block font-semibold mb-1">📅 Date</label>
            <input type="date" name="date" min="{{ date('Y-m-d') }}"
                   class="w-full bg-gray-800 text-white border border-gray-600 p-2 rounded shadow" required>
        </div>

        <!-- Start Time -->
        <div>
            <label class="block font-semibold mb-1">🕒 Start Time</label>
            <input type="time" name="start_time" class="w-full bg-gray-800 text-white border border-gray-600 p-2 rounded shadow" required>
        </div>

        <!-- Repeat Weekly -->
        <div>
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="repeat" value="1" class="mr-2 text-blue-500 focus:ring-2">
                Repeat this workout weekly for 4 weeks
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" id="submitBtn"
                class="bg-blue-600 hover:bg-blue-700 w-full flex justify-center items-center gap-2 text-white px-4 py-2 rounded shadow font-semibold transition">
            <span id="submitText">📆 Schedule Workout</span>
            <svg id="spinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<!-- ✅ TomSelect for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    new TomSelect('#clientSelect');

    document.getElementById('workoutForm').addEventListener('submit', function () {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        document.getElementById('submitText').textContent = 'Scheduling...';
        document.getElementById('spinner').classList.remove('hidden');
    });
</script>
@endpush
