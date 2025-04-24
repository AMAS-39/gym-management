@extends('layouts.app')

@section('content')
<div class="space-y-10 text-white">

    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-yellow-300">👥 Manage Users</h1>
        <button onclick="openModal('addUserModal')" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold transition">
            ➕ Add User
        </button>
    </div>

    <!-- Back Button -->
    <a href="{{ route('dashboard.admin') }}"
       class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
        ← Back to Dashboard
    </a>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-white bg-gray-800 rounded-xl shadow border border-gray-700">
            <thead class="bg-gray-700 text-blue-200">
                <tr>
                    <th class="py-2 px-4 text-left">#</th>
                    <th class="py-2 px-4 text-left">Name</th>
                    <th class="py-2 px-4 text-left">Email</th>
                    <th class="py-2 px-4 text-left">Role</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr class="border-t border-gray-700 hover:bg-gray-700/50">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $user->name }}</td>
                        <td class="py-2 px-4">{{ $user->email }}</td>
                        <td class="py-2 px-4 capitalize">{{ $user->role }}</td>
                        <td class="py-2 px-4 space-x-2">
                            <button onclick="openEditModal({{ $user }})" class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm">Edit</button>
                            <button onclick="openDeleteModal({{ $user->id }})" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ Modal Components -->
    @include('components.modals.create-modal')
@include('components.modals.edit-modal')
@include('components.modals.delete-modal')

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

    function openEditModal(user) {
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editUserName').value = user.name;
        document.getElementById('editUserEmail').value = user.email;
        document.getElementById('editUserRole').value = user.role;
        document.getElementById('editUserForm').action = `/users/${user.id}`;
        openModal('editUserModal');
    }

    function openDeleteModal(userId) {
        document.getElementById('deleteUserForm').action = `/users/${userId}`;
        openModal('deleteUserModal');
    }
</script>
@endpush
