<?php
    require_once "../clases/conexion.php";
    require_once "../clases/crud.php";
    $obj = new crud();
    $nombre = htmlentities($_POST['nombre']);
    $institucion = htmlentities($_POST['institucion']);
    $lat = htmlentities($_POST['lat']);
    $lon = htmlentities($_POST['lon']);
    $fecha = htmlentities($_POST['fecha']);
    $datos = array($nombre, $institucion, $lat, $lon, $fecha);
   
    echo $obj->agregar($datos);
?>