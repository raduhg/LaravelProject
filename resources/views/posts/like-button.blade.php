@if (Auth::user()->likesPost($post))
    <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="inline-block">
        @csrf
        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();">&#128148;</button>
    </form>
@else
    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline-block">
        @csrf
        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();">&#10084;</button>
    </form>
@endif
