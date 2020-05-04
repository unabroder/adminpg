<?php 
    require_once "clases/conexion.php";
    $obj = new conectar();
    $conexion = $obj->conexion();
    $sql = "SELECT 
            id, nombre, institucion, lat, lon, fecha 
            FROM reporte WHERE estado = 1;";
    $sentencia = $conexion->query($sql);
    $reportes = $sentencia->fetchAll(PDO::FETCH_OBJ);
?>
<div>
    <table class="table table-hover table-condensed table-bordered" id="iddatatable">
    <thead class="bg-danger text-white">
        <tr>
            <td>Nombre</td>
            <td>Institucion</td>
            <td>Latitud</td>
            <td>Longitud</td>
            <td>Fecha</td>
            <td>Editar</td>
            <td>Eliminar</td>
        </tr>
    </thead>
    <tfoot class="bg-light text-muted">
        <tr>
            <td>Nombre</td>
            <td>Institucion</td>
            <td>Latitud</td>
            <td>Longitud</td>
            <td>Fecha</td>
            <td>Editar</td>
            <td>Eliminar</td>
        </tr>
    </tfoot>
    <tbody class="bg-white">
    <?php foreach ($reportes as $reporte ) {?>
        <tr>
            <td><?php echo $reporte->nombre; ?></td>
            <td><?php echo $reporte->institucion; ?></td>
            <td><?php echo $reporte->lat; ?></td>
            <td><?php echo $reporte->lon; ?></td>
            <td><?php echo $reporte->fecha; ?></td>
            <td class="text-center"><span class="btn btn-warning btn-sm text-center" data-toggle="modal" data-target="#editar" onclick="aditar('<?php echo $reporte->id; ?>')">
                <span class="fa fa-pencil-square-o"></span>
            </span></td>
            <td class="text-center"><span class="btn btn-danger btn-sm text-center" onclick="eliminar('<?php echo $reporte->id; ?>')">
                <span class="fa fa-trash"></span>
            </span></td>
        </tr>
        <?php  } ?>
    </tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        $('#iddatatable').DataTable();
    });
</script>