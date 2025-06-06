<x-app-layout>
    {{-- Add a meta tag for the CSRF token in your main layout (e.g., app.blade.php) if not already present --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Give the form an ID for easy selection in JavaScript --}}
        <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <input
                class="block w-full mt-4 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                type="file" name="image">
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('POST') }}</x-primary-button>
        </form>
    </div>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            {{-- Give the container an ID to easily prepend new posts --}}
            <div id="posts-container" class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    {{-- Give each post container a unique ID --}}
                    <div id="post-{{ $post->id }}" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-2 sm:p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                            <div class="flex justify-between">
                                <h4 class="text-gray-900 dark:text-gray-100">{{ $post->user->name }}</h4>
                                @unless ($post->created_at->eq($post->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                                @if ($post->user->is(auth()->user()) || auth()->user()->is_admin)
                                    <x-dropdown>
                                        <x-slot name="trigger">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('posts.edit', $post)">
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="delete-form">
                                                @csrf
                                                @method('delete')
                                                <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                                    {{ __('Delete') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @endif
                            </div>
                            <p class="text-gray-900 dark:text-gray-100">{{ $post->message }}</p>
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" style="max-width:100%; max-height:550px;" class="self-center">
                            @endif
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                     {{-- LIKE BUTTON AND COUNT --}}
                                    @include('posts.like-button', ['post' => $post]) {{-- Pass post to the include --}}
                                    <span id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                    
                                    {{-- COMMENT ICON AND COUNT --}}
                                    <span class="flex items-center">
                                        &#9997; <span id="comment-count-{{ $post->id }}" class="ml-1">{{ $post->comments()->count() }}</span>
                                    </span>
                                </div>
                                <div>
                                    <p class="self-end text-sm text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            {{-- COMMENTS --}}
                            <div class="mt-4">
                               @include('components.comment-input', ['post' => $post])
                               @include('components.comments-box', ['post' => $post])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- ======================= JAVASCRIPT SECTION ======================= --}}
    {{-- ================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // --- CREATE POST ---
            const createPostForm = document.getElementById('create-post-form');
            if (createPostForm) {
                createPostForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    
                    fetch("{{ route('posts.store') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(post => {
                        // Create the HTML for the new post
                        const newPostHtml = createPostHtml(post);
                        // Add the new post to the top of the container
                        document.getElementById('posts-container').insertAdjacentHTML('afterbegin', newPostHtml);
                        // Reset the form fields
                        createPostForm.reset();
                    })
                    .catch(error => {
                        console.error('Error creating post:', error);
                        alert('Something went wrong. Please try again.');
                    });
                });
            }

            // --- LIKE, COMMENT, DELETE (Event Delegation) ---
            document.getElementById('posts-container').addEventListener('submit', function(e) {
                
                // --- LIKE/UNLIKE A POST ---
                if (e.target.matches('.like-form')) {
                    e.preventDefault();
                    const form = e.target;
                    const postId = form.dataset.postId;
                    
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update the like count
                        document.getElementById(`like-count-${postId}`).textContent = data.likes_count;
                        // Toggle the like button's appearance (e.g., change color)
                        const likeButton = form.querySelector('button');
                        likeButton.classList.toggle('text-blue-500', data.liked); // Assumes your controller sends back a 'liked' boolean
                        likeButton.classList.toggle('text-gray-400', !data.liked);
                    })
                    .catch(error => console.error('Like error:', error));
                }

                // --- ADD A COMMENT ---
                if (e.target.matches('.comment-form')) {
                    e.preventDefault();
                    const form = e.target;
                    const postId = form.dataset.postId;
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(comment => {
                        // Append the new comment
                        const commentHtml = createCommentHtml(comment);
                        document.getElementById(`comments-section-${postId}`).insertAdjacentHTML('beforeend', commentHtml);
                        // Update comment count
                        const countEl = document.getElementById(`comment-count-${postId}`);
                        countEl.textContent = parseInt(countEl.textContent) + 1;
                        // Clear the input
                        form.reset();
                    })
                    .catch(error => console.error('Comment error:', error));
                }

                // --- DELETE A POST ---
                if (e.target.matches('.delete-form')) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this post?')) {
                        return;
                    }

                    const form = e.target;
                    const postId = form.closest('.bg-white').id.split('-')[1];

                     fetch(form.action, {
                        method: 'POST', // HTML forms don't support DELETE, so we use POST with @method('delete')
                        body: new FormData(form), // This sends the _method=DELETE field
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.getElementById(`post-${postId}`).remove();
                        } else {
                            throw new Error('Failed to delete post.');
                        }
                    })
                    .catch(error => console.error('Delete error:', error));
                }
            });


            // --- HTML CREATION HELPER FUNCTIONS ---

            function createPostHtml(post) {
                // This is a simplified template. You should expand it to match your exact post structure,
                // including the dropdown, image, like button, comment form etc.
                const imageUrl = post.image ? `<img src="/storage/${post.image}" style="max-width:100%; max-height:550px;" class="self-center">` : '';
                
                // NOTE: This is a basic representation. You would need to reconstruct all the complex parts,
                // including the dropdown, like button forms, comment forms, etc. for the new post.
                return `
                    <div id="post-${post.id}" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-2 sm:p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                            <div class="flex justify-between">
                                <h4 class="text-gray-900 dark:text-gray-100">${post.user.name}</h4>
                                {{-- Dropdown would need to be dynamically generated here --}}
                            </div>
                            <p class="text-gray-900 dark:text-gray-100">${post.message}</p>
                            ${imageUrl}
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    {{-- Like button form would go here --}}
                                    <span id="like-count-${post.id}">0</span>
                                    <span class="flex items-center">&#9997; <span id="comment-count-${post.id}" class="ml-1">0</span></span>
                                </div>
                                <div>
                                    <p class="self-end text-sm text-gray-600">just now</p>
                                </div>
                            </div>
                            {{-- Comment input and box would go here --}}
                        </div>
                    </div>
                `;
            }

            function createCommentHtml(comment) {
                // Adjust to match your comment's HTML structure
                return `
                    <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p><strong>${comment.user.name}</strong>: ${comment.body}</p>
                        <small class="text-gray-500">just now</small>
                    </div>
                `;
            }

        });
    </script>
</x-app-layout>