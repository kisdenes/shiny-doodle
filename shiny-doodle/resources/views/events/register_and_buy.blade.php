<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció és Jegyvásárlás</title>
</head>
<body>
    <h1>Regisztráció és Jegyvásárlás</h1>

    <form action="{{ route('events.registerAndBuy.post', $event->id) }}" method="POST">
        @csrf
        <h2>Regisztráció</h2>
        <label for="name">Név:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Jelszó:</label>
        <input type="password" name="password" id="password" required><br>

        <h2>Jegyvásárlás</h2>
        <label for="quantity">Jegyek száma:</label>
        <input type="number" name="quantity" id="quantity" min="1" max="{{ $event->tickets_available }}" required><br>

        <button type="submit">Regisztráció és Vásárlás</button>
    </form>
</body>
</html>
