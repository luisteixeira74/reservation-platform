<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            My Reservations
        </h2>
    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach ($reservations as $reservation)

                <div class="bg-white p-6 mb-4 shadow rounded">

                    <h3 class="text-lg font-bold">
                        {{ $reservation->event->name }}
                    </h3>

                    <p>
                        Date:
                        {{ $reservation->event->event_date }}
                    </p>

                    <p>
                        Status:
                        {{ $reservation->status }}
                    </p>

                </div>

            @endforeach


        </div>

    </div>

</x-app-layout>