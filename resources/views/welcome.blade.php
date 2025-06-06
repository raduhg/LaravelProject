<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Home</title>
</head>

<body class="bg-gray-900">
    <header class="bg-gray-900 bg-opacity-65 fixed w-full px-16 py-4 z-20 top-0 start-0">
        @if (Route::has('login'))
            <nav class="-mx-3 flex flex-1 justify-end">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] text-white hover:text-white/80   ">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] text-white hover:text-white/80">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] text-white hover:text-white/80 ">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div
        class="container min-w-full min-h-screen bg-cover bg-center bg-no-repeat bg-[url('/images/vedere_creasta.jpeg')]">
        <div class="container min-w-full min-h-screen flex flex-row">
            <div class="mx-3 sm:ml-4 sm:basis-2/3 flex flex-col justify-end items-center mb-20">
                <div class="text-white text-center p-4 sm:p-6 rounded-xl">
                    <h1 class="text-4xl sm:text-7xl font-bold">EXPERIENCE <br> THE ROMANIAN MOUNTAINS</h1>
                    <p class="mt-4 text-2xl font-semibold">Being alive and living are two different things. We encurage
                        you to live. <br> <span class="text-2xl font-bold text-orange-500">Explore the mountains.</span>
                    </p>
                </div>
            </div>
        </div>
        <x-image-bottom>0.3</x-image-bottom>
    </div>

    <br>

    <a id="about_section">
    <div class="text-center bg-gray-900 bg-opacity-5 py-1">
        <h2 class="my-10 text-3xl sm:text-5xl text-white font-bold">About Us</h2>
    </div>

    <div
        class="container bg-gray-900 text-white bg-opacity-5 min-w-full flex flex-col lg:flex-row justify-center items-center gap-4">
        <div class="mx-3 md:mx-6 lg:basis-1/3 justify-center items-center text-2xl font-medium">
            <p>We are a group of friends who, for about six years now, have been hiking and camping across the country,
                mostly in the mountains. <br><br>
                We started as a team of five highschoolers who hiked with their dad's equiment. Later, as more of us got
                our driving licence, we started taking more of our friends and later, our siblings on trips.<br><br>

                Four years ago we organised our first expedition as guides. Since then we have
                organised tens of camping trips, became more experienced, and founded this platform so anybody can join
                us.
            <p>
        </div>
        <div class="lg:basis-2/3 m-4">
            <x-image-grid />
        </div>
    </div>

    <div class="bg-gray-900 text-white bg-opacity-5 py-8">
        <div class=" text-center ">
            <p class="text-2xl mx-16 ">If you want to join us on a trip, see other members' posts, or learn more about us and what we do, become a member! </p>
            <div class="my-8">
                <a href="{{ route('register') }}" class=" p-2 sm:py-2  px-10 rounded-3xl bg-orange-500 text-white font-semibold">Become
                    a member!</a>
            </div>
        </div>
    </div>
    </a>

    <br>

    <div
        class="container flex flex-col justify-between min-w-full h-96 lg:h-[500px] bg-cover bg-center bg-no-repeat bg-[url('/images/image1.jpeg')]">
        <x-image-top>0</x-image-top>
        <x-image-bottom>0</x-image-bottom>
    </div>

    <br>

    <a id="contact_section">
    <div class="text-center bg-gray-900 bg-opacity-5 py-1">
        <h2 class="my-10 text-3xl sm:text-5xl text-white font-bold">Contact</h2>
    </div>

    <div class="container bg-gray-900 text-white bg-opacity-5 min-w-full flex flex-col justify-center items-center gap-8 pb-6">
        <p class="text-2xl mx-16 ">You can contact us using this form, even if you are not a member.</p>

        <div class="bg-gray-100 bg-opacity-10 rounded-xl p-8">

            <form class="w-full md:w-72 lg:w-96" action="{{ route('contact.store')}}" method="POST">
                @csrf
                <label for="email-address-icon"
                    class="block my-2 text-md font-semibold text-gray-100 ">Your Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                          </svg>    
                    </div>
                    <input type="text" id="email-address-icon"
                        class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-500 block w-full ps-10 p-2.5"
                        placeholder="Jack Sparrow" name="name">
                </div>

                <label for="email-address-icon"
                    class="block my-2 text-md font-semibold text-gray-100">Your Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                            <path
                                d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                            <path
                                d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                        </svg>
                    </div>
                    <input type="text" id="email-address-icon"
                        class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-500 block w-full ps-10 p-2.5"
                        placeholder="name@mail.com" name="email">
                </div>

                <label for="message" class="block my-2 text-md font-semibold text-gray-100 ">Your message</label>
                <textarea id="message" rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-orange-300 focus:border-orange-500"
                    placeholder="Tell us something..." name="message"></textarea>
                    <br>
                    <button type="submit" class="text-white text-md font-semibold bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-300 rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">Submit</button>

            </form>
            @include('components.success-message')
        </div>
    </div>
    </a>
    <br>

@include('layouts.footer') 
