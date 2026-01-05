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
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Student Sign up</h1>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="studentSignup" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">First Name</label>
                    <input type="text" 
                           name="studentFname" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Enter your first name" 
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Last Name</label>
                    <input type="text" 
                           name="studentLname" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"        
                           placeholder="Enter your last name" 
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Programme</label>
                    <input type="text" 
                           name="programme" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"        
                           placeholder="Programme" 
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Faculty</label>
                    <select name="studentFaculty"
                            class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select Faculty</option>
                        <option value="Faculty of Computing">Faculty of Computing</option>
                        <option value="Faculty of Engineering">Faculty of Engineering</option>
                        <option value="Faculty of Business">Faculty of Business</option>
                        <option value="Faculty of Science">Faculty of Science</option>
                        <option value="Faculty of Arts">Faculty of Arts</option>
                        <option value="Faculty of Medicine">Faculty of Medicine</option>
                        <option value="Faculty of Law">Faculty of Law</option>
                        <option value="Faculty of Education">Faculty of Education</option>
                        <option value="Faculty of Social Sciences">Faculty of Social Sciences</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" 
                           name="studentEmail" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"        
                           placeholder="Enter your email" 
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Student ID</label>
                    <input type="text" 
                           name="studentID" 
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"        
                           placeholder="Enter your student ID" 
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password"
                           name="password"
                           class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your password"
                           required>
                </div>


                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium">
                    Sign Up
                </button>
            </form><br>
            <p class="text-center">Already have account? <a href="/loginStudent">Login</a></p>
            <div class="text-center mt-4 text-gray-500">
                <a href="/loginAdmin" class="no-underline hover:underline text-blue-600">
                <p>Login as Administrator</p>
                </a>
            </div>
        </div><br>
        
    </div>
</body>
</html>