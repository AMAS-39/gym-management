<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Gym Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-gray-800 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <i class="fas fa-dumbbell text-blue-700 text-4xl mb-2"></i>
            <h2 class="text-3xl font-bold text-gray-800">Gym Management</h2>
            <p class="text-sm text-gray-500 mt-1">Login to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-gray-600 font-medium mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm text-gray-600 font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            @if($errors->any())
                <p class="text-sm text-red-600 font-medium">{{ $errors->first() }}</p>
            @endif

            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 rounded-lg transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account? <a href="#" class="text-blue-600 hover:underline">Contact Admin</a>
        </p>
    </div>

</body>
</html>
