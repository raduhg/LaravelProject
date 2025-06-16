@php
    $level = $level ?? 0;
@endphp

<div id="comment-{{ $comment->id }}" class="w-full rounded-lg p-4 mb-3 ml-{{ $level }}">
    
    <div class="flex justify-between items-start">
        <div>
            <strong class="text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</strong>
            <small class="text-gray-500 dark:text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
        
        @if ($comment->user->is(auth()->user()) || (auth()->user() && auth()->user()->is_admin))
            <x-dropdown>
                <x-slot name="trigger">
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="delete-comment-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-600 hover:bg-gray-600 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            Delete
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        @endif
    </div>

    <div class="mt-2 text-gray-800 dark:text-gray-200">
        {{ $comment->content }}
    </div>

    <div class="mt-3">
        <button onclick="toggleReplyForm({{ $comment->id }})" class="text-sm text-blue-500 hover:underline">
            Reply
        </button>
    </div>

    <form id="reply-form-{{ $comment->id }}" method="POST" action="{{ route('posts.comments.store', $comment->post) }}" class="comment-form mt-3 hidden" data-post-id="{{ $comment->post->id }}">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
        <textarea name="content" rows="2" class="block w-full border-gray-300 focus:border-indigo-300 bg-gray-700 text-gray-100 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" placeholder="Write a reply..."></textarea>
        <div class="mt-2 flex justify-end">
            <x-primary-button type="submit">REPLY</x-primary-button>
        </div>
    </form>

    @if ($comment->children->isNotEmpty())
        <div class="mt-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4">
            @foreach ($comment->children()->orderBy('created_at', 'asc')->get() as $child)
                @include('components.comment-item', ['comment' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>