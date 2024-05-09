<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('upcoming-trips.store') }}">
            @csrf
            <div class="flex flex-col gap-2">
                <textarea name="title" placeholder="{{ __('Title') }}"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>

                <textarea name="description" placeholder="{{ __('Description') }}"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>

                <textarea name="difficulty" placeholder="{{ __('Difficulty') }}" rows="1"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>

                <input type='date' name="date" placeholder="Start date"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required>

                <textarea name="duration" placeholder="{{ __('Duration') }}" rows="1"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>

                <input type='number' name="number_of_people" placeholder="Total number of people"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required>

                <input type='number' name="avaliable_spots" placeholder="Avaliable spots"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required>
            </div>
            <x-primary-button class="mt-4">{{ __('POST') }}</x-primary-button>
            <a href="{{ route('upcoming-trips.index') }}" class="text-white ms-4">{{ __('Cancel') }}</a>
        </form>
    </div>
</x-app-layout>
