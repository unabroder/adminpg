<?php
    require_once "../clases/conexion.php";
    require_once "../clases/crud.php";
    $obj = new crud();
    $id = htmlentities($_POST['id']);
    $nombre = htmlentities($_POST['name']);
    $institucion = htmlentities($_POST['enterprise']);
    $lat = htmlentities($_POST['latit']);
    $lon = htmlentities($_POST['long']);
    $fecha = htmlentities($_POST['date']);
    $datos = array($nombre, $institucion, $lat, $lon, $fecha, $id);
    echo $obj->actualizar($datos);
?>