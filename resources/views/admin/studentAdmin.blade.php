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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Student Management</h2>
                    <p class="text-gray-600 mb-6">Welcome, {{ $admin->adminFName }} {{ $admin->adminLName }}! Click on any student row to view detailed financial information and charts.</p>


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
                                    <tr class="hover:bg-blue-50 hover:shadow-md transition-all duration-200 cursor-pointer border-b border-gray-200">
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">
                                            <a href="{{ route('admin.student.detail', $student->studentID) }}" class="text-blue-600 hover:text-blue-800">{{ $student->studentID }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-b">
                                            <a href="{{ route('admin.student.detail', $student->studentID) }}" class="text-blue-600 hover:text-blue-800">{{ $student->studentFname }} {{ $student->studentLname }}</a>
                                        </td>
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


</body>
</html>
