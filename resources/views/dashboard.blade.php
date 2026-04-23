<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Bot Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #f0f9ff; }
    </style>
</head>
<body class="p-6">
    <div class="max-w-6xl mx-auto">
        <header class="mb-8 text-center">
            <h2 class="text-xl font-semibold text-gray-600">Total Net Balance</h2>
            <h1 class="text-5xl font-bold text-emerald-600 mt-2">
                {{ number_format($totalBalance) }} <span class="text-2xl">UZS</span>
            </h1>
        </header>

        <div class="flex justify-center space-x-4 mb-8">
            <a href="?period=today" class="px-4 py-2 rounded-lg {{ $period === 'today' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600' }} shadow-sm">Today</a>
            <a href="?period=weekly" class="px-4 py-2 rounded-lg {{ $period === 'weekly' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600' }} shadow-sm">This Week</a>
            <a href="?period=monthly" class="px-4 py-2 rounded-lg {{ $period === 'monthly' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600' }} shadow-sm">This Month</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Income Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-emerald-500">
                <h3 class="text-xl font-bold text-emerald-700 mb-4 flex items-center">
                    <span class="mr-2">📥</span> Income
                </h3>
                <div class="space-y-3">
                    @forelse($stats['income'] as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->category }}</p>
                                <p class="text-xs text-gray-400">{{ $item->created_at->format('M d, H:i') }}</p>
                            </div>
                            <p class="text-emerald-600 font-bold">+{{ number_format($item->amount) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No income recorded.</p>
                    @endforelse
                </div>
            </div>

            <!-- Expense Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-400">
                <h3 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                    <span class="mr-2">📤</span> Expenses
                </h3>
                <div class="space-y-3">
                    @forelse($stats['expense'] as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->category }}</p>
                                <p class="text-xs text-gray-400">{{ $item->created_at->format('M d, H:i') }}</p>
                            </div>
                            <p class="text-red-500 font-bold">-{{ number_format($item->amount) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No expenses recorded.</p>
                    @endforelse
                </div>
            </div>

            <!-- Debts Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-orange-400">
                <h3 class="text-xl font-bold text-orange-600 mb-4 flex items-center">
                    <span class="mr-2">💸</span> Debts
                </h3>
                <div class="space-y-3">
                    @forelse($stats['debt'] as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->note }}</p>
                                <p class="text-xs text-gray-400">{{ $item->created_at->format('M d, H:i') }}</p>
                            </div>
                            <p class="text-orange-500 font-bold">{{ number_format($item->amount) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No debts recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
