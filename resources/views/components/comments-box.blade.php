<div id="comments-section-{{ $post->id }}" class="overflow-auto max-h-[700px] overflow-x-hidden space-y-2">
    @foreach ($post->comments()->whereNull('parent_id')->orderBy('created_at', 'desc')->get() as $comment)
        @include('components.comment-item', ['comment' => $comment])
    @endforeach
</div>