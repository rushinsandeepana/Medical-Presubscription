<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">Uploaded Prescription</h2>


    @foreach($prescriptions as $prescription)
    <div class="mb-6 border-b pb-4">
        <h3 class="text-lg font-semibold">Prescription #{{ $prescription->id }}</h3>

        @if($prescription->images)
        <div class="grid grid-cols-3 gap-4 mt-2">
            @foreach(json_decode($prescription->images, true) as $image)
            <div class="border rounded-lg p-2">
                <img src="{{ asset('storage/' . $image) }}" class="w-full h-auto rounded-lg" alt="Prescription Image">
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500">No images uploaded.</p>
        @endif

        <div class="mt-2">
            <strong>Note:</strong> {{ $prescription->note ?? 'No note provided' }} <br>
            <strong>Delivery Address:</strong> {{ $prescription->delivery_address }} <br>
            <strong>Delivery Time:</strong> {{ $prescription->delivery_time }}
        </div>
    </div>
    @endforeach
</x-app-layout>