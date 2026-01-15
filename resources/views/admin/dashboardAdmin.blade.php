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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>
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
                            <li class="m-0 p-0"><a href="/dashboardAdmin" class="text-blue-700 underline hover:text-blue-900 hover:underline"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li class="m-0 p-0"><a href="/studentAdmin" class="text-blue-500 no-underline hover:text-blue-900 hover:underline"><i class="fas fa-users mr-2"></i>Students</a></li>
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
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 flex flex-wrap">
            <div class="px-4 py-6 sm:px-0 w-full">
                <!-- Messages -->
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h2>
                    <p class="text-gray-600 mb-6">Welcome back, {{ $admin->adminFName }} {{ $admin->adminLName }}! This is your admin dashboard where you can manage students and financial data.</p>

                    

                    <!-- Global Filters -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Global Filters</h3>
                        <div class="bg-gray-50 rounded-lg border-2 p-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Filter All Data Below</h4>
                            <form id="globalFilter" class="flex flex-wrap gap-4 items-end" onsubmit="submitGlobalFilter(event)">
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
                                    <select id="globalTimeFrame" name="time_frame" class="border-2 rounded-lg p-2 text-sm">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly" selected>Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                                        Apply Filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Section 1: Top 5 Students by Spending (Filtered) -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Top 5 Students by Spending</h3>
                        <p class="text-sm text-gray-600 mb-4">Shows the highest spending students based on applied filters</p>
                        <div class="bg-gray-50 rounded-lg border-2 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table id="student-overview-table" class="w-full table-auto border-collapse">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Student ID</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Name</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Programme</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Faculty</th>
                                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">Total Spending</th>
                                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">Current Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topStudents ?? [] as $student)
                                        <tr class="hover:bg-gray-100 transition-colors">
                                            <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentID }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentFname }} {{ $student->studentLname }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->programme ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $student->studentFaculty ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-red-600 border-b font-semibold">RM{{ number_format($student->total_spending ?? 0, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-right {{ ($student->current_balance ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} border-b font-semibold">RM{{ number_format($student->current_balance ?? 0, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No student data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Spending Analytics (All Students) -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Spending Analytics (All Students)</h3>
                        <p class="text-sm text-gray-600 mb-4">Shows spending statistics across all students in the system</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Average Spending -->
                            <div class="bg-blue-50 rounded-lg border-2 p-4">
                                <h4 class="text-lg font-semibold text-blue-800 mb-3">Average Spending</h4>
                                <div class="text-2xl font-bold text-blue-600 mb-2">RM{{ number_format($analytics['average'] ?? 0, 2) }}</div>
                                @if($analytics['average_student'] ?? null)
                                <div class="text-sm text-gray-600">
                                    <p><strong>Student:</strong> {{ $analytics['average_student']->studentFname }} {{ $analytics['average_student']->studentLname }}</p>
                                    <p><strong>ID:</strong> {{ $analytics['average_student']->studentID }}</p>
                                    <p><strong>Programme:</strong> {{ $analytics['average_student']->programme ?? 'N/A' }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Highest Spending -->
                            <div class="bg-red-50 rounded-lg border-2 p-4">
                                <h4 class="text-lg font-semibold text-red-800 mb-3">Highest Spending</h4>
                                <div class="text-2xl font-bold text-red-600 mb-2">RM{{ number_format($analytics['highest'] ?? 0, 2) }}</div>
                                @if($analytics['highest_student'] ?? null)
                                <div class="text-sm text-gray-600">
                                    <p><strong>Student:</strong> {{ $analytics['highest_student']->studentFname }} {{ $analytics['highest_student']->studentLname }}</p>
                                    <p><strong>ID:</strong> {{ $analytics['highest_student']->studentID }}</p>
                                    <p><strong>Programme:</strong> {{ $analytics['highest_student']->programme ?? 'N/A' }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Lowest Spending -->
                            <div class="bg-green-50 rounded-lg border-2 p-4">
                                <h4 class="text-lg font-semibold text-green-800 mb-3">Lowest Spending</h4>
                                <div class="text-2xl font-bold text-green-600 mb-2">RM{{ number_format($analytics['lowest'] ?? 0, 2) }}</div>
                                @if($analytics['lowest_student'] ?? null)
                                <div class="text-sm text-gray-600">
                                    <p><strong>Student:</strong> {{ $analytics['lowest_student']->studentFname }} {{ $analytics['lowest_student']->studentLname }}</p>
                                    <p><strong>ID:</strong> {{ $analytics['lowest_student']->studentID }}</p>
                                    <p><strong>Programme:</strong> {{ $analytics['lowest_student']->programme ?? 'N/A' }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        // Set default dates for global filter (current month)
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            document.getElementById('globalStartDate').value = startOfMonth.toISOString().split('T')[0];
            document.getElementById('globalEndDate').value = endOfMonth.toISOString().split('T')[0];
        });

        function submitGlobalFilter(event) {
            event.preventDefault();

            const formData = new FormData(document.getElementById('globalFilter'));
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Show loading state
            const submitButton = document.querySelector('#globalFilter button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Applying Filters...';
            submitButton.disabled = true;

            fetch('/dashboardAdmin?' + params.toString(), {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received filtered data:', data);
                // Update both the student overview table and analytics cards with new data
                updateStudentOverview(data.students);
                updateAnalyticsCards(data.analytics);

                // Show success message
                showMessage('Filters applied successfully!', 'success');
            })
            .catch(error => {
                console.error('Error fetching filtered data:', error);
                showMessage('Error applying filters. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });

            return false;
        }

        function updateStudentOverview(students) {
            const tbody = document.querySelector('#student-overview-table tbody');
            tbody.innerHTML = '';

            if (!students || students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No student data available for selected filters</td></tr>';
                return;
            }

            students.forEach(student => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-100 transition-colors';

                const balanceClass = (student.current_balance || 0) >= 0 ? 'text-green-600' : 'text-red-600';

                row.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 border-b">${student.studentID || 'N/A'}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 border-b">${student.studentFname || ''} ${student.studentLname || ''}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 border-b">${student.programme || 'N/A'}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 border-b">${student.studentFaculty || 'N/A'}</td>
                    <td class="px-4 py-3 text-sm text-right text-red-600 border-b font-semibold">RM${parseFloat(student.total_spending || 0).toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm text-right ${balanceClass} border-b font-semibold">RM${parseFloat(student.current_balance || 0).toFixed(2)}</td>
                `;

                tbody.appendChild(row);
            });
        }

        function updateAnalyticsCards(analytics) {
            // Update average spending
            const averageElement = document.querySelector('.bg-blue-50 .text-2xl');
            if (averageElement && analytics.average !== undefined) {
                averageElement.textContent = 'RM' + parseFloat(analytics.average).toFixed(2);
            }

            // Update highest spending
            const highestElement = document.querySelector('.bg-red-50 .text-2xl');
            if (highestElement && analytics.highest !== undefined) {
                highestElement.textContent = 'RM' + parseFloat(analytics.highest).toFixed(2);
            }

            // Update lowest spending
            const lowestElement = document.querySelector('.bg-green-50 .text-2xl');
            if (lowestElement && analytics.lowest !== undefined) {
                lowestElement.textContent = 'RM' + parseFloat(analytics.lowest).toFixed(2);
            }

            // Update student information in analytics cards
            updateAnalyticsStudentInfo('.bg-blue-50', analytics.average_student);
            updateAnalyticsStudentInfo('.bg-red-50', analytics.highest_student);
            updateAnalyticsStudentInfo('.bg-green-50', analytics.lowest_student);
        }

        function updateAnalyticsStudentInfo(cardSelector, student) {
            const card = document.querySelector(cardSelector);
            if (!card || !student) return;

            const studentInfoDiv = card.querySelector('.text-sm.text-gray-600');
            if (studentInfoDiv) {
                studentInfoDiv.innerHTML = `
                    <p><strong>Student:</strong> ${student.studentFname || ''} ${student.studentLname || ''}</p>
                    <p><strong>ID:</strong> ${student.studentID || 'N/A'}</p>
                    <p><strong>Programme:</strong> ${student.programme || 'N/A'}</p>
                `;
            }
        }

        function showMessage(message, type = 'info') {
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.alert-message');
            existingMessages.forEach(msg => msg.remove());

            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert-message px-4 py-3 rounded mb-4 ${
                type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                'bg-blue-100 border border-blue-400 text-blue-700'
            }`;
            messageDiv.textContent = message;

            // Insert at the top of the main content
            const main = document.querySelector('main');
            if (main) {
                main.insertBefore(messageDiv, main.firstChild);
            }

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>
