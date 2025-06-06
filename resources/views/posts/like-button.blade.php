@php
    $isLiked = auth()->user()->likesPost($post);
@endphp


<form method="POST" action="{{ route('posts.like.toggle', $post) }}" class="like-form" data-post-id="{{ $post->id }}">
    @csrf
    <button type="submit"
        class="flex items-center transition-colors duration-200 ease-in-out 

        {{ $isLiked ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isLiked ? 'currentColor' : 'none' }}"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</form>
