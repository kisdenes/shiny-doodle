<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Események</title>
</head>
<body>
    <h1>Elérhető Események</h1>
    <ul>
        @foreach($events as $event)
            <li>
                <strong>{{ $event->name }}</strong><br>
                Dátum: {{ $event->date }}<br>
                Helyszín: {{ $event->location }}<br>
                <a href="{{ route('events.show', $event->id) }}">Részletek</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
