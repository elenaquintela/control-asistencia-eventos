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

// Obtener la hora actual en formato 'H:i'
date_default_timezone_set('Europe/Madrid');
$hora_actual = date('H:i');

// Obtener la lista de asistentes al evento
$query_asistentes = "SELECT * FROM asistentes WHERE id_evento = $event_id";
$result_asistentes = mysqli_query($conexion, $query_asistentes);

// Procesar el formulario de registro de hora de salida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asistente_id']) && isset($_POST['hora_salida_input'])) {
    $asistente_id = $_POST['asistente_id'];
    $hora_salida = $_POST['hora_salida_input'];

    $query_insert_hora_salida = "UPDATE asistentes SET hora_salida = '$hora_salida' WHERE id_asistente = $asistente_id";
    mysqli_query($conexion, $query_insert_hora_salida);

    // Redirigir para evitar reenvío del formulario al actualizar la página
    header("Location: event-details.php?event=$event_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/d6cf4a0a53.js" crossorigin="anonymous"></script>
    <title>Detalles del evento</title>
</head>

<body class="body-details">
    <header>
        <nav>
            <ul>
                <li><a class="navbar-link" href="./index.php">EVENTOS</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-details">
        <h1 class="title-details"><?php echo $evento['titulo']; ?></h1>
        <p><i class="fa-solid fa-calendar-day"></i><?php echo $evento['fecha_inicio']; ?></p>
        <div class="container-details">
            <table>
            <thead>
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                    </tr>
                </thead>
                <?php while ($asistente = mysqli_fetch_assoc($result_asistentes)) : ?>
                    <tr class="fila-asistente">
                        <td><?php echo $asistente['apellidos']; ?></td>
                        <td><?php echo $asistente['nombre']; ?></td>
                        <td>
                            <?php if ($asistente['hora_asistencia']) : ?>
                                <?php echo $asistente['hora_asistencia']; ?>
                            <?php else : ?>
                                <button class="btn-confirm-asistencia">
                                    <a href="event-form.php?event=<?php echo $event_id; ?>&asistente=<?php echo $asistente['id_asistente']; ?>"><i class="fa-solid fa-user-clock"></i></a>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($asistente['hora_salida']) : ?>
                                <?php echo $asistente['hora_salida']; ?>
                            <?php else : ?>
                                <?php if ($asistente['hora_asistencia']) : ?>
                                    <form class= 'form-details' method="POST">
                                        <input type="hidden" name="asistente_id" value="<?php echo $asistente['id_asistente']; ?>">
                                        <!-- Aquí mostramos la hora actual en el input para confirmar la salida -->
                                        <input type="time" name="hora_salida_input" value="<?php echo $hora_actual; ?>" required>
                                        <button class="btn-confirm-salida" type="submit"></i><i class="fa-solid fa-right-from-bracket"></i></button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <form method="POST">
            <button class="btn-confirm" type="button" >
                <a href="signup.php?event=<?php echo $event_id; ?>"><i class="fa-solid fa-user-plus"></i> Nuevo asistente</a>
            </button>
            <button class="btn-finish" type="submit" name="confirm_close"><i class="fa-solid fa-shop-slash"></i> Finalizar evento </button>
        </form>
            <?php
            // Procesar el formulario de finalización del evento
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_close'])) {
                // HTML con el mensaje de advertencia y los botones
                $html_output = "<div class='modal-shadow'>";
                $html_output .= "<div class='modal-content'>";
                $html_output .= "<h1 class='modal-msg'>¿Está seguro de que desea cerrar el evento?</h1>";
                $html_output .= "<form method='POST' action='close-event.php'>";
                $html_output .= "<input type='hidden' name='event_id' value='" . $event_id . "'>";
                $html_output .= "<button class='btn-confirm' type='submit' name='close_event'><i class='fa-solid fa-square-check'></i> Confirmar</button>";
                $html_output .= "<button class='btn-cancel' type='button' onclick='history.go(-1)'><i class='fa-solid fa-rectangle-xmark'></i> Cancelar </button>";
                $html_output .= "</form>";
                $html_output .= "</div>";
                $html_output .= "</div>";
                // Imprimir el contenido HTML generado
                echo $html_output;
                exit();
            }
            ?>
    </main>
</body>

</html>