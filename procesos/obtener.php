<?php
    require_once "../clases/conexion.php";
    require_once "../clases/crud.php";
    $obj = new crud();
    $id = htmlentities($_POST['id']);
    echo json_encode($obj->obtenerDatos($id));
?>