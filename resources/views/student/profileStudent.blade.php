<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart.js') }}" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Student Dashboard</title>
</head>
<body class="bg-gray-100">
    @if(!session('student_name'))
        <script>window.location.href = '/loginStudent';</script>
    @endif
    <div class="min-h-screen">
        <nav class="bg-white shadow-md fixed-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800"><i class="fas fa-graduation-cap mr-2"></i>Student Expense Tracker</h1>
                    </div>
                    <div class="flex items-center">
                        <ol class="flex items-center gap-20 list-none m-0 p-0">
                            <li class="m-0 p-0"><a href="/profileStudent" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-user mr-2"></i>Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-wallet mr-2"></i>Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-chart-pie mr-2"></i>Budgets</a></li>
                            <li class="m-0 p-0"><a href="/income" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-coins mr-2"></i>Income</a></li>
                            <li class="m-0 p-0"><a href="/category" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tags mr-2"></i>Category</a></li>
                        </ol>
                    </div>
                    <div class="flex items-center space-x-4">

                        <form action="{{ route('student.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 no-underline"><i class="fas fa-sign-out-alt mr-1"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <br><br>
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
                @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                
                <div class="bg-white border-1 py-3 px-4 rounded-lg shadow p-6 mb-2"><span class="text-gray-700">Welcome, {{ session('student_name') }} !</span></div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Profile</h2>

                    <p class="text-gray-600">Manage your profile.</p>

                    <div class="m-0 flex items-stretch gap-4 p-0 flex-between">
                        <div class="w-50 border-2 rounded-2 hover:shadow-lg p-2 flex flex-col h-full">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 text-center">Profile Information</h6>
                            <div class="flex flex-col gap-4 flex-grow p-4"> 
                                <!-- Full Name -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Full Name:</label>
                                    <div class="text-gray-800 font-medium">{{ $student->studentFname . ' ' . $student->studentLname }}</div>
                                </div>
                                
                                <!-- Programme -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Programme:</label>
                                    <div class="text-gray-800 font-medium">{{ $student->programme }}</div>
                                </div>

                                <!-- Faculty -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Faculty:</label>
                                    <div class="text-gray-800 font-medium">{{ $student->studentFaculty }}</div>
                                </div>

                                <!-- Email -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Email:</label>
                                    <div class="text-gray-800 font-medium">{{ $student->studentEmail }}</div>
                                </div>
                                
                                <!-- Password -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Password:</label>
                                    <div class="flex items-center gap-2">
                                        <input type="password" 
                                               id="passwordDisplay" 
                                               value="••••••••" 
                                               readonly 
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
                                <form action="{{ route('student.updateProfile') }}" method="POST" class="w-75 flex flex-column gap-4">
                                    @csrf
                                    <div class="flex justify-between">
                                        <label>New First Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" name="studentFname" value="{{ $student->studentFname }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label >New Last Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" name="studentLname" value="{{ $student->studentLname }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Programme:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" name="programme" value="{{ $student->programme }}" required>
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Faculty:</label>
                                        <select class="border-2 p-1 rounded-2" name="studentFaculty" required>
                                            <option value="">Select Faculty</option>
                                            <option value="Faculty of Computing" {{ $student->studentFaculty == 'Faculty of Computing' ? 'selected' : '' }}>Faculty of Computing</option>
                                            <option value="Faculty of Engineering" {{ $student->studentFaculty == 'Faculty of Engineering' ? 'selected' : '' }}>Faculty of Engineering</option>
                                            <option value="Faculty of Business" {{ $student->studentFaculty == 'Faculty of Business' ? 'selected' : '' }}>Faculty of Business</option>
                                            <option value="Faculty of Science" {{ $student->studentFaculty == 'Faculty of Science' ? 'selected' : '' }}>Faculty of Science</option>
                                            <option value="Faculty of Arts" {{ $student->studentFaculty == 'Faculty of Arts' ? 'selected' : '' }}>Faculty of Arts</option>
                                            <option value="Faculty of Medicine" {{ $student->studentFaculty == 'Faculty of Medicine' ? 'selected' : '' }}>Faculty of Medicine</option>
                                            <option value="Faculty of Law" {{ $student->studentFaculty == 'Faculty of Law' ? 'selected' : '' }}>Faculty of Law</option>
                                            <option value="Faculty of Education" {{ $student->studentFaculty == 'Faculty of Education' ? 'selected' : '' }}>Faculty of Education</option>
                                            <option value="Faculty of Social Sciences" {{ $student->studentFaculty == 'Faculty of Social Sciences' ? 'selected' : '' }}>Faculty of Social Sciences</option>
                                        </select>
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Email:</label>
                                        <input class="border-2 p-1 rounded-2" type="email" name="studentEmail" value="{{ $student->studentEmail }}" required>
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
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordDisplay');
            const toggleButton = document.getElementById('togglePassword');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordInput.value = '{{ $student->password }}'; // Show actual password
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

