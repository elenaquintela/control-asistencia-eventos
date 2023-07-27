<?php
session_start();

// Verificar si se ha seleccionado un evento válido
if (!isset($_GET['event']) || empty($_GET['event'])) {
    header("Location: index.php");
    exit();
}
// Conexión a la base de datos
require_once 'mysql-connect.php';

// Obtener detalles del evento seleccionado
$event_id = $_GET['event'];
$query_event = "SELECT * FROM eventos WHERE id_evento = $event_id";
$result_event = mysqli_query($conexion, $query_event);
$evento = mysqli_fetch_assoc($result_event);

// Verificar si el evento es válido
if (!$evento) {
    header("Location: index.php");
    exit();
}

// Verificar si hay un mensaje de error en la sesión
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
// Limpiar el mensaje de error de la sesión
unset($_SESSION['error_message']);

// Obtener datos ingresados previamente (si existen) para mantenerlos en el formulario
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$apellidos = isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Limpiar las variables de sesión de datos previos
unset($_SESSION['nombre']);
unset($_SESSION['apellidos']);
unset($_SESSION['email']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/d6cf4a0a53.js" crossorigin="anonymous"></script>
    <title>Alta de asistentes</title>
</head>

<body class="body-signup">
    <header>
        <nav>
            <ul>
                <li><a href="./index.php">EVENTOS</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-signup">
        <h1 class="title-signup-form"><?php echo $evento['titulo']; ?></h1>
        <form class="form-signup" method="POST" action="validate-signup.php?event=<?php echo $event_id; ?>">
            <h2 class="title-signup">Nuevo asistente</h2>
            <div class="container-signup">
                <label class="input-signup" for="nombre" >Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" >
            </div>
            <div class="container-signup">
                <label class="input-signup" for="apellidos" >Apellidos</label>
                <input class="" type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" >
            </div>
            <div class="container-signup">
                <label class="input-signup" for="email" >Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" >
            </div>
            <div class="container-signup">
                <label class="input-signup" for="nif" >NIF / DNI</label>
                <input type="text" id="nif" name="nif" >
            </div>

            <!-- Mostrar el mensaje de error -->
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <button class="btn-confirm" type="submit"><i class="fa-solid fa-square-plus"></i> Darse de alta</button>
            <button class="btn-cancel" type="button" onclick="location.href='event-details.php?event=<?php echo $event_id; ?>'"><i class='fa-solid fa-rectangle-xmark'></i> Cancelar  </button>
        </form>

    </main>
</body>

</html>