<?php
session_start();

// Verificar si se envió el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $nif = $_POST['nif'];

    // Limpiar y convertir el NIF a mayúsculas
    $nombre = htmlspecialchars(trim($nombre));
    $apellidos = htmlspecialchars(trim($apellidos));
    $email = htmlspecialchars(trim($email));
    $nif = strtoupper(htmlspecialchars(trim($nif)));

    // Validar los datos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($nif)) {
        $_SESSION['error_message'] = 'Todos los campos son obligatorios';
        // Guardar los datos ingresados en variables de sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['email'] = $email;
        // Redirigir al usuario a la página de detalles del evento
        header("Location: signup.php?event=" . $_GET['event']);
        exit();

    } elseif (!preg_match('/^[0-9]{8}[A-Za-z]$/', $nif)) {
        $_SESSION['error_message'] = 'El NIF introducido no es válido';
        // Guardar los datos ingresados en variables de sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['email'] = $email;
        // Redirigir al usuario a la página del evento
        header("Location: signup.php?event=" . $_GET['event']);
        exit();

    } else {
        // Conectar a la base de datos
        require_once 'mysql-connect.php';

        // Agregar al usuario a la base de datos:
        // Insertar los datos del usuario en la base de datos
        $query = "INSERT INTO asistentes (nombre, apellidos, email, NIF, id_evento) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $apellidos, $email, $nif, $_GET['event']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        // Redirigir al usuario a la página de detalles del evento
        header("Location: event-details.php?event=" . $_GET['event']);
        exit();
    }
}

