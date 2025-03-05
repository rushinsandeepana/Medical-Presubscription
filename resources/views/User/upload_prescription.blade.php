<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Upload Prescription (Max 5 images)
                </h3>

                <!-- Upload Prescription Form -->
                <form action="{{ route('User.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Upload Images -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Prescription Images (Max 5)</label>
                        <input type="file" name="prescription_images[]" accept="image/*" multiple required
                            class="border p-2 w-full rounded-lg">
                        <p class="text-sm text-gray-500">You can upload up to 5 images.</p>
                    </div>

                    <!-- Additional Note -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                            Additional Note
                        </label>
                        <textarea name="note" rows="3" class="border p-2 w-full rounded-lg"
                            placeholder="Any additional details..."></textarea>
                    </div>

                    <!-- Delivery Address -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                            Delivery Address
                        </label>
                        <textarea name="delivery_address" rows="2" class="border p-2 w-full rounded-lg"
                            required></textarea>
                    </div>

                    <!-- Delivery Time Slots -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                            Select Delivery Time Slot
                        </label>
                        <select name="delivery_time" class="border p-2 w-full rounded-lg" required>
                            @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Submit Prescription
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>