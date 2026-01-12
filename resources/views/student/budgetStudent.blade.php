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
    <title>Student Budgets</title>
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
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-chart-pie mr-2"></i>Budgets</a></li>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Budgets</h2>
                    <p class="text-gray-600 mb-4">Manage your budgets here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6 class="font-semibold text-lg mb-3">Budget Overview</h6>
                            
                            <!-- Budget Remaining Visual Container -->
                            <div class="w-100 mb-4 p-3 bg-gray-50 rounded-lg border-2">
                                <div class="flex flex-row justify-between items-center mb-3">
                                    <h6 class="font-semibold mb-0">Budget Remaining</h6>
                                    <div class="flex flex-row gap-2">
                                        <select id="categorySelector" class="border-2 rounded-2 p-1 text-sm" onchange="updateBudgetByCategory()">
                                            <option value="">All Categories</option>
                                            @forelse($categories ?? [] as $category)
                                                @if($category->categoryType === 'Expense' || $category->categoryType === 'budget')
                                                    <option value="{{ $category->categoryID }}" data-name="{{ $category->categoryName }}">{{ $category->categoryName }}</option>
                                                @endif
                                            @empty
                                                <option value="">No categories available</option>
                                            @endforelse
                                        </select>
                                        <select id="timePeriodSelector" class="border-2 rounded-2 p-1 text-sm" onchange="updateBudgetByTimePeriod()">
                                            <option value="daily">Daily</option>
                                            <option value="monthly" selected>Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex flex-row gap-2 items-center mb-3">
                                    <div style="width: 200px; height: 200px;">
                                        <canvas id="budgetChart"></canvas>
                                    </div>
                                    <div class="flex flex-column gap-1">
                                        <div>
                                            <p class="text-sm text-gray-600">Total Budget:</p>
                                            <p class="text-lg font-bold text-gray-800">RM<span id="totalBudget">0.00</span></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Used:</p>
                                            <p class="text-lg font-bold text-red-600">RM<span id="usedBudget">0.00</span></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Remaining:</p>
                                            <p class="text-lg font-bold text-green-600">RM<span id="remainingBudget">0.00</span></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Percentage Used:</p>
                                            <p class="text-lg font-bold text-blue-600"><span id="percentageUsed">0</span>%</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Progress Bar Visual -->
                                <div class="w-100 mt-3">
                                    <div class="flex flex-row justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">Budget Usage</span>
                                        <span class="text-sm font-semibold text-gray-700"><span id="progressPercentage">0</span>%</span>
                                    </div>
                                    <div class="w-100 bg-gray-200 rounded-full" style="height: 30px; position: relative; overflow: hidden;">
                                        <div id="progressBar" class="rounded-full" style="height: 100%; width: 0%; background-color: #10b981; transition: all 0.5s ease; display: flex; align-items: center; justify-content: flex-end; padding-right: 8px;">
                                            <span class="text-white text-xs font-bold" id="progressText"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-row justify-between mt-1">
                                        <span class="text-xs text-gray-500">0%</span>
                                        <span class="text-xs text-gray-500">50%</span>
                                        <span class="text-xs text-gray-500">100%</span>
                                    </div>
                                </div>
                                <!-- Additional Bar Chart Visual -->
                                <div class="w-100 mt-4" style="height: 150px;">
                                    <canvas id="budgetBarChart"></canvas>
                                </div>
                            </div>

                            <h6 class="font-semibold text-lg mb-2">Recent Budget Transactions</h6>
                            <div class="flex flex-row align-items-baseline gap-4 py-2">
                                <form id="globalFilter" class="flex flex-wrap gap-3 items-end" method="GET" action="/">
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" id="globalStartDate" name="start_date" class="border-2 rounded-lg p-2 text-sm" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input type="date" id="globalEndDate" name="end_date" class="border-2 rounded-lg p-2 text-sm" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-700 mb-1">Category</label>
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
                                        <a href="/viewBudgets" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium inline-block">
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
                                            <th>Limit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($budgets ?? [] as $budget)
                                        <tr>
                                            <td>{{ $budget->budgetName }}</td>
                                            <td>{{ $budget->budgetDate->format('d-m-Y') }}</td>
                                            <td>{{ $budget->category->categoryName ?? '-' }}</td>
                                            <td>RM{{ number_format($budget->budgetLimit, 2) }}</td>
                                            <td>
                                                <button type="button" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 text-sm open-edit-budget-modal"
                                                    onclick="openEditBudgetModal({
                                                        budgetID: '{{ $budget->budgetID }}',
                                                        budgetName: '{{ addslashes($budget->budgetName) }}',
                                                        budgetLimit: '{{ $budget->budgetLimit }}',
                                                        categoryID: '{{ $budget->categoryID }}',
                                                        budgetDate: '{{ $budget->budgetDate->format('Y-m-d') }}'
                                                    })">
                                                    Edit
                                                </button>
                                                <form action="/budget/{{ $budget->budgetID }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1" onclick="return confirm('Are you sure you want to delete this budget?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">No budgets found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <h6>Total budgets : RM{{ number_format($totalBudgets ?? 0, 2) }}</h6>
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6>Add new budget</h6>
                            <form action="/addBudget" method="POST" class="flex flex-column w-75 px-5 py-2 gap-4">
                                @csrf
                                <div class="flex flex-row justify-between">
                                    <label>Budget Name:</label>
                                    <input type="text" name="budgetName" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Budget Limit:</label>
                                    <input type="number" name="budgetLimit" class="border-2 rounded-2 p-1" step="0.01" required>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Budget Category:</label>
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
                                    <label>Budget Date:</label>
                                    <input type="date" name="budgetDate" class="border-2 rounded-2 p-1" required>
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

    <!-- Edit Budget Modal -->
    <div id="editBudgetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Edit Budget</h3>
                <button type="button" onclick="closeEditBudgetModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <form id="editBudgetForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editBudgetId" name="budgetID">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Budget Name:</label>
                    <input type="text" id="editBudgetName" name="budgetName" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Budget Limit:</label>
                    <input type="number" id="editBudgetLimit" name="budgetLimit" class="border-2 rounded-lg p-2" step="0.01" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Budget Category:</label>
                    <select id="editBudgetCategory" name="categoryID" class="border-2 rounded-lg p-2" required>
                        <option value="">Select Category</option>
                        @forelse($categories ?? [] as $category)
                            <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                        @empty
                        <option value="">No categories available</option>
                        @endforelse
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Budget Date:</label>
                    <input type="date" id="editBudgetDate" name="budgetDate" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex gap-2 justify-end mt-4">
                    <button type="button" onclick="closeEditBudgetModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Budget Chart using Chart.js
        const budgetCtx = document.getElementById('budgetChart').getContext('2d');
        const budgetBarCtx = document.getElementById('budgetBarChart').getContext('2d');
        let budgetChart = null;
        let budgetBarChart = null;

        // Budget data storage (replace with actual data from backend)
        let budgetData = {
            total: {{ $totalBudget ?? 0 }},
            used: {{ $usedBudget ?? 0 }},
            categories: @json($categoryBudgets ?? [])
        };

        // Fetch budget data based on filters (category and date range)
        function fetchBudgetByFilters(categoryID = null, startDate = null, endDate = null) {
            const params = new URLSearchParams();
            
            if (categoryID) {
                params.append('categoryID', categoryID);
            }
            if (startDate) {
                params.append('start_date', startDate);
            }
            if (endDate) {
                params.append('end_date', endDate);
            }

            fetch(`/api/budget/fetch-filters?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update budget data with fetched data
                    budgetData.total = data.total;
                    budgetData.used = data.used;
                    
                    if (data.categoryBudgets) {
                        budgetData.categories = data.categoryBudgets;
                    }
                    
                    // Update charts with new data
                    if (categoryID) {
                        // Single category view
                        updateBudgetChart(data.total, data.used, data.categoryName);
                    } else {
                        // All categories view
                        showBudgetOnCharts();
                    }
                    
                    console.log('Budget data fetched successfully:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching budget data:', error);
            });
        }

        function updateBudgetChart(totalBudget, usedBudget, categoryName = '') {
            const remainingBudget = totalBudget - usedBudget;
            const usedPercentage = totalBudget > 0 ? (usedBudget / totalBudget) * 100 : 0;
            const remainingPercentage = totalBudget > 0 ? (remainingBudget / totalBudget) * 100 : 0;

            // Update text values
            document.getElementById('totalBudget').textContent = totalBudget.toFixed(2);
            document.getElementById('usedBudget').textContent = usedBudget.toFixed(2);
            document.getElementById('remainingBudget').textContent = remainingBudget.toFixed(2);
            document.getElementById('percentageUsed').textContent = usedPercentage.toFixed(1);

            // Update progress bar
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const progressText = document.getElementById('progressText');
            
            progressBar.style.width = usedPercentage + '%';
            progressPercentage.textContent = usedPercentage.toFixed(1);
            
            // Change progress bar color based on percentage
            if (usedPercentage <= 50) {
                progressBar.style.backgroundColor = '#10b981'; // green
            } else if (usedPercentage <= 75) {
                progressBar.style.backgroundColor = '#eab308'; // yellow
            } else if (usedPercentage <= 90) {
                progressBar.style.backgroundColor = '#f97316'; // orange
            } else {
                progressBar.style.backgroundColor = '#ef4444'; // red
            }
            
            progressBar.style.height = '100%';
            progressBar.style.display = 'flex';
            progressBar.style.alignItems = 'center';
            progressBar.style.justifyContent = 'flex-end';
            progressBar.style.paddingRight = '8px';
            progressText.textContent = usedPercentage.toFixed(1) + '%';

            // Destroy existing doughnut chart if it exists
            if (budgetChart) {
                budgetChart.destroy();
            }

            // Create new doughnut chart
            budgetChart = new Chart(budgetCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Used', 'Remaining'],
                    datasets: [{
                        data: [usedBudget, remainingBudget],
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(34, 197, 94, 0.8)'
                        ],
                        borderColor: [
                            'rgba(239, 68, 68, 1)',
                            'rgba(34, 197, 94, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'RM' + context.parsed.toFixed(2);
                                    label += ' (' + ((context.parsed / totalBudget) * 100).toFixed(1) + '%)';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // Update bar chart
            updateBarChart(totalBudget, usedBudget, remainingBudget);
        }

        function updateBarChart(totalBudget, usedBudget, remainingBudget) {
            // Destroy existing bar chart if it exists
            if (budgetBarChart) {
                budgetBarChart.destroy();
            }

            // Create new bar chart
            budgetBarChart = new Chart(budgetBarCtx, {
                type: 'bar',
                data: {
                    labels: ['Budget Overview'],
                    datasets: [
                        {
                            label: 'Used',
                            data: [usedBudget],
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Remaining',
                            data: [remainingBudget],
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'RM' + value.toFixed(2);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'RM' + context.parsed.y.toFixed(2);
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateBudgetChartByCategory(categoryId) {
            // Find the category data
            const categoryData = budgetData.categories.find(cat => cat.categoryID == categoryId);
            
            if (categoryData) {
                // Update chart with category data
                updateBudgetChart(categoryData.total, categoryData.used, categoryData.categoryName);
            } else {
                // If no category found, fallback to total budget
                updateBudgetChart(budgetData.total, budgetData.used);
            }
        }

        function updateBudgetByCategory() {
            const categorySelector = document.getElementById('categorySelector');
            const selectedCategoryId = categorySelector.value;

            // Sync with filter dropdown
            document.getElementById('categoryFilter').value = selectedCategoryId;

            // Fetch budget on charts for selected category
            if (selectedCategoryId) {
                fetchBudgetByFilters(selectedCategoryId);
            } else {
                fetchBudgetByFilters();
            }
        }

        // Update budget by time period (daily, monthly, yearly)
        function updateBudgetByTimePeriod() {
            const timePeriod = document.getElementById('timePeriodSelector').value;
            const categorySelector = document.getElementById('categorySelector');
            const selectedCategoryId = categorySelector.value;
            
            // Calculate date range based on time period
            const { startDate, endDate } = getDateRangeByPeriod(timePeriod);
            
            // Fetch budget with category and date range
            if (selectedCategoryId) {
                fetchBudgetByFilters(selectedCategoryId, startDate, endDate);
            } else {
                fetchBudgetByFilters(null, startDate, endDate);
            }
        }

        // Calculate date range based on time period
        function getDateRangeByPeriod(period) {
            const today = new Date();
            let startDate, endDate;
            
            endDate = today.toISOString().split('T')[0]; // Today's date in YYYY-MM-DD
            
            switch(period) {
                case 'daily':
                    // Current day only
                    startDate = endDate;
                    break;
                case 'monthly':
                    // Current month
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1)
                        .toISOString().split('T')[0];
                    break;
                case 'yearly':
                    // Current year
                    startDate = new Date(today.getFullYear(), 0, 1)
                        .toISOString().split('T')[0];
                    break;
                default:
                    startDate = null;
                    endDate = null;
            }
            
            return { startDate, endDate };
        }

        function updateBudgetChartByCategory(categoryId) {
            // Find the category data
            const categoryData = budgetData.categories.find(cat => cat.categoryID == categoryId);

            if (categoryData) {
                // Update chart with category-specific data
                updateBudgetChart(categoryData.total, categoryData.used, categoryData.categoryName);
            } else {
                // If no category found, fallback to total budget
                updateBudgetChart(budgetData.total, budgetData.used);
            }
        }

        function syncCategorySelector() {
            const categoryFilter = document.getElementById('categoryFilter');
            const categorySelector = document.getElementById('categorySelector');
            categorySelector.value = categoryFilter.value;
            showBudgetOnCharts(categorySelector.value);
        }

        function applyFilters(event) {
            event.preventDefault();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const startDate = document.getElementById('globalStartDate').value;
            const endDate = document.getElementById('globalEndDate').value;

            // Sync category selector
            syncCategorySelector();

            // Fetch budget data with filters
            fetchBudgetByFilters(categoryFilter || null, startDate || null, endDate || null);

            // The form submission will handle table filtering on backend
            return true; // Allow form to submit normally
        }

        // Function to show budget on charts
        function showBudgetOnCharts(selectedCategoryId = null) {
            if (selectedCategoryId === null || selectedCategoryId === '') {
                // Show overall budget
                updateBudgetChart(budgetData.total, budgetData.used);
            } else {
                // Show category-specific budget
                updateBudgetChartByCategory(selectedCategoryId);
            }
        }

        // Function to refresh budget charts with new data
        function refreshBudgetCharts(newBudgetData = null) {
            if (newBudgetData) {
                budgetData = newBudgetData;
            }

            const categorySelector = document.getElementById('categorySelector');
            const selectedCategoryId = categorySelector ? categorySelector.value : null;
            showBudgetOnCharts(selectedCategoryId);
        }

        // Initialize charts on page load
        document.addEventListener('DOMContentLoaded', function() {
            showBudgetOnCharts();
        });

        function confirmDelete(budgetId, budgetName) {
            if (confirm(`Are you sure you want to delete the budget "${budgetName}"? This action cannot be undone.`)) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/budget/${budgetId}`;
                
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

        // Edit Budget Modal
        function openEditBudgetModal(budget) {
            document.getElementById('editBudgetId').value = budget.budgetID;
            document.getElementById('editBudgetName').value = budget.budgetName;
            document.getElementById('editBudgetLimit').value = budget.budgetLimit;
            document.getElementById('editBudgetCategory').value = budget.categoryID;
            document.getElementById('editBudgetDate').value = budget.budgetDate;
            document.getElementById('editBudgetForm').action = `/budget/${budget.budgetID}`;
            document.getElementById('editBudgetModal').style.display = 'flex';
        }
        function closeEditBudgetModal() {
            document.getElementById('editBudgetModal').style.display = 'none';
        }
        // Close modal when clicking outside
        document.getElementById('editBudgetModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditBudgetModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.open-edit-budget-modal').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const budget = JSON.parse(this.getAttribute('data-budget'));
            openEditBudgetModal(budget);
        });
    });
});
    </script>
</body>
</html>

