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

    <title>Student Budgets</title>
    <style>
        table th,
        table td {
            padding: 0.5rem 0.75rem;
        }
    </style>
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
                            <li class="m-0 p-0"><a href="/profileStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Profile</a></li>
                            <li class="m-0 p-0"><a href="/dashboardStudent" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-700 underline hover:text-blue-900 hover:underline">Budgets</a></li>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Budgets</h2>
                    <p class="text-gray-600 mb-4">Manage your budgets here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6 class="font-semibold text-lg mb-3">Budget Overview</h6>
                            
                            <!-- Budget Remaining Visual Container -->
                            <div class="w-100 mb-4 p-3 bg-gray-50 rounded-lg border-2">
                                <div class="flex flex-row justify-between items-center mb-3">
                                    <h6 class="font-semibold mb-0">Budget Remaining</h6>
                                    <select id="categorySelector" class="border-2 rounded-2 p-1 text-sm" onchange="updateBudgetByCategory()">
                                        <option value="">All Categories</option>
                                        @forelse($categories ?? [] as $category)
                                            @if($category->categoryType === 'expense' || $category->categoryType === 'budget')
                                                <option value="{{ $category->categoryID }}" data-name="{{ $category->categoryName }}">{{ $category->categoryName }}</option>
                                            @endif
                                        @empty
                                            <option value="">No categories available</option>
                                        @endforelse
                                    </select>
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
                                                @if($category->categoryType === 'Expense' || $category->categoryType === 'budget')
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
                                            <th>Limit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($budgets ?? [] as $budget)
                                        <tr>
                                            <td>{{ $budget->budgetName }}</td>
                                            <td>{{ date('d-m-Y', strtotime($budget->budgetDate)) }}</td>
                                            <td>{{ $budget->category->categoryName ?? 'N/A' }}</td>
                                            <td>RM{{ number_format($budget->budgetLimit, 2) }}</td>
                                            <td>
                                                <button onclick="confirmDelete({{ $budget->budgetID }}, '{{ $budget->budgetName }}')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lunch</td>
                                            <td>21-10-2026</td>
                                            <td>Food</td>
                                            <td>RM21.00</td>
                                            <td>
                                                <button onclick="openEditModal('Lunch', '21-10-2026', 'Food', 'RM21.00', 2)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 text-sm">Edit</button>
                                                <button onclick="confirmDelete(2, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
                                            </td>
                                        </tr
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">No budgets found. Create your first budget below.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6 class="font-semibold text-lg mb-3">Add New Budget</h6>
                            <form action="/budget" method="POST" class="flex flex-column w-75 px-5 py-2 gap-4">
                                @csrf
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Budget Name:</label>
                                    <input type="text" name="budgetName" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Budget Limit:</label>
                                    <input type="number" name="budgetLimit" step="0.01" min="0" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Budget Date:</label>
                                    <input type="date" name="budgetDate" class="border-2 rounded-2 p-1" required>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <label class="font-medium">Category Name:</label>
                                    <select name="categoryID" class="border-2 rounded-2 p-1" required>
                                        <option value="">Select Category</option>
                                        @forelse($categories ?? [] as $category)
                                            @if($category->categoryType === 'expense' || $category->categoryType === 'budget')
                                                <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                                            @endif
                                        @empty
                                            <option value="">No categories available</option>
                                        @endforelse
                                    </select>
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

        function updateBudgetByCategory() {
            const categorySelector = document.getElementById('categorySelector');
            const selectedCategoryId = categorySelector.value;
            const selectedOption = categorySelector.options[categorySelector.selectedIndex];
            
            // Sync with filter dropdown
            document.getElementById('categoryFilter').value = selectedCategoryId;
            
            // Filter budget data by category (replace with actual API call)
            if (selectedCategoryId === '') {
                // Show all categories
                updateBudgetChart(budgetData.total, budgetData.used);
            } else {
                // Filter by selected category (this would be replaced with actual API call)
                // For now, using sample data - replace with actual filtered data from backend
                const categoryName = selectedOption.getAttribute('data-name');
                updateBudgetChart(budgetData.total, budgetData.used, categoryName);
            }
        }

        function syncCategorySelector() {
            const categoryFilter = document.getElementById('categoryFilter');
            const categorySelector = document.getElementById('categorySelector');
            categorySelector.value = categoryFilter.value;
            updateBudgetByCategory();
        }

        function applyFilters(event) {
            event.preventDefault();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const firstRange = document.getElementById('firstRange').value;
            const secondRange = document.getElementById('secondRange').value;
            
            // Sync category selector
            syncCategorySelector();
            
            // Apply filters to chart (replace with actual API call to get filtered data)
            // For now, just updating the chart with current data
            updateBudgetByCategory();
            
            // The form submission will handle table filtering on backend
            // For client-side demo, you could filter the table here too
            return true; // Allow form to submit normally
        }

        // Initialize charts with sample data
        updateBudgetChart(budgetData.total, budgetData.used);

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
    </script>
</body>
</html>

