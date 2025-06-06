@php
    // We check if the user has liked the post to set the initial state of the button.
    // This uses the same logic from your original file.
    $isLiked = Auth::user()->likesPost($post);
@endphp

{{-- 
  This is now a SINGLE form that points to our toggle route.
  - 'like-form' class lets our main JavaScript target it.
  - 'data-post-id' tells the JavaScript which post is being liked.
--}}
<form method="POST" action="{{ route('posts.like.toggle', $post) }}" class="like-form" data-post-id="{{ $post->id }}">
    @csrf
    <button type="submit" class="flex items-center transition-colors duration-200 ease-in-out 
        {{-- On page load, we set the color: red if liked, gray if not. --}}
        {{ $isLiked ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">
        
        {{-- 
            This SVG icon will be a filled heart if liked, and an outline if not.
            The 'fill' attribute is controlled by the $isLiked variable.
        --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</form>