<!DOCTYPE html>

<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #mapa {
            width: 100%;
            height: 100%;
            float: left;
            background: green;
        }
        
        #infor {
            width: 100%;
            height: 100vh;
            float: left;
        }
    </style>
    <!--IMPORTANTE RESPETAR EL ORDEN -->
    <!--ESTILOS DE BOOSTRAP AIzaSyA_bxItF3pDdNx8BQV7GRtNAtxBdT1fRqw -->

    <link href="librerias/bootstrap/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_bxItF3pDdNx8BQV7GRtNAtxBdT1fRqw"></script>
    <script type="text/javascript" src="librerias/jquery-2.0.3.min.js"></script>
    <!--ARCHIVOS JAVASCRIPT DE BOOTSTRAP -->
    <script type="text/javascript" src="librerias/bootstrap/bootstrap.min.js"></script>
    <script>
        //VARIABLES GENERALES
        //declaras fuera del ready de jquery
        var nuevos_marcadores = [];
        var marcadores_bd = [];
        var mapa = null; //VARIABLE GENERAL PARA EL MAPA
        //FUNCION PARA QUITAR MARCADORES DE MAPA
        function limpiar_marcadores(lista) {
            for (i in lista) {
                //QUITAR MARCADOR DEL MAPA
                lista[i].setMap(null);
            }
        }
        $(document).on("ready", function() {

            //VARIABLE DE FORMULARIO
            var formulario = $("#formulario");
            //-88.86767915737873, 
            var punto = new google.maps.LatLng(13.724149743725818, -88.86767915737873);
            var config = {
                zoom: 9,
                center: punto,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            mapa = new google.maps.Map($("#mapa")[0], config);

            google.maps.event.addListener(mapa, "click", function(event) {
                var coordenadas = event.latLng.toString();

                coordenadas = coordenadas.replace("(", "");
                coordenadas = coordenadas.replace(")", "");

                var lista = coordenadas.split(",");

                var direccion = new google.maps.LatLng(lista[0], lista[1]);
                //PASAR LA INFORMACI�N AL FORMULARIO
                formulario.find("input[name='nombre']").focus();
                formulario.find("input[name='institucion']");
                formulario.find("input[name='cx']").val(lista[0]);
                formulario.find("input[name='cy']").val(lista[1]);
                formulario.find("input[name='fecha']").focus();


                var marcador = new google.maps.Marker({
                    //titulo:prompt("Titulo del marcador?"),
                    position: direccion,
                    map: mapa,
                    animation: google.maps.Animation.DROP,
                    draggable: false
                });
                //VIDEO 15
                $("#collapseOne").collapse('show');
                $("#collapseTwo").collapse('hide');
                //ALMACENAR UN MARCADOR EN EL ARRAY nuevos_marcadores
                nuevos_marcadores.push(marcador);

                google.maps.event.addListener(marcador, "click", function() {

                });

                //BORRAR MARCADORES NUEVOS
                limpiar_marcadores(nuevos_marcadores);
                marcador.setMap(mapa);
            });
            $("#btn_grabar").on("click", function() {
                //INSTANCIAR EL FORMULARIO
                var f = $("#formulario");

                //VALIDAR CAMPO TITULO
                if (f.find("input[name='nombre']").val().trim() == "") {
                    alert("Falta nombre");
                    return false;
                }
                if (f.find("input[name='institucion']").val().trim() == "") {
                    alert("Falta nombre de institucion");
                    return false;
                }
                //VALIDAR CAMPO CX
                if (f.find("input[name='cx']").val().trim() == "") {
                    alert("Falta Coordenada X");
                    return false;
                }
                //VALIDAR CAMPO CY
                if (f.find("input[name='cy']").val().trim() == "") {
                    alert("Falta Coordenada Y");
                    return false;
                }
                if (f.find("input[name='fecha']").val().trim() == "") {
                    alert("Falta la fecha");
                    return false;
                }
                //FIN VALIDACIONES

                if (f.hasClass("busy")) {
                    //Cuando se haga clic en el boton grabar
                    //se le marcar� con una clase 'busy' indicando
                    //que ya se ha presionado, y no permitir que se
                    //realiCe la misma operaci�n hasta que esta termine
                    //SI TIENE LA CLASE BUSY, YA NO HARA NADA
                    return false;
                }
                //SI NO TIENE LA CLASE BUSY, SE LA PONDREMOS AHORA
                f.addClass("busy");
                //Y CUANDO QUITAR LA CLASE BUSY?
                //CUANDO SE TERMINE DE PROCESAR ESTA SOLICITUD
                //ES DECIR EN EL EVENTO COMPLETE

                var loader_grabar = $("#loader_grabar");
                $.ajax({
                    type: "POST",
                    url: "iajax.php",
                    dataType: "JSON",
                    data: f.serialize() + "&tipo=grabar",
                    success: function(data) {
                        if (data.estado == "ok") {
                            loader_grabar.removeClass("label-warning").addClass("label-success")
                                .text("Grabado OK").delay(4000).slideUp();
                            listar();
                        } else {
                            alert(data.mensaje);
                        }
                    },
                    beforeSend: function() {
                        //Notificar al usuario mientras que se procesa su solicitud
                        loader_grabar.removeClass("label-success").addClass("label label-warning")
                            .text("Procesando...").slideDown();
                    },
                    complete: function() {
                        //QUITAR LA CLASE BUSY
                        f.removeClass("busy");
                        f[0].reset();
                        //[0] jquery trabaja con array de elementos javascript no
                        //asi que se debe especificar cual elemento se har� reset
                        //capricho de javascript
                        //AHORA PERMITIR� OTRA VEZ QUE SE REALICE LA ACCION
                        //Notificar que se ha terminado de procesar
                    }
                });
                return false;
            });

            //BORRAR
            $("#btn_borrar").on("click", function() {
                var f_eliminar = $("#formulario_eliminar");
                $.ajax({
                    type: "POST",
                    url: "iajax.php",
                    data: "id=" + f_eliminar.find("input[name='id']").val() + "&tipo=borrar",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.estado == "ok") {
                            limpiar_marcadores(nuevos_marcadores);
                            alert(data.mensaje);
                            f_eliminar[0].reset();
                            listar();
                        } else {
                            alert(data.mensaje);
                        }
                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            });

            //ACTUALIZAR
            $("#btn_actualizar").on("click", function() {
                var f_eliminar = $("#formulario_eliminar");
                $.ajax({
                    type: "POST",
                    url: "iajax.php",
                    data: f_eliminar.serialize() + "&tipo=actualizar",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.estado == "ok") {
                            limpiar_marcadores(nuevos_marcadores);
                            alert(data.mensaje);
                            f_eliminar[0].reset();
                            listar();
                        } else {
                            alert(data.mensaje);
                        }
                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            });

            //BUSCAR
            $("#btn_buscar").on("click", function() {
                var palabra_buscar = $("#palabra_buscar").val();
                var select_resultados = $("#select_resultados");
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "iajax.php",
                    data: "palabra_buscar=" + palabra_buscar + "&tipo=buscar",
                    success: function(data) {
                        if (data.estado == "ok") {
                            $.each(data.mensaje, function(i, item) {
                                $("<option data-cx='" + item.cx + "' data-cy='" + item.cy + "' value='" + item.IdPunto + "'>" + item.Titulo + "</option>")
                                    .appendTo(select_resultados);
                            });
                        }

                    },
                    beforeSend: function() {
                        select_resultados.empty(); //limpiar ComboBox
                    },
                    complete: function() {

                    }
                });
                return false;
            });

            //CENTRAR EL MARCADOR AL SELECCIONARLO
            $("#select_resultados").on("click, change", function() {
                //PEQUEÑA VALIDACION
                if ($(this).children().length < 1) {
                    return false; //NO HACER NADA, AL NO TENER ITEMS
                }
                var cx = $("#select_resultados option:selected").data("cx");
                var cy = $("#select_resultados option:selected").data("cy");
                //Crear variable coordenada
                var myLatLng = new google.maps.LatLng(cx, cy);
                //VARIABLE MAPA
                mapa.setCenter(myLatLng);
            });


            //CARGAR PUNTOS AL TERMINAR DE CARGAR LA P�GINA
            listar(); //FUNCIONA, AHORA A GRAFICAR LOS PUNTOS EN EL MAPA
        });
        //FUERA DE READY DE JQUERY
        //FUNCTION PARA RECUPERAR PUNTOS DE LA BD
        function listar() {
            //ANTES DE LISTAR MARCADORES
            //SE DEBEN QUITAR LOS ANTERIORES DEL MAPA
            limpiar_marcadores(marcadores_bd);
            var f_eliminar = $("#formulario_eliminar");
            $.ajax({
                type: "POST",
                url: "iajax.php",
                dataType: "JSON",
                data: "&tipo=listar",
                success: function(data) {
                    if (data.estado == "ok") {
                        //alert("Hay puntos en la BD");
                        $.each(data.mensaje, function(i, item) {
                            //OBTENER LAS COORDENADAS DEL PUNTO
                            var posi = new google.maps.LatLng(item.lat, item.lon); //bien
                            //CARGAR LAS PROPIEDADES AL MARCADOR
                            var marca = new google.maps.Marker({
                                idMarcador: item.id,
                                position: posi,
                                nombre: item.nombre,
                                cx: item.lat,
                                cy: item.lon
                            });
                            //AGREGAR EVENTO CLICK AL MARCADOR
                            google.maps.event.addListener(marca, "click", function() {
                                $("#collapseOne").collapse('hide');
                                $("#collapseTwo").collapse('show');
                                //alert("Hiciste click en "+marca.idMarcador + " - " + marca.titulo) ;
                                //SOLO MOVER CUANDO SE MARQUE EL CHECKBOX DEL FORMULARIO
                                if ($("#opc_edicion").prop("checked"))

                                {
                                    //HACER UN MARCADOR DRAGGABLE
                                    marca.setOptions({
                                        draggable: true
                                    });

                                    google.maps.event.addListener(marca, 'dragend', function(event) {
                                        //AL FINAL DE MOVE EL MARCADOR
                                        //ESTE MISMO YA SE ACTUALIZA CON LAS NUEVAS COORDENADAS
                                        //alert(marca.position);
                                        var coordenadas = event.latLng.toString();
                                        coordenadas = coordenadas.replace("(", "");
                                        coordenadas = coordenadas.replace(")", "");
                                        var lista = coordenadas.split(",");
                                        f_eliminar.find("input[name='cx']").val(lista[0]);
                                        f_eliminar.find("input[name='cy']").val(lista[1]);
                                    });
                                } else {

                                    f_eliminar.find("input[name='nombre']").val(marca.nombre);
                                    f_eliminar.find("input[name='cx']").val(marca.lat);
                                    f_eliminar.find("input[name='cy']").val(marca.lon);
                                    f_eliminar.find("input[name='id']").val(marca.idMarcador);
                                }
                                limpiar_marcadores(nuevos_marcadores);
                            });
                            //AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BD
                            marcadores_bd.push(marca);
                            //UBICAR EL MARCADOR EN EL MAPA
                            marca.setMap(mapa);
                        });
                    } else {
                        alert("NO hay puntos en la BD");
                    }
                },
                beforeSend: function() {

                },
                complete: function() {

                }
            });
        }
        //PLANTILLA AJAX
    </script>
</head>

<body>
    <div class=" container-fluid mt-3">
        <div class="row">
            <div class="col-sm-6 ">
                <div id="mapa">
                    <h2>Aquí ira el mapa!</h2>
                </div>
            </div>
            <div class="col-sm-6">

                <div id="infor">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Agregar
                              </button>
                                    <a href="index.php" class="btn btn-success">Inicio</a>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form id="formulario">
                                        <table>
                                            <tr>
                                                <td>Nombre</td>
                                                <td><input type="text" class="form-control" name="nombre" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Institucion</td>
                                                <td><input type="text" class="form-control" name="institucion" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Latitud</td>
                                                <td><input type="text" class="form-control" readonly name="cx" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Longitud</td>
                                                <td><input type="text" class="form-control" readonly name="cy" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Fecha</td>
                                                <td><input type="date" class="form-control" name="fecha" autocomplete="off" /></td>
                                            </tr>
                                            <!-- Aqui estar� se colocaran los mensajes para el usuario -->
                                            <tr>
                                                <td></td>
                                                <td><span id="loader_grabar" class=""></span></td>
                                            </tr>
                                            <tr>
                                                <td><button type="button" id="btn_grabar" class="btn btn-success btn-sm">Grabar</button></td>
                                                <td><button type="button" class="btn btn-danger btn-sm">Cancelar</button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--div class="accordion" id="accordion2">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
              Agregar
            </a>
                    </div>
                    <div id="collapseOne" class="accordion-body collapse in">
                        <div class="accordion-inner">
                            <form id="formulario">
                                <table>
                                    <tr>
                                        <td>Título</td>
                                        <td><input type="text" class="form-control" name="titulo" autocomplete="off" /></td>
                                    </tr>
                                    <tr>
                                        <td>Coordenada X</td>
                                        <td><input type="text" class="form-control" readonly name="cx" autocomplete="off" /></td>
                                    </tr>
                                    <tr>
                                        <td>Coordenada Y</td>
                                        <td><input type="text" class="form-control" readonly name="cy" autocomplete="off" /></td>
                                    </tr>
                                    <!-- Aqui estar� se colocaran los mensajes para el usuario >
            <tr>
                <td></td>
                <td><span id="loader_grabar" class=""></span></td>
            </tr>
            <tr>
                <td><button type="button" id="btn_grabar" class="btn btn-success btn-sm">Grabar</button></td>
                <td><button type="button" class="btn btn-danger btn-sm">Cancelar</button></td>
            </tr>
            </table>
            </form>
        </div>
    </div>
    </div-->
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Eliminar
                                  </button>
                                </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <form id="formulario_eliminar">
                                        <input type="hidden" class="form-control" name="id" />
                                        <table>
                                            <tr>
                                                <td>Título</td>
                                                <td><input type="text" class="form-control" name="titulo" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Coordenada X</td>
                                                <td><input type="text" class="form-control" readonly name="cx" autocomplete="off" /></td>
                                            </tr>
                                            <tr>
                                                <td>Coordenada Y</td>
                                                <td><input type="text" class="form-control" readonly name="cy" autocomplete="off" /></td>
                                            </tr>
                                            <!-- Aqui estar� se colocaran los mensajes para el usuario -->
                                            <tr>
                                                <td></td>
                                                <td><span id="loader_grabar" class=""></span></td>
                                            </tr>
                                            <tr>
                                                <td><button type="button" id="btn_actualizar" class="btn btn-success btn-sm">Actualizar</button></td>
                                                <td><button type="button" id="btn_borrar" class="btn btn-danger btn-sm">Borrar</button></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><label>
                                <input id="opc_edicion" type="checkbox"> Habilitar Edición
                              </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                  Eliminar
                </a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <form id="formulario_eliminar">
                            <input type="hidden" class="form-control" name="id" />
                            <table>
                                <tr>
                                    <td>Título</td>
                                    <td><input type="text" class="form-control" name="titulo" autocomplete="off" /></td>
                                </tr>
                                <tr>
                                    <td>Coordenada X</td>
                                    <td><input type="text" class="form-control" readonly name="cx" autocomplete="off" /></td>
                                </tr>
                                <tr>
                                    <td>Coordenada Y</td>
                                    <td><input type="text" class="form-control" readonly name="cy" autocomplete="off" /></td>
                                </tr>
                                <!-- Aqui estar� se colocaran los mensajes para el usuario >
                                <tr>
                                    <td></td>
                                    <td><span id="loader_grabar" class=""></span></td>
                                </tr>
                                <tr>
                                    <td><button type="button" id="btn_actualizar" class="btn btn-success btn-sm">Actualizar</button></td>
                                    <td><button type="button" id="btn_borrar" class="btn btn-danger btn-sm">Borrar</button></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><label>
                                    <input id="opc_edicion" type="checkbox"> Habilitar Edición
                                  </label>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div-->
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Buscar
                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <form id="formulario_buscar">
                                        <table>
                                            <TR>
                                                <td>
                                                    <input type="text" id="palabra_buscar" class="form-control" autocomplete="off" />
                                                </td>
                                                <td>
                                                    <button type="button" id="btn_buscar" class="btn btn-success btn-sm">Buscar</button>
                                                </td>
                                            </TR>

                                            <TR>
                                                <td>
                                                    <select id="select_resultados">
                        <option value="uno">uno</option>
                      </select>
                                                </td>
                                                <td></td>
                                            </TR>

                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                  Buscar
                </a>
                    </div>
                    <div id="collapseThree" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <form id="formulario_buscar">
                                <table>
                                    <TR>
                                        <td>
                                            <input type="text" id="palabra_buscar" class="form-control" autocomplete="off" />
                                        </td>
                                        <td>
                                            <button type="button" id="btn_buscar" class="btn btn-success btn-sm">Buscar</button>
                                        </td>
                                    </TR>

                                    <TR>
                                        <td>
                                            <select id="select_resultados">
                            <option value="uno">uno</option>
                          </select>
                                        </td>
                                        <td></td>
                                    </TR>

                                </table>
                            </form>
                        </div>
                    </div>
                </div-->
                </div>
            </div>
        </div>
    </div>
</body>

</html>