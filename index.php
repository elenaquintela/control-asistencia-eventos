<?php
// Conexión a la base de datos
require_once 'mysql-connect.php';

// Obtener listado de eventos disponibles (estado = 'Abierto')
$query = "SELECT id_evento, titulo FROM eventos WHERE estado = 'Abierto'";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/d6cf4a0a53.js" crossorigin="anonymous"></script>
    <title>Eventos disponibles</title>
</head>

<body>
    <div class="card-home">
        <h1 class="title-home">Eventos disponibles</h1>
        <ul class="list-home">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <li class="item-home">
                    <a class="link-home" href="event-details.php?event=<?php echo $row['id_evento']; ?>">
                        <?php echo $row['titulo']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>