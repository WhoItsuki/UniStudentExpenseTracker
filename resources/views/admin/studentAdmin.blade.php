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

    <title>Student Management</title>
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
                            <a href="#" class="text-red-600 hover:text-red-800"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <br><br>
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Student Management</h2>
                    <p class="text-gray-600 mb-6">Click on any student row to view detailed financial information and charts.</p>

                    <!-- Student List Table -->
                    <div class="bg-gray-50 rounded-lg border-2 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto border-collapse">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Student ID</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Programme</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Faculty</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Email</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">Total Spending</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">Current Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students ?? [] as $student)
                                    <tr class="hover:bg-gray-100 transition-colors cursor-pointer student-row" data-student-id="{{ $student->studentID }}">
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentID }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentFname }} {{ $student->studentLname }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->programme ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentFaculty ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentEmail }}</td>
                                        <td class="px-4 py-3 text-sm text-right text-red-600 border-b font-semibold">RM{{ number_format($student->total_spending ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-right {{ ($student->current_balance ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} border-b font-semibold">RM{{ number_format($student->current_balance ?? 0, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No student data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Student Detail Modal -->
    <div class="modal fade" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentDetailModalLabel">Student Financial Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Student Information -->
                    <div class="mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">Student Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p><strong>Student ID:</strong> <span id="modal-student-id"></span></p>
                                    <p><strong>Name:</strong> <span id="modal-student-name"></span></p>
                                    <p><strong>Email:</strong> <span id="modal-student-email"></span></p>
                                </div>
                                <div>
                                    <p><strong>Programme:</strong> <span id="modal-student-programme"></span></p>
                                    <p><strong>Faculty:</strong> <span id="modal-student-faculty"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">Financial Summary</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <h5 class="text-lg font-semibold text-green-600">Total Income</h5>
                                    <p class="text-2xl font-bold text-green-600" id="modal-total-income">RM0.00</p>
                                </div>
                                <div class="text-center">
                                    <h5 class="text-lg font-semibold text-red-600">Total Expenses</h5>
                                    <p class="text-2xl font-bold text-red-600" id="modal-total-expenses">RM0.00</p>
                                </div>
                                <div class="text-center">
                                    <h5 class="text-lg font-semibold text-blue-600">Current Balance</h5>
                                    <p class="text-2xl font-bold text-blue-600" id="modal-balance">RM0.00</p>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let expenseChart = null;
        let incomeChart = null;

        // Handle student row clicks
        document.querySelectorAll('.student-row').forEach(row => {
            row.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                loadStudentDetails(studentId);
            });
        });

        async function loadStudentDetails(studentId) {
            try {
                const response = await fetch(`/admin/student/${studentId}/details`);
                const data = await response.json();

                // Update student information
                document.getElementById('modal-student-id').textContent = data.student.studentID;
                document.getElementById('modal-student-name').textContent = `${data.student.studentFname} ${data.student.studentLname}`;
                document.getElementById('modal-student-email').textContent = data.student.studentEmail;
                document.getElementById('modal-student-programme').textContent = data.student.programme || 'N/A';
                document.getElementById('modal-student-faculty').textContent = data.student.studentFaculty || 'N/A';

                // Update financial summary
                document.getElementById('modal-total-income').textContent = `RM${parseFloat(data.totalIncome).toFixed(2)}`;
                document.getElementById('modal-total-expenses').textContent = `RM${parseFloat(data.totalExpenses).toFixed(2)}`;
                document.getElementById('modal-balance').textContent = `RM${parseFloat(data.balance).toFixed(2)}`;

                // Update balance color based on value
                const balanceElement = document.getElementById('modal-balance');
                if (data.balance >= 0) {
                    balanceElement.className = 'text-2xl font-bold text-green-600';
                } else {
                    balanceElement.className = 'text-2xl font-bold text-red-600';
                }

                // Create/update expense chart
                if (expenseChart) {
                    expenseChart.destroy();
                }
                const expenseCtx = document.getElementById('expenseChart').getContext('2d');
                expenseChart = new Chart(expenseCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(data.expenseCategories),
                        datasets: [{
                            data: Object.values(data.expenseCategories),
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40',
                                '#FF6384',
                                '#C9CBCF'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
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

                // Create/update income chart
                if (incomeChart) {
                    incomeChart.destroy();
                }
                const incomeCtx = document.getElementById('incomeChart').getContext('2d');
                incomeChart = new Chart(incomeCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(data.incomeCategories),
                        datasets: [{
                            data: Object.values(data.incomeCategories),
                            backgroundColor: [
                                '#4BC0C0',
                                '#FF9F40',
                                '#9966FF',
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#C9CBCF'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
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

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('studentDetailModal'));
                modal.show();

            } catch (error) {
                console.error('Error loading student details:', error);
                alert('Error loading student details. Please try again.');
            }
        }
    </script>
</body>
</html>
