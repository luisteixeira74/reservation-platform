@foreach ($events as $event)

<div>
    <h2>{{ $event->name }}</h2>

    <p>{{ $event->description }}</p>

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

            @if ($event->hasReservationFromUser(auth()->user()))

                <button disabled>
                    Already reserved
                </button>

            @else

                <form method="POST" action="{{ route('reservations.store', $event) }}">
                    @csrf

                    <button type="submit">
                        Reserve
                    </button>
                </form>

            @endif

        @else

            <button disabled>
                Sold out
            </button>

        @endif

    @else
        <a href="{{ route('login') }}">
            Login to reserve
        </a>
    @endauth

</div>

<hr>

@endforeach