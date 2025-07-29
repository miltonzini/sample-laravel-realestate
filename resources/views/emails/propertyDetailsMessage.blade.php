<!DOCTYPE html>
<html>
<head>
    <title>Nuevo una consulta sobre una propiedad</title>
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
    
    <h2>Detalles de la Propiedad</h2>
    <p><strong>Título:</strong> {{ $propertyTitle }}</p>
    <p><strong>ID:</strong> {{ $propertyId }}</p>
    <p><a href="{{ $propertyUrl }}">(Ver propiedad online)</a></p>
    
</body>
</html>
