@php
    $level = $level ?? 0;
@endphp

<div class="rounded-md p-3 mb-3 ml-{{ $level }}">
    <div class="flex justify-between items-start">
        <div>
            <h5 class="font-semibold text-white">{{ $comment->user->name }}</h5>
            <p class="text-sm text-white mt-1">{{ $comment->content }}</p>
        </div>

        @if ($comment->user->is(auth()->user()) || auth()->user()->is_admin)
            <x-dropdown>
                <x-slot name="trigger">
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                        @csrf
                        @method('delete')
                        <x-dropdown-link href="#"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Delete') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        @endif
    </div>

    {{-- Buton "Reply" --}}
    <div class="mt-2">
        <button onclick="toggleReplyForm({{ $comment->id }})"
            class="text-sm text-gray-400 hover:underline">Reply</button>
    </div>

    {{-- Formular de răspuns ascuns inițial --}}
    <form id="reply-form-{{ $comment->id }}" method="POST"
        action="{{ route('posts.comments.store', $comment->post) }}" class="mt-2 hidden">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
        <textarea name="content" rows="1" class="block w-full bg-gray-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            placeholder="Write a reply:"></textarea>
        <div class="mt-1 flex justify-end">
            <x-primary-button class="mt-4">{{ __('REPLY') }}</x-primary-button>
        </div>
    </form>

    {{-- Comentarii copil --}}
    @foreach ($comment->children()->orderBy('created_at', 'asc')->get() as $child)
        @include('components.comment-item', ['comment' => $child, 'level' => $level + 4])
    @endforeach
</div>
