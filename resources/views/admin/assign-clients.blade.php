@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-gray-900 border border-gray-700 text-white p-6 mt-10 rounded-2xl shadow space-y-6" x-data="assignmentForm">
    <h2 class="text-3xl font-bold text-yellow-400">👥 Assign Clients to Trainer</h2>

    @if(session('success'))
        <div class="text-green-400 font-semibold bg-green-800 border border-green-600 p-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('dashboard.admin') }}" class="inline-block bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded shadow transition">
        ← Back to Dashboard
    </a>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.assign.clients.submit') }}" @submit="$refs.loader.classList.remove('hidden')">
        @csrf

        <!-- Trainer Dropdown -->
        <div>
            <label class="block mb-1 font-semibold">👨‍🏫 Select Trainer</label>
            <select name="trainer_id" class="w-full bg-gray-800 border border-gray-600 text-white p-2 rounded shadow"
                    x-model="trainer" @change="fetchAssignedClients($event.target.value)" required>
                <option value="">-- Choose Trainer --</option>
                @foreach($trainers as $trainer)
                    <option value="{{ $trainer->id }}">{{ $trainer->name }} ({{ $trainer->email }})</option>
                @endforeach
            </select>
        </div>

        <!-- Client Search -->
        <div class="mt-4">
            <input type="text" placeholder="🔍 Search clients..." class="w-full bg-gray-800 border border-gray-600 p-2 rounded shadow text-white" x-model="search">
        </div>

        <!-- Client List -->
        <div class="mt-4">
            <label class="block font-semibold mb-2">📋 Select Clients</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-64 overflow-y-auto p-3 bg-gray-800 border border-gray-700 rounded shadow">
                @foreach($clients as $client)
                    <div class="flex items-center space-x-2" x-show="clientMatch('{{ strtolower($client->name) }} {{ strtolower($client->email) }}', search)">
                        <input type="checkbox" name="client_ids[]" value="{{ $client->id }}"
                               class="text-blue-500 focus:ring-2 ring-offset-0 ring-blue-500"
                               :checked="assignedClients.includes({{ $client->id }})">
                        <label class="text-white">{{ $client->name }} <span class="text-gray-400">({{ $client->email }})</span></label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end items-center space-x-4 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                ✅ Assign Clients
            </button>
            <div class="hidden" x-ref="loader">
                <svg class="animate-spin h-5 w-5 text-blue-500" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </div>
        </div>
    </form>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

@push('scripts')
<script>
    function fetchAssignedClients(trainerId) {
        fetch(`/api/assigned-clients/${trainerId}`)
            .then(res => res.json())
            .then(data => {
                document.querySelector('[x-data]').__x.$data.assignedClients = data;
            });
    }

    function clientMatch(client, query) {
        return client.toLowerCase().includes(query.toLowerCase());
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('assignmentForm', () => ({
            trainer: '',
            assignedClients: [],
            search: '',
            fetchAssignedClients,
            clientMatch,
        }));
    });
</script>
@endpush
@endsection
