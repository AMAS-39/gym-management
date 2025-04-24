<div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-gray-800 text-white w-full max-w-sm rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-red-400 mb-4">⚠️ Confirm Delete</h2>
        <p class="text-gray-300 mb-4">Are you sure you want to delete this user?</p>
        <form id="deleteUserForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('deleteUserModal')" class="px-4 py-2 bg-gray-500 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </form>
    </div>
</div>
