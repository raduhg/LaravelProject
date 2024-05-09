<div class="overflow-auto max-h-44 overflow-x-hidden">
    @foreach ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
        <div class="border-1 border-gray-200 rounded-md">
            <div class="flex justify-between mr-4">
                <h5 class="font-bold">{{ $comment->user->name }}</h5>
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
                        <x-slot name="content" class="z-50">
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf
                                @method('delete')
                                <x-dropdown-link :href="route('comments.destroy', $comment)"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>
            <p class="text-gray-900 dark:text-gray-100">&emsp;  {{ $comment->content }}</p>
        </div>
    @endforeach
</div>
