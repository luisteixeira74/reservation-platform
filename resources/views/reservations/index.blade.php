<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            My Reservations
        </h2>
    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @forelse ($reservations as $reservation)

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

            @empty

                <div class="bg-white p-6 shadow rounded text-center">
                    <p class="text-gray-600">
                        Você ainda não possui reservas.
                    </p>

                    <a href="{{ route('events.index') }}"
                    class="mt-4 inline-block text-blue-600 hover:underline">
                        Ver eventos disponíveis
                    </a>
                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>