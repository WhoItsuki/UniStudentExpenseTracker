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
                        <h1 class="text-xl font-bold text-gray-800">Student Expense Tracker</h1>
                    </div>
                    <div class="flex items-center">
                        <ol class="flex items-center gap-20 list-none m-0 p-0">
                            <li class="m-0 p-0"><a href="/profileStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-user mr-2"></i>Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardStudent" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
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
                <div class="bg-white border-1 py-3 px-4 rounded-lg shadow p-6 mb-2"><span class="text-gray-700">Welcome, {{ session('student_name') }} !</span></div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h2>
                    <p class="text-gray-600">Welcome to your expense tracking dashboard. This is where you can manage your financials.</p>

                    <div class="m-0 flex items-center gap-4 p-0">
                        <div class="w-50 h-100 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col items-center">
                            <h1 class="text-center text-xl underline">Total Expenses by Category</h1>
                            
                            <canvas id="expenseChart" style="max-width:350px; max-height: 600px;"></canvas>

                            <h6 id="totalExpensesText" class="text-center">Total Expenses: RM{{ number_format($totalExpense, 2) }}</h6>
                        </div>
                        <div class="w-50 flex flex-col gap-5 justify-around ">
                            <div class="flex flex-row gap-5 align-content-baseline">
                                <h6>Filter:</h6>
                                <div class="flex gap-3">
                                    <select id="periodFilter" class="border-2 p-1 px-3 rounded-2">
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly" selected>Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                    <button id="applyFilter" class="p-1 px-3 rounded-2 text-white bg-blue-600 hover:bg-blue-800">
                                        Apply Filter
                                    </button>
                                </div>
                            </div>
                            <div class="h-50 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col">
                                <h1 class="text-center text-xl underline">Current Balance (Income - Expense = Balance)</h1>
                                <br>
                                <h6 class="text-center">RM{{ number_format($totalIncome, 2) }} - RM{{ number_format($totalExpense, 2) }} = RM{{ number_format($currentBalance, 2) }}</h6>
                            </div>

                            <div class="h-50 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col items-center">
                                <h1 class="text-center text-xl underline">Budget vs Expense</h1>
                                <canvas id="expenseVSbudgetChart" style=""></canvas>

                                <h6 id="budgetExpenseText" class="text-center">Budget: RM{{ number_format($totalBudget, 2) }} Expense: RM{{ number_format($totalExpenseForBudget, 2) }}</h6>
                            </div>
                            
                        </div>
                    </div>


                </div>
            </div>


        </main>
    </div>

    <script>
        // Function to get color based on category name
        function getCategoryColor(categoryName) {
            const categoryColors = {
                // Food & Dining
                'Food': '#FF6384',
                'food': '#FF6384',
                'Dining': '#FF6384',
                'dining': '#FF6384',
                'Restaurant': '#FF6384',
                'restaurant': '#FF6384',
                'Groceries': '#FF6384',
                'groceries': '#FF6384',

                // Entertainment
                'Entertainment': '#36A2EB',
                'entertainment': '#36A2EB',
                'Movies': '#36A2EB',
                'movies': '#36A2EB',
                'Games': '#36A2EB',
                'games': '#36A2EB',
                'Music': '#36A2EB',
                'music': '#36A2EB',

                // Transportation
                'Transportation': '#FFCE56',
                'transportation': '#FFCE56',
                'Fuel': '#FFCE56',
                'fuel': '#FFCE56',
                'Car': '#FFCE56',
                'car': '#FFCE56',
                'Bus': '#FFCE56',
                'bus': '#FFCE56',

                // Shopping
                'Shopping': '#4BC0C0',
                'shopping': '#4BC0C0',
                'Clothes': '#4BC0C0',
                'clothes': '#4BC0C0',
                'Electronics': '#4BC0C0',
                'electronics': '#4BC0C0',

                // Education
                'Education': '#9966FF',
                'education': '#9966FF',
                'Books': '#9966FF',
                'books': '#9966FF',
                'Courses': '#9966FF',
                'courses': '#9966FF',

                // Health & Fitness
                'Health': '#FF9F40',
                'health': '#FF9F40',
                'Fitness': '#FF9F40',
                'fitness': '#FF9F40',
                'Medical': '#FF9F40',
                'medical': '#FF9F40',

                // Bills & Utilities
                'Bills': '#C9CBCF',
                'bills': '#C9CBCF',
                'Utilities': '#C9CBCF',
                'utilities': '#C9CBCF',
                'Electricity': '#C9CBCF',
                'electricity': '#C9CBCF',

                // Other categories - default color
                'Other': '#8B5CF6',
                'other': '#8B5CF6'
            };

            return categoryColors[categoryName] || '#8B5CF6'; // Default purple color
        }

        // Total Expenses by Category Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseData = @json($expensesByCategory);

        const expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: expenseData.map(item => item.category_name),
                datasets: [{
                    data: expenseData.map(item => parseFloat(item.total_amount)),
                    backgroundColor: expenseData.map(item => getCategoryColor(item.category_name))
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Expenses by Category (Current Month)'
                    }
                }
            }
        });

        // Budget vs Expense Chart (Total Comparison)
        const budgetCtx = document.getElementById('expenseVSbudgetChart').getContext('2d');
        const totalBudget = @json($totalBudget);
        const totalExpenseForBudget = @json($totalExpenseForBudget);

        const budgetChart = new Chart(budgetCtx, {
            type: 'bar',
            data: {
                labels: ['Total Budget vs Total Expense'],
                datasets: [{
                    label: 'Total Budget',
                    data: [parseFloat(totalBudget)],
                    backgroundColor: '#36A2EB'
                }, {
                    label: 'Total Expense',
                    data: [parseFloat(totalExpenseForBudget)],
                    backgroundColor: '#FF6384'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Total Budget vs Total Expense (Current Month)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (RM)'
                        }
                    }
                }
            }
        });

        // Global variables to store chart instances
        let expenseChartInstance = expenseChart;
        let budgetChartInstance = budgetChart;

        // Function to fetch expenses by category data
        async function fetchExpensesByCategory(period) {
            try {
                const response = await fetch(`/api/expenses-by-category/${period}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching expenses by category:', error);
                return null;
            }
        }

        // Function to fetch budget vs expense data
        async function fetchBudgetVsExpense(period) {
            try {
                const response = await fetch(`/api/budget-vs-expense/${period}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching budget vs expense:', error);
                return null;
            }
        }

        // Function to update expense chart
        function updateExpenseChart(data) {
            if (!data || !data.data) return;

            const periodLabel = data.period.charAt(0).toUpperCase() + data.period.slice(1);

            // Calculate total expenses
            const totalExpenses = data.data.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);

            // Update chart data
            expenseChartInstance.data.labels = data.data.map(item => item.category_name);
            expenseChartInstance.data.datasets[0].data = data.data.map(item => parseFloat(item.total_amount));
            expenseChartInstance.data.datasets[0].backgroundColor = data.data.map(item => getCategoryColor(item.category_name));

            // Update chart title
            expenseChartInstance.options.plugins.title.text = `Expenses by Category (${periodLabel})`;

            // Update the chart
            expenseChartInstance.update();

            // Update total expenses text
            document.getElementById('totalExpensesText').textContent = `Total Expenses: RM${totalExpenses.toFixed(2)}`;
        }

        // Function to update budget vs expense chart
        function updateBudgetChart(data) {
            if (!data || !data.data) return;

            const periodLabel = data.period.charAt(0).toUpperCase() + data.period.slice(1);

            // Update chart data
            budgetChartInstance.data.datasets[0].data = [parseFloat(data.data.total_budget)];
            budgetChartInstance.data.datasets[1].data = [parseFloat(data.data.total_expense)];

            // Update chart title
            budgetChartInstance.options.plugins.title.text = `Total Budget vs Total Expense (${periodLabel})`;

            // Update the chart
            budgetChartInstance.update();

            // Update budget vs expense text
            const budgetText = parseFloat(data.data.total_budget).toFixed(2);
            const expenseText = parseFloat(data.data.total_expense).toFixed(2);
            document.getElementById('budgetExpenseText').textContent = `Budget: RM${budgetText} Expense: RM${expenseText}`;
        }

        // Function to apply filter
        async function applyFilter() {
            const periodFilter = document.getElementById('periodFilter');
            const selectedPeriod = periodFilter.value;

            // Show loading state
            const applyButton = document.getElementById('applyFilter');
            const originalText = applyButton.textContent;
            applyButton.textContent = 'Loading...';
            applyButton.disabled = true;

            try {
                // Fetch both datasets in parallel
                const [expenseData, budgetData] = await Promise.all([
                    fetchExpensesByCategory(selectedPeriod),
                    fetchBudgetVsExpense(selectedPeriod)
                ]);

                // Update charts with new data
                if (expenseData) {
                    updateExpenseChart(expenseData);
                }
                if (budgetData) {
                    updateBudgetChart(budgetData);
                }

            } catch (error) {
                console.error('Error applying filter:', error);
                alert('Error loading data. Please try again.');
            } finally {
                // Reset button state
                applyButton.textContent = originalText;
                applyButton.disabled = false;
            }
        }

        // Event listeners
        document.getElementById('applyFilter').addEventListener('click', applyFilter);

        // Optional: Auto-apply filter when select changes (commented out for now)
        // document.getElementById('periodFilter').addEventListener('change', applyFilter);
    </script>
</body>
</html>

