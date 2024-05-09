<div class="container sm:max-w-72 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="text-white text-center text-xl font-semibold my-2">{{ $trip->title }}</div>
    <div class="text-gray-400 m-2">{{ $trip->description }} </div>
    <div class="text-yellow-200 m-2">Difficulty: {{ $trip->difficulty }}</Difficulty:>
    </div>
    <div class="text-white m-2">Date: {{ $trip->date }}</div>
    <div class="text-white m-2">Duration: {{ $trip->duration }}</div>
    <div class="text-white m-2">Total number of people: {{ $trip->number_of_people }}</div>
    <div class="text-white m-2">Avaliable spots: {{ $trip->avaliable_spots }}</div>
    <div class="flex justify-evenly m-2">
        @if(Auth::user()->joinedTrip($trip))
        <form method="POST" action="{{ route('trip.leave', $trip->id)}}">
            @csrf
            <button type="submit" class="bg-red-600 text-white font-semibold px-4 py-2 rounded-xl">Leave</button>
        </form>
        @else
            @if( $trip->avaliable_spots > 0)
                <form method="POST" action="{{ route('trip.join', $trip->id)}}">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white font-semibold px-4 py-2 rounded-xl">Join</button>
                </form>
            @endif
        @endif
        @if (auth()->user()->is_admin)
            <form method="POST" action="{{ route('trip.destroy', $trip->id) }}">
                @csrf
                @method('delete')
                <button type="submit" class="bg-red-500 bg-opacity-85 text-white font-semibold px-4 py-2 rounded-xl"
                    onclick="event.preventDefault(); this.closest('form').submit();">Delete</button>
            </form>
        @endif
    </div>
    <div class="text-gray-400 m-2 text-sm">*To make a reservation for more people contact as via email at
        radus_app@gmail.com</div>
</div>
