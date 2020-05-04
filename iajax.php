<?php
#Agregar un retardo de 2 segundo para poder apreciar los mensajes.
#sleep(2);
header('content-type: application/json; charset=utf-8');//HEADER PARA JSON
include_once "clases/conexion.php";
include_once 'clases/puntosDao.php';
$ac = isset($_POST["tipo"])?$_POST["tipo"]:"x"; //PARAMETRO PARA DETERMINAR LA ACCION

switch ($ac) {
    case "grabar":
        $p = new puntosDao();
        $exito = $p->grabar(htmlentities($_POST["nombre"]), $_POST["institucion"], $_POST["cx"], $_POST["cy"], $_POST["fecha"]);
        if($exito)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "error al grabar!";
        }
    break;

    case "listar":
        $p = new puntosDao();
        $resultados = $p->listar_todo();
        if(sizeof($resultados)>0)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = $resultados;
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "No hay registros";
        }
    break;
    case "borrar":
        $p = new puntosDao();
        $resultados = $p->borrar($_POST["id"]);
        if($resultados)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = "Borrado Correctamente";
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al borrar";
        }
    break;
    
    case "actualizar":
        $p = new puntosDao();
        $exito = $p->actualizar($_POST["id"], $_POST["titulo"], $_POST["cx"], $_POST["cy"]);
        if($exito)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = "Actualizado Correctamente";
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "error al actualizar!";
        }
    break;

    case "buscar":
        $p = new puntosDao();
        $resultados = $p->buscar($_POST["palabra_buscar"]);
        if(sizeof($resultados)>0)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = $resultados;
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "No hay registros";
        }
    break;
    
    default:
        $r["estado"] = "error";
        $r["mensaje"] = "datos no válidos";
    break;
}
echo json_encode($r);//IMPRIMIR JSON
?>