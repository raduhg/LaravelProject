<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upcoming trips') }}
        </h2>
    </x-slot>
    @if (auth()->user()->is_admin)
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <a href="{{ route('upcoming-trips.create') }}" class="text-white bg-gray-700 p-2 rounded-md">Create a new trip</a>
            @include('components.success-message')
            @include('components.error-message')
        </div>
    @endif
    @if ($upcomingTrips->isEmpty())
        <div class="pt-12 text-yellow-300 text-center text-2xl font-semibold">
            No upcoming trips!
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-row flex-wrap gap-5">
            @foreach ($upcomingTrips as $trip)
                @include('upcoming-trips.trip-card', ['trip' => $trip])
            @endforeach
        </div>
    </div>
</x-app-layout>
