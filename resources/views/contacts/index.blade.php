<x-app-layout>
    @if (!$contacts->count() >= 1)
        <div class="pt-12 text-yellow-300 text-center text-2xl font-semibold">
            No submissions!
        </div>
    @endif
    <div class=" mx-auto sm:px-6 lg:px-8 flex flex-row flex-wrap gap-6">
        @foreach ($contacts as $contact)
            <div class="pt-12">
                <div class="container sm:max-w-80 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="text-white text-center text-xl font-semibold my-2">From: {{ $contact->name }}</div>

                    <div class="text-yellow-200 m-2">Email: {{ $contact->email }}</div>
                    <div class="text-white m-2">{{ $contact->message }}</div>
                    <div class="flex justify-evenly m-2">
                        <form method="GET" action="{{ route('contacts.response') }}" class="mb-2">
                            <button type="submit"
                                class="bg-green-500 bg-opacity-85 text-white font-semibold px-4 py-2 rounded-xl">Respond</button>
                        </form>
                        <form method="POST" action="{{ route('contacts.destroy', $contact) }}" class="mb-2">
                            @csrf
                            @method('delete')
                            <button type="submit"
                                class="bg-red-500 bg-opacity-85 text-white font-semibold px-4 py-2 rounded-xl"
                                onclick="event.preventDefault(); this.closest('form').submit();">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
