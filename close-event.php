<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_event'])) {
    // Conexión a la base de datos
    require_once 'mysql-connect.php';

    // Obtener el ID del evento a cerrar
    $event_id = $_POST['event_id'];

    // Actualizar estado del evento a "Cerrado" en la base de datos
    $query_finalizar_evento = "UPDATE eventos SET estado = 'Cerrado' WHERE id_evento = $event_id";
    mysqli_query($conexion, $query_finalizar_evento);

    // Redirigir a la página de inicio
    header("Location: index.php");
    exit();
}
