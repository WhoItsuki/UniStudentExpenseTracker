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
                        <span class="text-gray-700"><i class="fas fa-user-circle mr-2"></i>{{ session('student_name') }}</span>
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Income</h2>
                    <p class="text-gray-600">Manage your income here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg" style="height: 500px;"> 
                            <h6>Recent incomes' transactions</h6>
                            <div class="flex flex-row align-items-baseline gap-4 py-2">
                                <form id="globalFilter" class="flex flex-wrap gap-3 items-end" onsubmit="submitGlobalFilter(event)">
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" id="globalStartDate" name="start_date" class="border-2 rounded-lg p-2 text-sm" required>
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input type="date" id="globalEndDate" name="end_date" class="border-2 rounded-lg p-2 text-sm" required>
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">Time Frame</label>
                                        <select id="categoryFilter" name="categoryFilter" class="border-2 rounded-2 p-1 text-sm" onchange="syncCategorySelector()">
                                            <option value="">All category</option>
                                            @forelse($categories ?? [] as $category)
                                                @if($category->categoryType === 'Income')
                                                    <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                                                @endif   
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
                                        <tr>
                                            <td>Lunch</td>
                                            <td>21-10-2026</td>
                                            <td>Food</td>
                                            <td>RM21.00</td>
                                            <td>
                                                <button onclick="confirmDelete(1, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lunch</td>
                                            <td>21-10-2026</td>
                                            <td>Food</td>
                                            <td>RM21.00</td>
                                            <td>
                                                <button onclick="confirmDelete(2, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lunch</td>
                                            <td>21-10-2026</td>
                                            <td>Food</td>
                                            <td>RM21.00</td>
                                            <td>
                                                <button onclick="confirmDelete(3, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <h6>Total income : RM63.00</h6>
                            
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6>Add new income</h6>
                            <form class="flex flex-column w-75 px-5 py-2 gap-4">
                                <div class="flex flex-row justify-between">
                                    <label>Income's Name:</label>
                                    <input type="text" class="border-2 rounded-2 p-1">
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Amount:</label>
                                    <input type="text" class="border-2 rounded-2 p-1">
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Category:</label>
                                    <select class="border-2 rounded-2 p-1">
                                        <option value="">Allowance</option>
                                        <option value="">Scholarship</option>
                                        <option value="">Part time</option>
                                    </select>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Income's Date:</label>
                                    <input type="date" class="border-2 rounded-2 p-1">
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

        function confirmDelete(incomeID, incomeName) {
            if (confirm(`Are you sure you want to delete the income "${incomeName}"? This action cannot be undone.`)) {
                // Handle delete action here
                console.log('Deleting income with ID:', incomeID);
                // You can add AJAX call here to delete from backend
                alert('Income deleted successfully!');
                // Optionally reload the page or remove the row from table
            }
        }

    </script>
</body>
</html>

