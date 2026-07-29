@extends('layouts.app')

@section('title', 'Events')

@section('content')

<h1>Available Events</h1>

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
</div>

<hr>

@endforeach

@endsection