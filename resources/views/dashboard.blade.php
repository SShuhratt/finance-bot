<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f0f9ff; } /* Light blue background */
    </style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto">
        <header class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-blue-900">Finance Manager Bot</h1>
            <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-2 rounded">
                Total Spent: <span class="font-bold">${{ number_format($transactions->sum('amount'), 2) }}</span>
            </div>
        </header>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Category</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $transaction->date->format('Y-m-d') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $transaction->category }}</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm font-bold text-gray-900">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-gray-600">{{ $transaction->note }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500 italic">No transactions found. Start by sending a message to your bot!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
