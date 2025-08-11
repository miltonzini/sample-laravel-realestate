<!DOCTYPE html>
<html>
<head>
    <title>Nuevo una consulta sobre un lote/terreno</title>
    <style>
        p {margin-top: 0; margin-bottom: 2px;}
        h2 {margin-bottom: 5px;}
    </style>
</head>
<body>
    <h2>Mensaje</h2>
    <p>{{ $message }}</p>    

    <h2>Datos del usuario</h2>
    <p><strong>Nombre:</strong> {{ $fullName }}</p>
    <p><strong>Teléfono:</strong> {{ $telephone }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    
    <h2>Detalles del Lote/Terreno</h2>
    <p><strong>Título:</strong> {{ $lotTitle }}</p>
    <p><strong>ID:</strong> {{ $lotId }}</p>
    <p><a href="{{ $lotUrl }}">(Ver lote/terreno online)</a></p>
    
</body>
</html>
