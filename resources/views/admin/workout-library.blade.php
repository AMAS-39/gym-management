@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto text-white space-y-10">

    <!-- 🔙 Back Button -->
    <a href="{{ route('dashboard.admin') }}"
       class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded shadow transition">
        ← Back to Admin Dashboard
    </a>

    <h2 class="text-3xl font-bold text-yellow-300">🧠 Workout Library</h2>

    <!-- ➕ Add Category -->
    <form method="POST" action="{{ route('admin.add.category') }}" class="space-y-3">
        @csrf
        <label class="block font-semibold">➕ Add New Category</label>
        <input type="text" name="name" class="w-full bg-gray-800 border border-gray-600 p-2 rounded" required placeholder="e.g. Chest, Legs">
        <button type="submit" class="bg-blue-600 px-4 py-2 mt-2 rounded">Add Category</button>
    </form>

    <!-- 🧩 Add Type -->
    <form method="POST" action="{{ route('admin.add.type') }}" class="space-y-3">
        @csrf
        <label class="block font-semibold">🧩 Add Workout Type to Category</label>
        <select name="category_id" required class="w-full bg-gray-800 border border-gray-600 p-2 rounded">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="name" class="w-full bg-gray-800 border border-gray-600 p-2 rounded" placeholder="e.g. Bench Press" required>
        <button type="submit" class="bg-green-600 px-4 py-2 mt-2 rounded">Add Workout Type</button>
    </form>

    <!-- 📚 List -->
    <div class="space-y-6 mt-10">
        <h3 class="text-xl font-bold text-blue-300">📚 Current Library</h3>
        @foreach($categories as $cat)
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-600 shadow space-y-2">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-yellow-400">{{ $cat->name }}</h4>
                    <div class="space-x-2">
                        <button onclick="openModal('editCategoryModal{{ $cat->id }}')" class="bg-yellow-400 text-black px-3 py-1 rounded">Edit</button>
                        <button onclick="openModal('deleteCategoryModal{{ $cat->id }}')" class="bg-red-500 px-3 py-1 rounded">Delete</button>
                    </div>
                </div>

                <!-- Types -->
                <ul class="ml-4 list-disc text-sm text-gray-300 space-y-2">
                    @forelse($cat->types as $type)
                        <li class="flex justify-between items-center">
                            <span>{{ $type->name }}</span>
                            <div class="space-x-2">
                                <button onclick="openModal('editTypeModal{{ $type->id }}')" class="bg-yellow-300 text-black px-2 py-1 text-sm rounded">Edit</button>
                                <button onclick="openModal('deleteTypeModal{{ $type->id }}')" class="bg-red-400 px-2 py-1 text-sm rounded">Delete</button>
                            </div>
                        </li>

                        <!-- Edit Type Modal -->
                        <x-modal id="editTypeModal{{ $type->id }}">
                            <form method="POST" action="{{ route('admin.type.update', $type->id) }}">
                                @csrf @method('PUT')
                                <label class="block mb-2 font-semibold">Update Workout Type</label>
                                <input type="text" name="name" value="{{ $type->name }}" class="w-full bg-gray-800 p-2 border border-gray-600 rounded mb-3">
                                <div class="flex justify-end gap-2">
                                    <button type="submit" class="bg-yellow-300 text-black px-3 py-1 rounded">Update</button>
                                    <button type="button" onclick="closeModal('editTypeModal{{ $type->id }}')" class="bg-gray-600 px-3 py-1 rounded">Cancel</button>
                                </div>
                            </form>
                        </x-modal>

                        <!-- Delete Type Modal -->
                        <x-modal id="deleteTypeModal{{ $type->id }}">
                            <form method="POST" action="{{ route('admin.type.delete', $type->id) }}">
                                @csrf @method('DELETE')
                                <p class="text-red-400 font-semibold mb-4">Are you sure you want to delete this workout type?</p>
                                <div class="flex justify-end gap-2">
                                    <button type="submit" class="bg-red-500 px-3 py-1 rounded">Delete</button>
                                    <button type="button" onclick="closeModal('deleteTypeModal{{ $type->id }}')" class="bg-gray-600 px-3 py-1 rounded">Cancel</button>
                                </div>
                            </form>
                        </x-modal>
                    @empty
                        <li class="italic text-gray-500">No types yet.</li>
                    @endforelse
                </ul>

                <!-- Edit Category Modal -->
                <x-modal id="editCategoryModal{{ $cat->id }}">
                    <form method="POST" action="{{ route('admin.category.update', $cat->id) }}">
                        @csrf @method('PUT')
                        <label class="block mb-2 font-semibold">Update Category</label>
                        <input type="text" name="name" value="{{ $cat->name }}" class="w-full bg-gray-800 p-2 border border-gray-600 rounded mb-3">
                        <div class="flex justify-end gap-2">
                            <button type="submit" class="bg-yellow-400 text-black px-3 py-1 rounded">Update</button>
                            <button type="button" onclick="closeModal('editCategoryModal{{ $cat->id }}')" class="bg-gray-600 px-3 py-1 rounded">Cancel</button>
                        </div>
                    </form>
                </x-modal>

                <!-- Delete Category Modal -->
                <x-modal id="deleteCategoryModal{{ $cat->id }}">
                    <form method="POST" action="{{ route('admin.category.delete', $cat->id) }}">
                        @csrf @method('DELETE')
                        <p class="text-red-400 font-semibold mb-4">Are you sure you want to delete this category?</p>
                        <div class="flex justify-end gap-2">
                            <button type="submit" class="bg-red-600 px-3 py-1 rounded">Delete</button>
                            <button type="button" onclick="closeModal('deleteCategoryModal{{ $cat->id }}')" class="bg-gray-600 px-3 py-1 rounded">Cancel</button>
                        </div>
                    </form>
                </x-modal>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush
