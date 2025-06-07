@if (session()->has('error'))
    <div id="error-alert"
        class="flex items-center p-4 my-6 text-red-700 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-red-500"
        role="alert">
        <div class="ms-3 text-md font-medium">
            {{ session('error') }}
        </div>
        <button type="button" id="close-alert"
            class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-red-700 rounded-lg focus:ring-2 focus:ring-red-500 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-500 dark:hover:bg-gray-700"
            data-dismiss-target="#error-alert" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
@endif
<script>
    document.getElementById('close-alert').addEventListener('click', function() {
        document.getElementById('error-alert').style.display = 'none';
    });
</script>
