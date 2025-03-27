<!DOCTYPE html>
<html>
<head>
    <title>Nuevo mensaje desde el formulario de contacto</title>
</head>
<body>
    <h2>Recibiste un nuevo mensaje desde el formulario de contacto</h2>
    <p><strong>Nombre:</strong> {{ $fullName }}</p>
    <p><strong>Teléfono:</strong> {{ $telephone }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $message }}</p>
</body>
</html>
