<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">Uploaded Prescription</h2>
    <table class="w-full border border-gray-200 rounded-lg shadow-md overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="py-3 px-4 text-left border-b">User Name</th>
                <th class="py-3 px-4 text-left border-b">Additional Note</th>
                <th class="py-3 px-4 text-left border-b">Delivery Address</th>
                <th class="py-3 px-4 text-left border-b">Delivery Time Slot</th>
                <th class="py-3 px-4 text-left border-b">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($prescriptions as $prescription)
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="py-4 px-6">{{ $prescription->id }}</td>
                <td class="py-4 px-6">{{ $prescription->note ?? 'No note provided' }}</td>
                <td class="py-4 px-6">{{ $prescription->delivery_address }}</td>
                <td class="py-4 px-6">{{ $prescription->delivery_time }}</td>
                <td class="py-4 px-6">
                    <a href="#"
                        class="bg-blue-600 text-white font-semibold rounded-md px-4 py-2 hover:bg-blue-700 transition duration-200 shadow-sm">
                        prepare quotations
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>