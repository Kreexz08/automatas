<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analizador Léxico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        button {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .alert {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        @if (isset($errores) && count($errores) > 0)
            <div class="alert">
                <ul>
                    @foreach ($errores as $error)
                        <li>Error {{ $error['linea'] }}: {{ $error['error'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Analizador Léxico</h1>
        <form method="POST" action="{{ route('analizar') }}">
            @csrf
            <textarea name="codigo" rows="10" cols="50" placeholder="Ingrese su código aquí">{{$codigo??''}}</textarea>
            <br>
            <button type="submit">Analizar</button>
        </form>
    </div>
</body>
</html>
