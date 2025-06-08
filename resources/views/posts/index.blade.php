<x-app-layout>
    <div id="flash-message-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 space-y-2">
    </div>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <textarea name="message" placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <input
                class="block w-full mt-4 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                type="file" name="image">
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4" type="submit">{{ __('POST') }}</x-primary-button>
        </form>
    </div>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div id="posts-container" class="flex flex-col gap-4">
                @foreach ($posts as $post)
                <div id="post-{{ $post->id }}" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-2 sm:p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                        <div class="flex justify-between">
                            <h4 class="text-gray-900 dark:text-gray-100">{{ $post->user->name }}</h4>
                            @unless ($post->created_at->eq($post->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                            @if ($post->user->is(auth()->user()) || (auth()->user() && auth()->user()->is_admin))
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
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                        class="delete-form">
                                        @csrf
                                        @method('delete')
                                        <x-dropdown-link href="#" type="submit">
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
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                @include('posts.like-button', ['post' => $post])
                                <span id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                <span class="flex items-center">&#9997; <span id="comment-count-{{ $post->id }}"
                                        class="ml-1">{{ $post->comments()->count() }}</span></span>
                            </div>
                            <div>
                                <p class="self-end text-sm text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
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

    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            if (form) {
                form.classList.toggle('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const authUser = {
                id: {{ auth()->id() ?? 'null' }},
                is_admin: {{ (auth()->user() && auth()->user()->is_admin) ? 'true' : 'false' }}
            };
            const modal = document.getElementById('confirmation-modal');
            const modalConfirmButton = document.getElementById('modal-confirm-button');
            const modalCancelButton = document.getElementById('modal-cancel-button');
            const modalCloseButton = document.getElementById('modal-close-button');
            let formToSubmit = null;

            function showConfirmModal(form) {
                formToSubmit = form;
                if(modal) modal.classList.remove('hidden');
            }

            function hideConfirmModal() {
                formToSubmit = null;
                if(modal) modal.classList.add('hidden');
            }
            
            if(modal) {
                modalConfirmButton.addEventListener('click', () => {
                    if (formToSubmit) {
                        if (formToSubmit.matches('.delete-form')) {
                            deletePost(formToSubmit);
                        } else if (formToSubmit.matches('.delete-comment-form')) {
                            deleteComment(formToSubmit);
                        }
                    }
                    hideConfirmModal();
                });
                modalCancelButton.addEventListener('click', hideConfirmModal);
                modalCloseButton.addEventListener('click', hideConfirmModal);
            }

            function showFlashMessage(message, type = 'success') {
                const container = document.getElementById('flash-message-container');
                if (!container) return;
                const messageDiv = document.createElement('div');
                const baseClasses = 'px-6 py-3 rounded-lg shadow-lg text-white font-semibold';
                const successClasses = 'bg-green-500';
                const errorClasses = 'bg-red-500';
                messageDiv.className = `${baseClasses} ${type === 'success' ? successClasses : errorClasses}`;
                messageDiv.textContent = message;
                container.appendChild(messageDiv);
                setTimeout(() => {
                    messageDiv.style.transition = 'opacity 0.5s ease';
                    messageDiv.style.opacity = '0';
                    setTimeout(() => messageDiv.remove(), 500);
                }, 5000);
            }

            document.body.addEventListener('submit', function(e) {
                const form = e.target.closest('form');
                if (!form) return;

                if (form.matches('#create-post-form')) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(form.action, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }})
                    .then(response => response.json())
                    .then(post => {
                        document.getElementById('posts-container').insertAdjacentHTML('afterbegin', createPostHtml(post));
                        form.reset();
                    });
                }

                if (form.matches('.like-form')) {
                    e.preventDefault();
                    const postId = form.dataset.postId;
                    fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }})
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById(`like-count-${postId}`).textContent = data.likes_count;
                        const likeButton = form.querySelector('button');
                        const likeIcon = form.querySelector('svg');
                        if (data.liked) {
                            likeButton.classList.remove('text-gray-400'); likeButton.classList.add('text-red-500');
                            likeIcon.setAttribute('fill', 'currentColor');
                        } else {
                            likeButton.classList.remove('text-red-500'); likeButton.classList.add('text-gray-400');
                            likeIcon.setAttribute('fill', 'none');
                        }
                    });
                }

                if (form.matches('.comment-form')) {
                    e.preventDefault();
                    const postId = form.dataset.postId;
                    const formData = new FormData(form);
                    fetch(form.action, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }})
                    .then(response => response.json())
                    .then(comment => {
                        const newCommentHtml = createCommentHtml(comment);
                        if (comment.parent_id) {
                            const parentComment = document.getElementById(`comment-${comment.parent_id}`);
                            if (parentComment) {
                                let childrenContainer = parentComment.querySelector('.comment-children-container');
                                if (!childrenContainer) {
                                    childrenContainer = document.createElement('div');
                                    childrenContainer.className = 'mt-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4 comment-children-container';
                                    parentComment.appendChild(childrenContainer);
                                }
                                childrenContainer.insertAdjacentHTML('beforeend', newCommentHtml);
                            }
                        } else {
                            document.getElementById(`comments-section-${postId}`).insertAdjacentHTML('afterbegin', newCommentHtml);
                        }
                        const countEl = document.getElementById(`comment-count-${postId}`);
                        countEl.textContent = parseInt(countEl.textContent || '0') + 1;
                        form.reset();
                        if (comment.parent_id) {
                           form.classList.add('hidden'); 
                        }
                    });
                }

                if (form.matches('.delete-form') || form.matches('.delete-comment-form')) {
                    e.preventDefault();
                    showConfirmModal(form);
                }
            });

            function deletePost(form) {
                const postId = form.closest('[id^="post-"]').id.split('-')[1];
                fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }})
                .then(response => response.json())
                .then(data => {
                    document.getElementById(`post-${postId}`).remove();
                    showFlashMessage(data.message, 'success');
                });
            }

            function deleteComment(form) {
                const commentDiv = form.closest('[id^="comment-"]');
                const postId = form.closest('[id^="post-"]').id.split('-')[1];
                fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }})
                .then(response => {
                    if (response.ok) {
                        commentDiv.remove();
                        const countEl = document.getElementById(`comment-count-${postId}`);
                        countEl.textContent = parseInt(countEl.textContent) - 1;
                    } else { throw new Error('Failed to delete comment.'); }
                });
            }

            function createPostHtml(post) {
                const imageUrl = post.image ? `<img src="/storage/${post.image}" style="max-width:100%; max-height:550px;" class="self-center">` : '';
                let dropdownHtml = '';
                if (authUser.id === post.user.id || authUser.is_admin) {
                    dropdownHtml = `<div x-data="{ open: false }" @@click.outside="open = false" class="relative"> <button @@click="open = !open"> <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" /></svg> </button> <div x-show="open" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg right-0 bg-white ring-1 ring-black ring-opacity-5" style="display: none;"> <div class="py-1"> <a href="/posts/${post.id}/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a> <form method="POST" action="/posts/${post.id}" class="delete-form"> <input type="hidden" name="_token" value="${csrfToken}"> <input type="hidden" name="_method" value="delete"> <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delete</button> </form> </div> </div> </div>`;
                }
                return `<div id="post-${post.id}" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg"> <div class="p-2 sm:p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4"> <div class="flex justify-between"> <h4 class="text-gray-900 dark:text-gray-100">${post.user.name}</h4> ${dropdownHtml} </div> <p class="text-gray-900 dark:text-gray-100">${post.message}</p> ${imageUrl} <div class="flex justify-between items-center"> <div class="flex items-center gap-4"> <form method="POST" action="/posts/${post.id}/like" class="like-form" data-post-id="${post.id}"> <input type="hidden" name="_token" value="${csrfToken}"> <button type="submit" class="flex items-center transition-colors duration-200 ease-in-out text-gray-400 hover:text-red-500"> <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg> </button> </form> <span id="like-count-${post.id}">0</span> <span class="flex items-center">&#9997; <span id="comment-count-${post.id}" class="ml-1">0</span></span> </div> <div><p class="self-end text-sm text-gray-600">just now</p></div> </div> <div class="mt-4"> <form method="POST" action="/posts/${post.id}/comments" class="comment-form" data-post-id="${post.id}"> <input type="hidden" name="_token" value="${csrfToken}"> <textarea name="content" placeholder="Leave a comment" rows="1" class="block w-full bg-gray-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required></textarea> <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">POST</button> </form> <div id="comments-section-${post.id}" class="overflow-auto max-h-[700px] overflow-x-hidden space-y-2 mt-4"></div> </div> </div> </div>`;
            }

            function createCommentHtml(comment) {
                let optionsDropdownHtml = '';
                if (authUser.id === comment.user.id || authUser.is_admin) {
                    optionsDropdownHtml = `<div x-data="{ open: false }" @@click.outside="open = false" class="relative"> <button @@click="open = !open" class="text-gray-400 hover:text-gray-600"> <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" /></svg> </button> <div x-show="open" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg right-0 bg-white ring-1 ring-black ring-opacity-5" style="display: none;"> <div class="py-1"> <form method="POST" action="/comments/${comment.id}" class="delete-comment-form"> <input type="hidden" name="_token" value="${csrfToken}"> <input type="hidden" name="_method" value="DELETE"> <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delete</button> </form> </div> </div> </div>`;
                }
                
                return `
                    <div id="comment-${comment.id}" class="w-full bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-3 ml-${comment.level || 0}">
                        <div class="flex justify-between items-start">
                            <div>
                                <strong class="text-gray-900 dark:text-gray-100">${comment.user.name}</strong>
                                <small class="text-gray-500 dark:text-gray-400 ml-2">just now</small>
                            </div>
                            ${optionsDropdownHtml}
                        </div>
                        <div class="mt-2 text-gray-800 dark:text-gray-200">
                            ${comment.content}
                        </div>
                        <div class="mt-3">
                            <button onclick="toggleReplyForm(${comment.id})" class="text-sm text-blue-500 hover:underline">
                                Reply
                            </button>
                        </div>
                        <form id="reply-form-${comment.id}" method="POST" action="/posts/${comment.post_id}/comments" class="comment-form mt-3 hidden" data-post-id="${comment.post_id}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="parent_id" value="${comment.id}">
                            <textarea name="content" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Write a reply..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest" type="submit">REPLY</button>
                            </div>
                        </form>
                        <div class="mt-4 border-l-2 border-gray-200 dark:border-gray-600 pl-4 comment-children-container">
                        </div>
                    </div>
                `;
            }
        });
    </script>

    <x-confirm-modal />
</x-app-layout>