<?php
 $conexion = mysqli_connect("localhost", "root", "", "eventscontrol");
 if (!$conexion) {
  die("No se ha podido realizar la conexión" . mysqli_error());
 }
