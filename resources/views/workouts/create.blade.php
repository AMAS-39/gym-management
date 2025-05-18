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
        $userRole = auth()->check() ? auth()->user()->role : 'guest';
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

        <!-- Date Picker -->
        <div>
            <label class="block font-semibold mb-1">📅 Select Dates</label>
            <input type="text" id="multiDatePicker" class="w-full bg-gray-800 text-white border border-gray-600 p-2 rounded shadow"
                   placeholder="Select multiple dates" readonly>
        </div>

        <!-- Dynamic Date/Time Fields -->
        <div id="dateTimeContainer" class="space-y-4"></div>

        <!-- Repeat Option -->
        <div>
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="repeat" value="1" class="mr-2 text-blue-500 focus:ring-2">
                Repeat all workouts weekly for 4 weeks
            </label>
        </div>

        <!-- Description -->
        <div>
            <label class="block font-semibold mb-1">📝 Workout Description</label>
            <textarea name="description" rows="4"
                      class="w-full bg-gray-800 text-white border border-gray-600 p-2 rounded shadow"
                      placeholder="e.g. Warm-up, 3x12 squats, 15 min cardio..." required></textarea>
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
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const categories = {!! json_encode($categories->map(function ($cat) {
        return [
            'id' => $cat->id,
            'name' => $cat->name,
            'types' => $cat->types->map(function ($type) {
                return ['id' => $type->id, 'name' => $type->name];
            }),
        ];
    })) !!};

    function updateTypes(categorySelect, workoutIndex, pairIndex) {
    const categoryId = categorySelect.value;
    const typeSelect = document.getElementById(`type-select-${workoutIndex}${pairIndex}`);
    typeSelect.innerHTML = '<option value="">-- Select Type --</option>';
    const selectedCategory = categories.find(cat => cat.id == categoryId);
    if (selectedCategory) {
        selectedCategory.types.forEach(type => {
            const opt = document.createElement('option');
            opt.value = type.id;
            opt.textContent = type.name;
            typeSelect.appendChild(opt);
        });
    }
}


    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#clientSelect');

        const container = document.getElementById('dateTimeContainer');
        const picker = flatpickr("#multiDatePicker", {
            mode: "multiple",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                container.innerHTML = ''; // clear old
                selectedDates.forEach((date, index) => {
                    const formatted = instance.formatDate(date, "Y-m-d");
                    const html = `
    <div class="category-type-group space-y-2">
        <input type="hidden" name="dates[]" value="${formatted}">
        <div>
            <label class="block text-sm text-gray-300 font-medium mb-1">🕒 Time</label>
            <input type="time" name="times[]" required
                class="w-full bg-gray-900 text-white border border-gray-700 p-2 rounded shadow">
        </div>

        <div class="flex gap-2">
            <select name="category_ids[${index}][]" onchange="updateTypes(this, ${index}, ${index}0)"
                    class="category-dropdown w-1/2 bg-gray-900 text-white border border-gray-700 p-2 rounded shadow">
                <option value="">-- Select Category --</option>
                ${categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('')}
            </select>

            <select name="type_ids[${index}][]" id="type-select-${index}0"
                    class="type-dropdown w-1/2 bg-gray-900 text-white border border-gray-700 p-2 rounded shadow">
                <option value="">-- Select Type --</option>
            </select>
        </div>

        <button type="button" onclick="addAnotherPair(${index})"
                class="text-sm text-blue-300 hover:text-blue-500">➕ Add Another Category/Type</button>
    </div>
`;

                    container.insertAdjacentHTML('beforeend', html);
                });
            }
        });

        const form = document.getElementById('workoutForm');
        form.addEventListener('submit', function () {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitText').textContent = 'Scheduling...';
            document.getElementById('spinner').classList.remove('hidden');
        });
    });


    function addAnotherPair(index) {
    const group = document.querySelectorAll('.category-type-group')[index];
    const count = group.querySelectorAll('.category-dropdown').length;
    const newHtml = `
    <div class="flex gap-2 mt-2">
        <select name="category_ids[${index}][]" onchange="updateTypes(this, ${index}, ${count})"
                class="category-dropdown w-1/2 bg-gray-900 text-white border border-gray-700 p-2 rounded shadow">
            <option value="">-- Select Category --</option>
            ${categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('')}
        </select>

        <select name="type_ids[${index}][]" id="type-select-${index}${count}"
                class="type-dropdown w-1/2 bg-gray-900 text-white border border-gray-700 p-2 rounded shadow">
            <option value="">-- Select Type --</option>
        </select>
    </div>`;
    group.insertAdjacentHTML('beforeend', newHtml);
}

</script>

@endpush
