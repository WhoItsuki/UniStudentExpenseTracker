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

    <title>Student Details - {{ $student->studentFname }} {{ $student->studentLname }}</title>
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
                            <li class="m-0 p-0"><a href="/profileAdmin" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-user mr-2"></i>Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardAdmin" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/studentAdmin" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-users mr-2"></i>Students</a></li>
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

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0 w-full">
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="/studentAdmin" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Student List
                    </a>
                </div>

                <!-- Page Title -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Student Details</h2>
                    <p class="text-gray-600">Detailed financial information for {{ $student->studentFname }} {{ $student->studentLname }}</p>
                </div>

                <!-- Student Information -->
                <div class="mb-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Student Information</h4>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Student ID:</strong> {{ $student->studentID }}</p>
                                <p><strong>Name:</strong> {{ $student->studentFname }} {{ $student->studentLname }}</p>
                                <p><strong>Email:</strong> {{ $student->studentEmail }}</p>
                            </div>
                            <div>
                                <p><strong>Programme:</strong> {{ $student->programme ?? 'N/A' }}</p>
                                <p><strong>Faculty:</strong> {{ $student->studentFaculty ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="mb-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Financial Summary</h4>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <h5 class="text-lg font-semibold text-green-600">Total Income</h5>
                                <p class="text-2xl font-bold text-green-600">RM{{ number_format($totalIncome, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <h5 class="text-lg font-semibold text-red-600">Total Expenses</h5>
                                <p class="text-2xl font-bold text-red-600">RM{{ number_format($totalExpenses, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <h5 class="text-lg font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">Current Balance</h5>
                                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">RM{{ number_format($balance, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="mb-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Financial Analysis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Expense by Category Chart -->
                        <div class="bg-white border-2 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3">Expenses by Category</h5>
                            <canvas id="expenseChart" width="400" height="300"></canvas>
                        </div>

                        <!-- Income by Category Chart -->
                        <div class="bg-white border-2 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3">Income by Category</h5>
                            <canvas id="incomeChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Expenses -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">Recent Expenses</h4>
                        <div class="space-y-3">
                            @forelse($student->expenses ?? collect()->take(5) as $expense)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $expense->expenseName }}</p>
                                    <p class="text-sm text-gray-600">{{ $expense->category->categoryName ?? 'Uncategorized' }}</p>
                                </div>
                                <span class="text-red-600 font-semibold">RM{{ number_format($expense->expenseAmount, 2) }}</span>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No expenses recorded</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Income -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">Recent Income</h4>
                        <div class="space-y-3">
                            @forelse($student->incomes ?? collect()->take(5) as $income)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $income->incomeName }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($income->incomeDate)->format('M d, Y') }}</p>
                                </div>
                                <span class="text-green-600 font-semibold">RM{{ number_format($income->incomeAmount, 2) }}</span>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No income recorded</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Expense Chart
                const expenseCategories = @json($expenseCategories ?? []);
                const expenseCanvas = document.getElementById('expenseChart');

                if (expenseCanvas && Object.keys(expenseCategories).length > 0) {
                    const expenseCtx = expenseCanvas.getContext('2d');
                    new Chart(expenseCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(expenseCategories),
                            datasets: [{
                                data: Object.values(expenseCategories),
                                backgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `RM${parseFloat(context.parsed).toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else if (expenseCanvas) {
                    const expenseCtx = expenseCanvas.getContext('2d');
                    expenseCtx.font = '16px Arial';
                    expenseCtx.fillStyle = '#666';
                    expenseCtx.textAlign = 'center';
                    expenseCtx.fillText('No expense data', expenseCtx.canvas.width / 2, expenseCtx.canvas.height / 2);
                }

                // Income Chart
                const incomeCategories = @json($incomeCategories ?? []);
                const incomeCanvas = document.getElementById('incomeChart');

                if (incomeCanvas && Object.keys(incomeCategories).length > 0) {
                    const incomeCtx = incomeCanvas.getContext('2d');
                    new Chart(incomeCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(incomeCategories),
                            datasets: [{
                                data: Object.values(incomeCategories),
                                backgroundColor: [
                                    '#4BC0C0', '#FF9F40', '#9966FF', '#FF6384',
                                    '#36A2EB', '#FFCE56', '#C9CBCF'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `RM${parseFloat(context.parsed).toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else if (incomeCanvas) {
                    const incomeCtx = incomeCanvas.getContext('2d');
                    incomeCtx.font = '16px Arial';
                    incomeCtx.fillStyle = '#666';
                    incomeCtx.textAlign = 'center';
                    incomeCtx.fillText('No income data', incomeCtx.canvas.width / 2, incomeCtx.canvas.height / 2);
                }
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        });
    </script>
</body>
</html>