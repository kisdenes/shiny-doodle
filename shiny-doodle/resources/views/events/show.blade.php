<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }}</title>
</head>
<body>
    <h1>{{ $event->name }}</h1>
    <p><strong>Dátum:</strong> {{ $event->date }}</p>
    <p><strong>Helyszín:</strong> {{ $event->location }}</p>
    <p><strong>Leírás:</strong> {{ $event->description }}</p>
    <p><strong>Jegyár:</strong> {{ $event->price }} Ft</p>
    <p><strong>Elérhető jegyek:</strong> {{ $event->tickets_available }}</p>

    <form action="{{ route('events.buy', $event->id) }}" method="POST">
        @csrf
        <label for="quantity">Jegyek száma:</label>
        <input type="number" name="quantity" id="quantity" min="1" max="{{ $event->tickets_available }}" required>
        <button type="submit">Vásárlás</button>
    </form>

    <a href="{{ route('events.index') }}">Vissza az eseményekhez</a>
    // Események listája nézetben
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

</body>
</html>
