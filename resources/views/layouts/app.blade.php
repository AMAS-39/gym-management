<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🏋️ Gym Management System</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #0f172a; /* slate-900 */
            color: #f8fafc; /* slate-50 */
        }

        ::selection {
            background: #3b82f6; /* blue-500 */
            color: #fff;
        }
    </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-gradient-to-r from-gray-800 via-blue-900 to-gray-900 shadow-md border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-dumbbell text-yellow-400 text-2xl"></i>
                <h1 class="text-2xl font-bold text-white tracking-wide">Gym Management</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-medium text-sm transition-all text-white">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-6 py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-center text-sm text-gray-400 py-4 border-t border-gray-700">
        &copy; {{ date('Y') }} <span class="text-white font-semibold">Gym Management System</span>. All rights reserved.
    </footer>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

    <!-- Extra Scripts -->
    @stack('scripts')
</body>
</html>
