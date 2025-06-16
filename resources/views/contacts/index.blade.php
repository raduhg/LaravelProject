<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-200 mb-8">Contact Form Submissions</h2>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($contacts->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400">
                You have no new messages.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($contacts as $contact)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-xl text-gray-900 dark:text-white">{{ $contact->name }}</div>
                                    <a href="mailto:{{ $contact->email }}" class="text-sm text-blue-500 hover:underline">{{ $contact->email }}</a>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->created_at->diffForHumans() }}</div>
                            </div>
                            
                            <p class="mt-4 text-gray-700 dark:text-gray-300">
                                {{ $contact->message }}
                            </p>
                        </div>
                        
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">

                            <div class="flex justify-between items-center">
                                <button onclick="toggleReplyForm({{ $contact->id }})" class="text-sm font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Respond
                                </button>
                                <form method="POST" action="{{ route('contacts.destroy', $contact) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this message?')">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <form id="reply-form-{{ $contact->id }}" method="POST" action="{{ route('contacts.reply', $contact) }}" class="mt-4 hidden">
                                @csrf
                                <textarea 
                                    name="reply_message" 
                                    rows="4"
                                    class="block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    placeholder="Write your reply here..."></textarea>
                                <x-input-error :messages="$errors->get('reply_message')" class="mt-2" />
                                <div class="mt-2 text-right">
                                    <x-primary-button type="submit">Send Reply</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleReplyForm(contactId) {
            const form = document.getElementById(`reply-form-${contactId}`);
            if (form) {
                form.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>