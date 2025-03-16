<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">Uploaded Prescription</h2>
    <table class="w-full border border-gray-500 rounded-lg shadow-md">
        <thead>
            <tr class="bg-gray-100 border-b border-gray-500">
                <th class="py-4 px-6 w-24 border border-gray-500">User ID</th>
                <th class="py-4 px-6 w-64 border border-gray-500">Additional Note</th>
                <th class="py-4 px-6 w-48 border border-gray-500">Delivery Address</th>
                <th class="py-4 px-6 w-40 border border-gray-500">Delivery Time Slot</th>
                <th class="py-4 px-6 w-56 border border-gray-500">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptions as $prescription)
            <tr class="border-b border-gray-500">
                <td class="py-4 px-6 w-24 border border-gray-500">{{ $prescription->user_name }}</td>
                <td class="py-4 px-6 w-96 border border-gray-500">{{ $prescription->note ?? 'No note provided' }}</td>
                <td class="py-4 px-6 w-48 border border-gray-500">{{ $prescription->delivery_address }}</td>
                <td class="py-4 px-6 w-30 border border-gray-500">{{ $prescription->delivery_time }}</td>
                <td class="py-4 px-6  border border-gray-500">
                    <a href="{{ route('prepare_quotations.view', ['prescription_id' => $prescription->id]) }}"
                        class="bg-blue-600 text-white font-semibold rounded-md px-4 py-2 hover:bg-blue-700 transition duration-200 shadow-sm">
                        Prepare Quotations
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>