<?php     
class crud{
    public function agregar($datos){
        $obj = new conectar();
        $conexion = $obj->conexion();
        $sql = $conexion->prepare("INSERT INTO reporte(nombre, institucion, lat, lon, fecha) VALUES(?,?,?,?,?)");
        $result = $sql->execute([$datos[0], $datos[1], $datos[2], $datos[3], $datos[4]]);
        if ($result) {
            return $result;
        }else{
            echo "Error al insertar datos a reporte";
        } 
    }
    public function actualizar($datos){
        $obj = new conectar();
        $conexion = $obj->conexion();
        $sql = $conexion->prepare("UPDATE reporte SET nombre = ?, institucion = ?, lat = ?, lon = ?, fecha = ? WHERE id = ?");
        $result = $sql->execute([$datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5]]);
        if ($result) {
            return $result;
        }else{
            echo "Error al actualizar datos a reporte";
        } 
    }
    public function eliminar($id){
        $obj = new conectar();
        $conexion = $obj->conexion();
        $sql = $conexion->prepare("UPDATE reporte SET  estado = 0 WHERE id = ?");
        $result = $sql->execute([$id]);
        if ($result) {
            return $result;
        }else{
            echo "Error al actualizar datos a reporte";
        } 
    }
    public function obtenerDatos($id){
        $obj = new conectar();
        $conexion = $obj->conexion();
        $sentencia = $conexion->prepare("SELECT id, nombre, institucion, lat, lon, fecha FROM reporte WHERE id = ?");
        $sentencia->execute([$id]);
        $reporte = $sentencia->fetchObject();
        $datos = array(
            "id" => $reporte->id,
            "nombre"=> $reporte->nombre,
            "institucion" => $reporte->institucion,
            "lat"=> $reporte->lat,
            "lon"=> $reporte->lon,
            "fecha"=> $reporte->fecha
        );
        return $datos;

    }
}
 ?>