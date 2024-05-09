<div class="flex flex-col gap-1">
    <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
        @csrf
        <textarea name="content" placeholder="{{ __('Leave a comment') }}" rows="1"
            class="block w-full bg-gray-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
        <x-primary-button class="mt-4">{{ __('POST') }}</x-primary-button>
    </form>
</div>