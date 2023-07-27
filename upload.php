<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signed'])) {
        $event_id = $_POST['event'];
        $asistente_id = $_POST['asistente'];
        $folderPath = "./upload-signs/";
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.' . $image_type;
        file_put_contents($file, $image_base64);

        // Conexión a la base de datos
        require_once './mysql-connect.php';

        // Obtener la hora de entrada del formulario
        $hora_entrada = $_POST['hora_entrada'];

        // Realizar la inserción de la hora de entrada en la base de datos
        $query_insert_entrada = "UPDATE asistentes SET hora_asistencia = '$hora_entrada' WHERE id_asistente = $asistente_id";
        mysqli_query($conexion, $query_insert_entrada);
    }
}
?>
