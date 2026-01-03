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
                            <li class="m-0 p-0"><a href="/expense" class="text-blue-700 underline hover:text-blue-900 hover:underline">Expenses</a></li>
                            <li class="m-0 p-0"><a href="/budget" class="text-blue-500 no-underline hover:text-blue-900 hover:underline">Budgets</a></li>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses</h2>
                    <p class="text-gray-600">Manage your expenses here.</p>
                    <div class="w-100 h-100 flex flex-row gap-4"> 
                        <div class="w-50 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg" style="height: 500px;"> 
                            <h6>Recent expenses' transactions</h6>
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
                                                @if($category->categoryType === 'expense' || $category->categoryType === 'budget')
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
                                                <button onclick="openEditModal('Lunch', '21-10-2026', 'Food', 'RM21.00', 1)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 text-sm">Edit</button>
                                                <button onclick="confirmDelete(1, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
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
                                        </tr>
                                        <tr>
                                            <td>Lunch</td>
                                            <td>21-10-2026</td>
                                            <td>Food</td>
                                            <td>RM21.00</td>
                                            <td>
                                                <button onclick="openEditModal('Lunch', '21-10-2026', 'Food', 'RM21.00', 3)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 text-sm">Edit</button>
                                                <button onclick="confirmDelete(3, 'Lunch')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 text-sm ml-1">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <h6>Total expenses : RM63.00</h6>
                            
                        </div>

                        <div class="w-50 h-100 border-1 p-2 flex flex-column align-items-center rounded-2 hover:shadow-lg"> 
                            <h6>Add new expense</h6>
                            <form class="flex flex-column w-75 px-5 py-2 gap-4">
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Name:</label>
                                    <input type="text" class="border-2 rounded-2 p-1">
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Amount:</label>
                                    <input type="text" class="border-2 rounded-2 p-1">
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Category:</label>
                                    <select class="border-2 rounded-2 p-1">
                                        <option value="">Entertainment</option>
                                        <option value="">Food</option>
                                        <option value="">Transportation</option>
                                    </select>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <label>Expense's Date:</label>
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

    <!-- Edit Expense Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Edit Expense</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <form id="editExpenseForm" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Expense Name:</label>
                    <input type="text" id="editExpenseName" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Date:</label>
                    <input type="date" id="editExpenseDate" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Category:</label>
                    <select id="editExpenseCategory" class="border-2 rounded-lg p-2" required>
                        <option value="">Entertainment</option>
                        <option value="">Food</option>
                        <option value="">Transportation</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Amount:</label>
                    <input type="text" id="editExpenseAmount" class="border-2 rounded-lg p-2" required>
                </div>
                <div class="flex gap-2 justify-end mt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentExpenseId = null;

        function openEditModal(name, date, category, amount, expenseId) {
            currentExpenseId = expenseId;
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
            currentExpenseId = null;
        }

        function confirmDelete(expenseId, expenseName) {
            if (confirm(`Are you sure you want to delete the expense "${expenseName}"? This action cannot be undone.`)) {
                // Handle delete action here
                console.log('Deleting expense with ID:', expenseId);
                // You can add AJAX call here to delete from backend
                alert('Expense deleted successfully!');
                // Optionally reload the page or remove the row from table
            }
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
            // Handle update action here
            console.log('Updating expense with ID:', currentExpenseId);
            console.log('Updated data:', {
                name: document.getElementById('editExpenseName').value,
                date: document.getElementById('editExpenseDate').value,
                category: document.getElementById('editExpenseCategory').value,
                amount: document.getElementById('editExpenseAmount').value
            });
            // You can add AJAX call here to update in backend
            alert('Expense updated successfully!');
            closeEditModal();
            // Optionally reload the page or update the row in table
        });
    </script>
</body>
</html>

