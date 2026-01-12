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
    <title>Student Categories</title>
    <style>
        table th,
        table td {
            padding: 0.5rem 0.75rem;
        }
    </style>
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
                        <h1 class="text-xl font-bold text-gray-800">Student Expense Tracker</h1>
                    </div>
                    <div class="flex items-center">
                        <ol class="flex items-center gap-20 list-none m-0 p-0">
                            <li class="m-0 p-0"><a href="/profileStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-user mr-2"></i>Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-wallet mr-2"></i>Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-chart-pie mr-2"></i>Budgets</a></li>
                            <li class="m-0 p-0"><a href="/income" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-coins mr-2"></i>Income</a></li>
                            <li class="m-0 p-0"><a href="/category" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-tags mr-2"></i>Category</a></li>
                        </ol>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700"><i class="fas fa-user-circle mr-2"></i>{{ session('student_name') }}</span>
                        <form action="{{ route('student.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 border-none bg-transparent cursor-pointer"><i class="fas fa-sign-out-alt mr-1"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <br><br>
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-tags mr-2"></i>Categories</h2>
                    <p class="text-gray-600 mb-4"><i class="fas fa-cogs mr-1"></i>Manage your expense and income categories here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg" style="height: 500px;"> 
                            <h6 class="font-semibold text-lg mb-3"><i class="fas fa-eye mr-1"></i>Existing Categories</h6>
                            <div class="overflow-auto w-100">
                                <table class="border-collapse w-100 max-h-50 table-bordered border-2 border-black">
                                    <thead>
                                        <tr>
                                            <th>Category Type</th>
                                            <th>Category Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories ?? [] as $category)
                                        <tr>
                                            <td>{{ $category->categoryType }}</td>
                                            <td>{{ $category->categoryName }}</td>
                                            <td>
                                                <button onclick="confirmDelete({{ $category->categoryID }}, '{{ $category->categoryName }}')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm">Delete</button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-gray-500">No categories found. Create your first category below.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6 class="font-semibold text-lg mb-3">Add New Category</h6>
                            <form action="/addCategory" method="POST" class="flex flex-column w-75 px-5 py-2 gap-4">
                                @csrf
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Category Type:</label>
                                    <select name="categoryType" class="border-2 rounded-2 p-1" required>
                                        <option value="">Select Type</option>
                                        <option value="Income">Income</option>
                                        <option value="Expense">Expense/Budget</option>
                                    </select>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Category Name:</label>
                                    <input type="text" name="categoryName" class="border-2 rounded-2 p-1" required>
                                </div>
                                
                                <div class="flex flex-col align-items-center mt-4"> 
                                    <input type="submit" class="p-1 rounded-2 bg-blue-600 text-white hover:bg-blue-800 w-25 cursor-pointer" value="Submit">
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function confirmDelete(categoryId, categoryName) {
            if (confirm(`Are you sure you want to delete the category "${categoryName}"? This action cannot be undone.`)) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/category/${categoryId}`;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

