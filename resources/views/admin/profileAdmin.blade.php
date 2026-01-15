@if(!session('admin_logged_in'))
    <script>
        window.location.href = '/loginAdmin';
    </script>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart.js') }}" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-md fixed-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Student Expense Tracker</h1>
                    </div>
                    <div class="flex items-center">
                        <ol class="flex items-center gap-20 list-none m-0 p-0">
                            <li class="m-0 p-0"><a href="/profileAdmin" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-user mr-2"></i>Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardAdmin" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/studentAdmin" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-users mr-2"></i>Students</a></li>
                        </ol>
                    </div>
                    <div class="flex items-center">
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 border-none bg-transparent cursor-pointer">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                </button>
                            </form>
                    </div>
                </div>
            </div>
        </nav>
        <br><br>
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
                <div class="bg-white rounded-lg shadow p-6">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Admin Profile</h2>
                    <p class="text-gray-600">Welcome, {{ $admin->adminFName }} {{ $admin->adminLName }}! Manage your admin profile information below.</p>

                    <!-- Messages -->
                    
                    <div class="m-0 flex items-stretch gap-4 p-0 flex-between">
                        <div class="w-50 border-2 rounded-2 hover:shadow-lg p-2 flex flex-col h-full">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 text-center">Profile Information</h6>
                            <div class="flex flex-col gap-4 flex-grow p-4">
                                <!-- Full Name -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Full Name:</label>
                                    <div class="text-gray-800 font-medium">{{ $admin->adminFName }} {{ $admin->adminLName }}</div>
                                </div>

                                <!-- Admin ID -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Admin ID:</label>
                                    <div class="text-gray-800 font-medium">{{ $admin->adminID }}</div>
                                </div>

                                <!-- Email -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Email:</label>
                                    <div class="text-gray-800 font-medium">{{ $admin->adminEmail }}</div>
                                </div>

                                <!-- Password -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Password:</label>
                                    <div class="flex items-center gap-2">
                                        <input type="password"
                                               id="passwordDisplay"
                                               value="••••••••"
                                               class="text-gray-800 font-medium border-none bg-transparent p-0 focus:outline-none">
                                        <button type="button"
                                                id="togglePassword"
                                                onclick="togglePasswordVisibility()"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium px-2 py-1 border border-blue-600 rounded hover:bg-blue-50 transition-colors">
                                            Show
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-50 flex flex-col gap-5">
                            <div class="flex flex-column rounded-2 items-center justify-baseline gap-3 border-2 m-0 p-2 hover:shadow-lg h-full">
                                <h6 class="text-lg font-semibold text-gray-800 mb-4 text-center"><i class="fas fa-user-edit mr-1"></i>Edit profile:</h6>
                                <form action="{{ route('admin.updateProfile') }}" method="POST" class="w-75 flex flex-column gap-4">
                                    @csrf
                                    <div class="flex justify-between">
                                        <label>New First Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" name="adminFName" value="{{ $admin->adminFName }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label >New Last Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" name="adminLName" value="{{ $admin->adminLName }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Email:</label>
                                        <input class="border-2 p-1 rounded-2" type="email" name="adminEmail" value="{{ $admin->adminEmail }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Password (leave empty to keep current):</label>
                                        <input class="border-2 p-1 rounded-2" type="password" name="password" placeholder="Enter new password">
                                    </div>
                                    <div class="flex justify-between">
                                        <label>Confirm New Password:</label>
                                        <input class="border-2 p-1 rounded-2" type="password" name="password_confirmation" placeholder="Confirm new password">
                                    </div>
                                    <div class="flex flex-column items-center">
                                        <input type="submit" value="Update Profile" class="p-1 rounded-2 bg-blue-600 text-white hover:bg-blue-800 w-25">
                                    </div>
                                </form>
                            </div>




                        </div>
                    </div>
                </div>
            </div>


        </main>
    </div>

    <script>
        // Store the actual password in a JavaScript variable
        const adminPassword = '{{ $admin->password }}';

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordDisplay');
            const toggleButton = document.getElementById('togglePassword');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordInput.value = adminPassword;
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                passwordInput.value = '••••••••';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>
