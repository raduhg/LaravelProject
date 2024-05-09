<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <textarea name="message" placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <input
                class="block text-white border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-4"
                type="file" name="image">
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('POST') }}</x-primary-button>
        </form>
        @include('components.success-message')
    </div>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-2 sm:p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                            <div class="flex justify-between">
                                <h4 class="text-gray-900 dark:text-gray-100">{{ $post->user->name }}</h4>
                                @unless ($post->created_at->eq($post->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                                @if (($post->user->is(auth()->user())) || auth()->user()->is_admin)
                                    <x-dropdown>
                                        <x-slot name="trigger">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('posts.edit', $post)">
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                                @csrf
                                                @method('delete')
                                                <x-dropdown-link 
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                    {{ __('Delete') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @endif
                            </div>
                            <p class="text-gray-900 dark:text-gray-100">{{ $post->message }}</p>
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}"
                                    style="max-width:100%; max-height:550px;" class="self-center">
                            @endif
                            <div class="flex justify-between">
                                <div><p>@include('posts.like-button'){{ $post->likes()->count() }} &emsp; &#9997;{{ $post->comments()->count() }}</p></div>
                                <div><p class="self-end">{{ $post->created_at }}</p> </div>
                            </div>
                            @include('components.comment-input')
                           @include('components.comments-box')
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
