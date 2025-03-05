<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pharmacy Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex justify-center bg-gray-100 dark:bg-gray-800 pt-20">
        <div class="bg-white dark:bg-gray-700 p-6 shadow-lg rounded-lg text-center">
            <div class="mb-4 text-gray-900 dark:text-gray-100">
                {{ __("You're logged in!") }}
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('prescription.view') }}"
                    class="bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-700 px-5 py-2 inline-block text-center">
                    View Prescription
                </a>
                <button class="w-[100px] h-[50px] bg-green-500 text-white font-bold rounded-lg hover:bg-green-700">
                    Button 2
                </button>
            </div>
        </div>
    </div>
</x-app-layout>