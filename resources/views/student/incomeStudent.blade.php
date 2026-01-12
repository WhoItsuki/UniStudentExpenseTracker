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

    <title>Student Income</title>

    <style>
        table th,
        table td {
            padding: 0.5rem 0.75rem;
        }
        #editModal {
            display: flex;
            align-items: center;
            justify-content: center;
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
                            <li class="m-0 p-0"><a href="/income" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-coins mr-2"></i>Income</a></li>
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
                <div class="bg-white border-1 py-3 px-4 rounded-lg shadow p-6 mb-2"><span class="text-gray-700">Welcome, {{ session('student_name') }} !</span></div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-coins mr-2"></i>Income</h2>
                    <p class="text-gray-600"><i class="fas fa-cogs mr-1"></i>Manage your income here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg" style="height: 500px;"> 
                            <h6><i class="fas fa-history mr-1"></i>Recent incomes' transactions</h6>
                            <div class="flex flex-row align-items-baseline gap-4 py-2">
                                <form id="globalFilter" class="flex flex-wrap gap-3 items-end" method="GET" action="/viewIncomes">
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1"><i class="fas fa-calendar-alt mr-1"></i>Start Date</label>
                                        <input type="date" id="globalStartDate" name="start_date" class="border-2 rounded-lg p-2 text-sm" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1"><i class="fas fa-calendar-alt mr-1"></i>End Date</label>
                                        <input type="date" id="globalEndDate" name="end_date" class="border-2 rounded-lg p-2 text-sm" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1"><i class="fas fa-tags mr-1"></i>Category</label>
                                        <select id="categoryFilter" name="categoryFilter" class="border-2 rounded-2 p-1 text-sm">
                                            <option value="">All category</option>
                                            @forelse($categories ?? [] as $category)
                                                <option value="{{ $category->categoryID }}" {{ request('categoryFilter') == $category->categoryID ? 'selected' : '' }}>{{ $category->categoryName }}</option>
                                            @empty
                                            <option value="">No categories available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div>
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                                            Apply Filters
                                        </button>
                                    </div>
                                    <div>
                                        <a href="/income" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium inline-block">
                                            Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <br>
                            <div class="overflow-auto w-100">
                                <table class="border-collapse w-100 max-h-50 table-bordered border-2 border-black">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($incomes ?? [] as $income)
                                        <tr>
                                            <td>{{ $income->incomeName }}</td>
                                            <td>{{ $income->incomeDate->format('d-m-Y') }}</td>
                                            <td>{{ $income->category->categoryName ?? '-' }}</td>
                                            <td>RM{{ number_format($income->incomeAmount, 2) }}</td>
                                            <td>
                                                <form action="/income/{{ $income->incomeID }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1" onclick="return confirm('Are you sure you want to delete this income?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">No incomes found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <h6>Total income : RM{{ number_format($totalIncomes ?? 0, 2) }}</h6>
                            
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg">
                            <h6><i class="fas fa-plus-circle mr-1"></i>Add new income</h6>
                            <form action="/addIncome" method="POST" class="flex flex-column w-75 px-5 py-2 gap-4">
                                @csrf
                                <div class="flex flex-row justify-between">
                                    <label>Income's Name:</label>
                                    <input type="text" name="incomeName" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Amount:</label>
                                    <input type="number" name="incomeAmount" step="0.01" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Category:</label>
                                    <select name="categoryID" class="border-2 rounded-2 p-1" required>
                                        <option value="">Select Category</option>
                                        @forelse($categories ?? [] as $category)
                                            <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                                        @empty
                                        <option value="">No categories available</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Date:</label>
                                    <input type="date" name="incomeDate" class="border-2 rounded-2 p-1" required>
                                </div>

                                <div class="flex flex-col align-items-center">
                                    <input type="submit" class="p-1 rounded-2 bg-blue-600 text-white hover:bg-blue-800 w-25" value="Submit">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        // Function to confirm delete (now handled by form submission)
        function confirmDelete(incomeID, incomeName) {
            return confirm(`Are you sure you want to delete the income "${incomeName}"? This action cannot be undone.`);
        }

        // Optional: Auto-submit form when category filter changes
        document.getElementById('categoryFilter').addEventListener('change', function() {
            // Uncomment the line below if you want auto-submit on category change
            // document.getElementById('globalFilter').submit();
        });
    </script>
</body>
</html>

