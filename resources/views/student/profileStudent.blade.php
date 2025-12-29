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

    <title>Student Dashboard</title>
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
                            <li class="m-0 p-0"><a href="/profileStudent" class="text-blue-700 underline hover:text-blue-900 hover:underline">Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Budgets</a></li>
                            <li class="m-0 p-0"><a href="/income" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Income</a></li>
                            <li class="m-0 p-0"><a href="/category" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Category</a></li>
                        </ol>
                    </div>
                    <div class="flex items-center">
                            <a href="#" class="text-red-600 hover:text-red-800">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <br><br>
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Profile</h2>
                    <p class="text-gray-600">Manage your profile.</p>

                    <div class="m-0 flex items-stretch gap-4 p-0 flex-between">
                        <div class="w-50 border-2 rounded-2 hover:shadow-lg p-2 flex flex-col h-full">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 text-center">Profile Information</h6>
                            <div class="flex flex-col gap-4 flex-grow"> 
                                <!-- Full Name -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Full Name:</label>
                                    <div class="text-gray-800 font-medium">Ahmad Albab</div>
                                </div>
                                
                                <!-- Programme -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Programme:</label>
                                    <div class="text-gray-800 font-medium">Information Technology</div>
                                </div>
                                
                                <!-- Faculty -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Faculty:</label>
                                    <div class="text-gray-800 font-medium">Faculty of Computing</div>
                                </div>
                                
                                <!-- Email -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium text-gray-600">Email:</label>
                                    <div class="text-gray-800 font-medium">example@gmail.com</div>
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
                                <h6 class="text-lg font-semibold text-gray-800 mb-4 text-center">Edit profile:</h6>
                                <form method="" class="w-75 flex flex-column gap-4">
                                    <div class="flex justify-between">
                                        <label>New First Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" value="Ahmad">
                                    </div>
                                    <div class="flex justify-between">
                                        <label >New Last Name:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" value="Albab">
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Programme:</label>
                                    <input class="border-2 p-1 rounded-2" type="text" value="Information Technology">
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Faculty:</label>
                                        <input class="border-2 p-1 rounded-2" type="text" value="Faculty of Computing">
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Email:</label>
                                        <input class="border-2 p-1 rounded-2" type="email" value="example@gmail.com">        
                                    </div>
                                    <div class="flex justify-between">
                                        <label>New Password:</label>
                                        <input class="border-2 p-1 rounded-2" type="password" value="example@gmail.com">    
                                    </div>
                                    <div class="flex flex-column items-center">
                                        <input type="submit" value="Submit" class="p-1 rounded-2 bg-blue-600 text-white hover:bg-blue-800 w-25">
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
                passwordInput.value = 'examplePassword123'; // Replace with actual password when connected to backend
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

