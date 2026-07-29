<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Events
        </h2>
    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach ($events as $event)

                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">

                    <h2 class="text-xl font-bold">
                        {{ $event->name }}
                    </h2>

                    <p>
                        {{ $event->description }}
                    </p>

                    <p>
                        Date:
                        {{ $event->event_date->format('d/m/Y H:i') }}
                    </p>

                    <p>
                        Available seats:
                        {{ $event->available_seats }}
                    </p>


                    @auth

                        @if ($event->available_seats > 0)

                            @if ($event->already_reserved)

                               <button 
                                    disabled
                                    class="inline-flex items-center px-4 py-2 bg-gray-400 rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed"
                                >
                                    Já reservou
                                </button>

                            @else

                                <form method="POST" action="{{ route('reservations.store', $event) }}">
                                    @csrf

                                    <button 
                                        type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        Reserve
                                    </button>
                                </form>

                            @endif

                        @else

                            <button 
                                    disabled
                                    class="inline-flex items-center px-4 py-2 bg-gray-400 rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed"
                                >
                                Esgotado
                            </button>

                        @endif

                    @else

                        <a 
                            href="{{ route('login') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
                        >
                            Login to reserve
                        </a>

                    @endauth

                </div>

            @endforeach

        </div>

    </div>

</x-app-layout>