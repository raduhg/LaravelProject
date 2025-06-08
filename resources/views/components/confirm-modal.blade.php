<div id="confirmation-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">


    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-4">


        <div class="flex items-start justify-between">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Confirm Deletion
            </h3>
            <button id="modal-close-button" type="button"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-4">
            <p id="modal-body" class="text-sm text-gray-600 dark:text-gray-300">
                Are you sure you want to proceed? This action cannot be undone.
            </p>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <button id="modal-cancel-button" type="button"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                Cancel
            </button>
            <button id="modal-confirm-button" type="button"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete
            </button>
        </div>
    </div>
</div>
