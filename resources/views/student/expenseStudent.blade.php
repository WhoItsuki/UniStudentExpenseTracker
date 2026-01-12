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

    <title>Student Expenses</title>
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
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-wallet mr-2"></i>Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-chart-pie mr-2"></i>Budgets</a></li>
                            <li class="m-0 p-0"><a href="/income" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-coins mr-2"></i>Income</a></li>
                            <li class="m-0 p-0"><a href="/category" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tags mr-2"></i>Category</a></li>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-wallet mr-2"></i>Expenses</h2>
                    <p class="text-gray-600"><i class="fas fa-cogs mr-1"></i>Manage your expenses here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg" style="height: 500px;"> 
                            <h6><i class="fas fa-history mr-1"></i>Recent expenses' transactions</h6>
                            <div class="flex flex-row align-items-baseline gap-4 py-2">
                                <form id="globalFilter" class="flex flex-wrap gap-3 items-end" method="GET" action="/viewExpenses">
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
                                            <i class="fas fa-filter mr-1"></i>Apply Filters
                                        </button>
                                    </div>
                                    <div>
                                        <a href="/viewExpenses" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium inline-block">
                                            <i class="fas fa-undo mr-1"></i>Reset
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
                                        @forelse($expenses ?? [] as $expense)
                                        <tr>
                                            <td>{{ $expense->expenseName }}</td>
                                            <td>{{ $expense->expenseDate->format('d-m-Y') }}</td>
                                            <td>{{ $expense->category->categoryName ?? '-' }}</td>
                                            <td>RM{{ number_format($expense->expenseAmount, 2) }}</td>
                                            <td>
                                                <button onclick="openEditModal('{{ $expense->expenseName }}', '{{ $expense->expenseDate->format('d-m-Y') }}', '{{ $expense->category->categoryName ?? '-' }}', 'RM{{ number_format($expense->expenseAmount, 2) }}', {{ $expense->expenseID }})" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 text-sm"><i class="fas fa-edit mr-1"></i>Edit</button>
                                                <form action="/expense/{{ $expense->expenseID }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1" onclick="return confirm('Are you sure you want to delete this expense?')"><i class="fas fa-trash mr-1"></i>Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">No expenses found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <h6>Total expenses : RM{{ number_format($totalExpenses ?? 0, 2) }}</h6>
                            
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6>Add new expense</h6>
                            <form action="/addExpense" method="POST" class="flex flex-column w-75 px-5 py-2 gap-4">
                                @csrf
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Name:</label>
                                    <input type="text" name="expenseName" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Amount:</label>
                                    <input type="number" name="expenseAmount" class="border-2 rounded-2 p-1" step="0.01" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Category:</label>
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
                                    <label>Expense's Date:</label>
                                    <input type="date" name="expenseDate" class="border-2 rounded-2 p-1" required>
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

    <!-- Edit Expense Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Edit Expense</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <form id="editExpenseForm" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="expenseIdInput" name="expenseID">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Expense Name:</label>
                    <input type="text" id="editExpenseName" name="expenseName" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Date:</label>
                    <input type="date" id="editExpenseDate" name="expenseDate" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Category:</label>
                    <select id="editExpenseCategory" name="categoryID" class="border-2 rounded-lg p-2" required>
                        <option value="">Select Category</option>
                        @forelse($categories ?? [] as $category)
                            <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                        @empty
                        <option value="">No categories available</option>
                        @endforelse
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Amount:</label>
                    <input type="number" id="editExpenseAmount" name="expenseAmount" class="border-2 rounded-lg p-2" step="0.01" required>
                </div>
                <div class="flex gap-2 justify-end mt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(name, date, category, amount, expenseId) {
            document.getElementById('expenseIdInput').value = expenseId;
            document.getElementById('editExpenseName').value = name;
            
            // Convert date from DD-MM-YYYY to YYYY-MM-DD format
            const dateParts = date.split('-');
            if (dateParts.length === 3) {
                const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                document.getElementById('editExpenseDate').value = formattedDate;
            } else {
                document.getElementById('editExpenseDate').value = date;
            }
            
            document.getElementById('editExpenseCategory').value = category;
            // Remove 'RM' prefix if present and set amount
            const amountValue = amount.replace('RM', '').trim();
            document.getElementById('editExpenseAmount').value = amountValue;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Handle form submission
        document.getElementById('editExpenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const expenseId = document.getElementById('expenseIdInput').value;
            this.action = `/expense/${expenseId}`;
            this.submit();
        });
    </script>
</body>
</html>

