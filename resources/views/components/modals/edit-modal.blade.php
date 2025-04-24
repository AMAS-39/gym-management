<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-gray-800 text-white w-full max-w-md rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">✏️ Edit User</h2>
        <form method="POST" id="editUserForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editUserId">
            <div class="mb-4">
                <label class="block font-semibold">Name</label>
                <input type="text" name="name" id="editUserName" class="w-full p-2 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Email</label>
                <input type="email" name="email" id="editUserEmail" class="w-full p-2 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Role</label>
                <select name="role" id="editUserRole" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <option value="admin">Admin</option>
                    <option value="trainer">Trainer</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 bg-gray-500 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</div>
