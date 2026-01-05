<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <title>Login</title>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center flex-col">
        <h1 class="">UniStudent Expense Tracker</h1><br>
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Student Login</h1>

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

            <form action="studentLogin" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="student_id" class="block text-gray-700 text-sm font-medium mb-2">Student ID</label>
                    <input type="text" 
                           id="studentID" 
                           name="studentID" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Enter your student ID" 
                           required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"        
                           placeholder="Enter your password" 
                           required>
                </div>
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium">
                    Login
                </button>
            </form><br>
            <p class="text-center">Don't have account? <a href="/signupStudent">Sign up</a></p>
            <div class="text-center mt-4 text-gray-500">
                <a href="/loginAdmin" class="no-underline hover:underline text-blue-600">
                <p>Login as Administrator</p>
                
                </a>
            </div>
        </div><br>
        
    </div>
</body>
</html>