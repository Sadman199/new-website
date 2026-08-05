<div id="trading" class="p-6 border-b border-gray-200 bg-gray-50">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
        </svg>
        Trading Conditions
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feature</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $broker1->name }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $broker2->name }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Minimum Deposit</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($broker1->minimum_deposit, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($broker2->minimum_deposit, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Average Spreads</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker1->spreads }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker2->spreads }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Maximum Leverage</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker1->leverage }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker2->leverage }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Commission</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker1->commission ?? 'None' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $broker2->commission ?? 'None' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
