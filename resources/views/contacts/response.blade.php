<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-white text-center text-2xl mb-8"> Email response</h1>
        <form>
            @csrf
            <div class="flex flex-col gap-2">
                <textarea name="subject" placeholder="{{ __('Subject') }}"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>

                <textarea name="content" placeholder="{{ __('Content') }}"
                    class="block w-full text-white bg-gray-700 bg-opacity-55 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required></textarea>
            </div>
            <x-primary-button class="mt-4">{{ __('Respond') }}</x-primary-button>
            <a href="{{ route('contacts.index') }}" class="text-white ms-4">{{ __('Cancel') }}</a>
        </form>
    </div>
</x-app-layout>