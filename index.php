<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Postgres</title>
    <?php require_once "script.php";?>    
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card text-left mt-3">
                <div class="card-header text-center text-uppercase">
                    Admin Reporte
                </div>    
                <div class="card-body">
                    <h4 class="card-title">Lista de reportes</h4>
                    <span class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregar">
                        Agregar nuevo <span class="fa fa-plus-circle text-white text-center text-uppercase"></span>
                    </span>
                  
                    <a href="mapa.php" class="btn btn-success btn-sm mb-3 p-2 text-uppercase"> Ver Mapa 
                    <span class="fa fa-plus-circle text-white text-center text-uppercase"></span>
                    </a>
                 
                    <div id="tabladatable"></div>
                </div>
                <div class="card-footer text-muted">
                    Direccion General Proteccion Civil
                </div>                
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
	<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-uppercase" id="exampleModalLabel">Agregar evento</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                <form id="frm-nuevo" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese su nombre completo" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Institucion</label>
                        <input type="text" name="institucion" id="institucion"  class="form-control" placeholder="Ingrese la institucion" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Latitud</label> 
                        <input type="text" name="lat" id="latitud"   class="form-control"  required >
                    </div>
                    <div class="form-group">
                        <label>Longitud</label> 
                        <input type="text" name="lon" id="longitud" class="form-control"  required >
                    </div>
                    
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" id="fecha"  class="form-control"  required>
                    </div>
                </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" id="btnAgregar" class="btn btn-primary">Agregar nuevo</button>
				</div>
			</div>
		</div>
	</div>
    <!-- Modal Actualizar-->
	<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-uppercase" id="exampleModalLabel">Actualizar evento</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                <form id="frm-editar" enctype="multipart/form-data" autocomplete="off">
                    <input type="text" hidden="" id="id" name="id">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ingrese su nombre completo" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Institucion</label>
                        <input type="text" name="enterprise" id="enterprise"  class="form-control" placeholder="Ingrese la institucion" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Latitud</label> 
                        <input type="text" name="latit" id="latit"   class="form-control"  required >
                    </div>
                    <div class="form-group">
                        <label>Longitud</label> 
                        <input type="text" name="long" id="long" class="form-control"  required >
                    </div>
                    
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="date" id="date"  class="form-control"  required>
                    </div>
                </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" id="btnEdit" class="btn btn-warning">Actualizar</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
(function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getCoords, errorFound);
    } else {
        alert("Por favor, actualiza tu navegador");
    }

    function errorFound(error) {
        alert("Un error ocurrio: " + error.code);
    };

    function getCoords(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        var latitud = document.getElementById("latitud");
        latitud.value = lat;
        var longitud = document.getElementById("longitud");
        longitud.value = lon;
    };
})();
</script>
<script>
$(document).ready(function(){
    $("#btnAgregar").click(function(){
        datos = $("#frm-nuevo").serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "procesos/agregar.php",
            success: function(r){
                if(r == 1){
                    $("#frm-nuevo")[0].reset();
                    $('#tabladatable').load('tabla.php');
                    alertify.success("Agregar con Exito");
                }else{
                    alertify.error("Error al agregar");
                }
            }
        });
    });
    $("#btnEdit").click(function(){
        datos = $("#frm-editar").serialize();
        $.ajax({
            type: "POST",
            data: datos,
            url: "procesos/actualizar.php",
            success: function(r){
                if(r == 1){
                    $('#tabladatable').load('tabla.php');
                    alertify.success("Actualizado con Exito");
                }else{
                    alertify.error("Error al actualizar");
                }
            }
        });
    });
});
</script>
<script>
    $(document).ready(function(){
        $('#tabladatable').load('tabla.php');
    });
</script>
<script>
    function aditar(id){
        $.ajax({
            type: "POST",
            data: "id=" + id,
            url: "procesos/obtener.php",
            success: function(r){
                datos = jQuery.parseJSON(r);
                $('#id').val(datos['id']);
                $('#name').val(datos['nombre']);
                $('#enterprise').val(datos['institucion']);
                $('#latit').val(datos['lat']);
                $('#long').val(datos['lon']);
                $('#date').val(datos['fecha']);
            }
        });
    }
    function eliminar(id){
        alertify.confirm('Eliminar evento', 'Está seguro de eliminar este evento',function(){
            $.ajax({
            type: "POST",
            data: "id=" + id,
            url: "procesos/eliminar.php",
            success: function(r){
                if (r == 1) {
                    $('#tabladatable').load('tabla.php');
                    alertify.success('Eliminado con exito');
                }else{
                    alertify.error('No se elimino');
                } 
            }    
        });
        }, function(){});
    }
</script>