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

    <title>Student Dashboard</title>
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
                            <li class="m-0 p-0"><a href="" class="text-blue-600 no-underline hover:text-blue-800 hover:underline">Profile</a></li>
                            <li class="m-0 p-0"><a href="" class="text-blue-600 no-underline hover:text-blue-800 hover:underline">Expenses</a></li>
                            <li class="m-0 p-0"><a href="" class="text-blue-600 no-underline hover:text-blue-800 hover:underline">Budgets</a></li>
                            <li class="m-0 p-0"><a href="" class="text-blue-600 no-underline hover:text-blue-800 hover:underline">Income</a></li>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h2>
                    <p class="text-gray-600">Welcome to your expense tracking dashboard. This is where you can manage your financials.</p>

                    <div class="m-2 flex items-center gap-5">
                        <div class="w-50 h-100 border-1 rounded-2 hover:shadow-lg p-2">
                            <h1 class="text-center text-xl">Stats</h1>
                        </div>
                        <div class="w-50 flex flex-col gap-5">
                            <div class="h-25 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col">
                                <h1 class="text-center text-xl">Expense</h1>
                                <canvas id="expenseChart" style="width:100%;max-width:600px"></canvas>
                            </div>
                            <div class="h-25 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col">
                                <h1 class="text-center text-xl">Income</h1>
                            </div>
                            <div class="h-25 border-1 rounded-2 hover:shadow-lg p-2 flex flex-col">
                                <h1 class="text-center text-xl">Budget</h1>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            
        </main>
    </div>
</body>
</html>

