<?php
session_start();

// Verificar si se ha seleccionado un evento y asistente válido
if (!isset($_GET['event']) || empty($_GET['event']) || !isset($_GET['asistente']) || empty($_GET['asistente'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'mysql-connect.php';

// Obtener detalles del evento seleccionado
$event_id = $_GET['event'];
$query_event = "SELECT * FROM eventos WHERE id_evento = $event_id AND estado = 'Abierto'";
$result_event = mysqli_query($conexion, $query_event);
$evento = mysqli_fetch_assoc($result_event);

// Verificar si el evento es válido
if (!$evento) {
    header("Location: index.php");
    exit();
}

// Obtener detalles del asistente seleccionado
$asistente_id = $_GET['asistente'];
$query_asistente = "SELECT * FROM asistentes WHERE id_asistente = $asistente_id AND id_evento = $event_id";
$result_asistente = mysqli_query($conexion, $query_asistente);
$asistente = mysqli_fetch_assoc($result_asistente);

// Verificar si el asistente es válido
if (!$asistente) {
    header("Location: index.php");
    exit();
}

// Procesar el formulario de registro de entrada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener la hora de entrada del formulario
    $hora_entrada = $_POST['hora_entrada'];

    // Realizar la inserción de la hora de entrada en la base de datos
    $query_insert_entrada = "UPDATE asistentes SET hora_asistencia = '$hora_entrada' WHERE id_asistente = $asistente_id";
    mysqli_query($conexion, $query_insert_entrada);

    // Redirigir a la página de detalles del evento
    header("Location: event-details.php?event=$event_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/d6cf4a0a53.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="./jquery.signature.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./jquery.signature.css">
    <style>
        .kbw-signature {
            width: 320px;
            height: 200px;
        }
        
        #sig canvas {
            width: 100% !important;
            height: auto;
        }
        </style>
        <title>Registro de entrada</title>
</head>

<body class="body-form">
    <header>
        <nav>
            <ul>
                <li><a href="./index.php">EVENTOS</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-form">
        <h1 class="title-form-event"><?php echo $evento['titulo']; ?></h1>
        <h3 class="title-form">Registro de entrada</h3>
        <form class="form-form" id="form-entrada" method="POST">
            <?php
            date_default_timezone_set('Europe/Madrid');
            $hora_actual = date('H:i');
            ?>
            <label for="hora_entrada"></label>
            <input type="time" id="hora_entrada" name="hora_entrada" value="<?php echo $hora_actual; ?>">
            <h2><?php echo $asistente['nombre'] . ' ' . $asistente['apellidos']; ?></h2>
            <button class="btn-signature" id="btn-firmar" type="button"><i class="fa-solid fa-signature"></i> Firmar</button>
            <button class="btn-cancel" type="button" onclick="location.href='./event-details.php?event=<?php echo $event_id; ?>'"><i class="fa-solid fa-rectangle-xmark"></i> Cancelar </button>
            <div id="firma" style="display: none">
                <h3>Firme en el recuadro</h3>
                <div id="sig"></div>
                <br />
                <button class="btn-erase" id="clear"><i class="fa-solid fa-eraser"></i> Limpiar Firma </button>
                <textarea id="signature64" name="signed" style="display: none"></textarea>
                <button class="btn-confirm" id="btn-submit" type="button"><i class="fa-solid fa-square-check"></i> Confirmar Entrada </button>
            </div>
            <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_id; ?>">
            <input type="hidden" id="asistente_id" name="asistente_id" value="<?php echo $asistente_id; ?>">
        </form>

        <script type="text/javascript">
            const sig = $('#sig').signature({
                syncField: '#signature64',
                syncFormat: 'PNG'
            });
            $('#clear').click(function(e) {
                e.preventDefault();
                sig.signature('clear');
                $("#signature64").val('');
            });

            $('#btn-firmar').click(function(e) {
                e.preventDefault();
                $('#firma').show();
            });

            $('#btn-submit').click(function(e) {
                e.preventDefault();
                const formData = new FormData($('#form-entrada')[0]);
                formData.append('event', $('#event_id').val());
                formData.append('asistente', $('#asistente_id').val());
                $.ajax({
                    url: './upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.location.href = './event-details.php?event=<?php echo $event_id; ?>';
                    },
                    error: function(xhr, status, error) {
                        alert('Error al guardar la firma.');
                    }
                });
            });
        </script>
</body>

</html>