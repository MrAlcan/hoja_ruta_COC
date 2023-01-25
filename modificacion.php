<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

	<!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet/css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"-->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


</head>
<body>

	<?php
        session_start();

        if($_SESSION['area']){
            $ses = $_SESSION['area'];
            $rol = $_SESSION['rol'];
			$area_usuario = $_SESSION['nombre_area_usuario'];
			$id_usuario = $_SESSION['id'];
        }else{
            echo "<script>location.href='index.html'</script>";
        }
        echo "<script>console.log($ses)</script>";
    ?>

	<nav class="navbar navbar-expand-lg navbar-dark bg-success" role="navigation">
		<div class="container-fluid m-2">
			
			<div class="row navbar-brand">
					<div id="gtco-logo" class="col"><a href="index.html" class="navbar-brand">Cooperativa <em> Apostol Santiago</em></a></div>
                    <?php
                        echo "<div id='gtco-logo' class='col'>$area_usuario</div>";
                    ?>		
			</div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
				<ul class="navbar-nav justify-content-end">
                    <li class='nav-item'><a href="inicio.php" class="nav-link" aria-current="page">Inicio</a></li>
                    <?php
						if($ses == 1){
							echo "<li class='nav-item'><a href='nuevo.php' class='nav-link' aria-current='page'>Nuevo registro</a></li>";
						}
                        if($rol == 1){
							echo "<li class='nav-item'><a href='flujoActual.php' class='nav-link' aria-current='page'>Flujo actual</a></li>";
							echo "<li class='nav-item'><a href='modificacion.php' class='nav-link active' aria-current='page'>Modificaciones</a></li>";
                        }
                    ?>
					<li class='nav-item'><a href='pendientes.php' class='nav-link' aria-current='page'>Pendientes</a></li>
                    <li class='nav-item'><a href='enviados.php' class='nav-link' aria-current='page'>Enviados</a></li>
                    <li class='nav-item'><a href='completados.php' class='nav-link' aria-current='page'>Completados</a></li>
                    <li class='nav-item'><a href='terminados.php' class='nav-link' aria-current='page'>Terminados</a></li>
					<li class='nav-item'><a href="desconectar.php" class='nav-link' aria-current='page'><font color="red">Salir</font></a></li>
				</ul>
			</div>
		</div>
	</nav>

    <div class="gtco-section border-bottom m-5">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<h2>COOPERATIVA APOSTOL SANTIAGO</h2>
					<p>DOCUMENTOS CON SOLICITUD DE CAMBIO</p>
				</div>
			</div>
			<div class="row">


			<center><p>SOLICITUDES</p></center>

				<div class="table-responsive">          
					<table class="table">
						<thead>
						<tr>
                            <th>N° hoja de ruta</th>
							<th>Tipo de Solicitud</th>
							<th>Solicitante</th>
							<th>Area Solicitud</th>
                            <th>Fecha de Solicitud</th>
							<th>Modificar</th>
							<!--th>Fecha de Salida</th>
							<th>Hora de Salida</th>
                            <th>Precio</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

							$destinoArea = 100;

							

							
							$sql=("SELECT * FROM documentos_modificacion");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 0;
                            

							while($arreglo=mysqli_fetch_array($query)){

								

								$num = $num + 1;

								$id_flujo_env = $arreglo['id_flujo_modificacion'];

                                $sql_3 = "SELECT * FROM flujo_procedimiento WHERE id_flujo_padre=$id_flujo_env";
                                $query_3 = mysqli_query($mysqli,$sql_3);
                                $id_hijo;
                                while($arreglo_3=mysqli_fetch_array($query_3)){
                                    $id_hijo=$arreglo_3['id_flujo'];
                                }


								$estado_env = $arreglo['id_estado_modificacion'];

								//$id_padre = $arreglo['id_flujo_padre'];
								$fecha_enviada =$arreglo['fecha_solicitud'];

								$sql4=("SELECT documento_subido.nombre_archivo, documento_subido.directorio FROM documento_subido WHERE documento_subido.id_flujo_subido = $id_flujo_env");

								$nombre_archivo = '';
								$directorio_archivo = '';
								
								$query4=mysqli_query($mysqli,$sql4);
								while($arreglo4=mysqli_fetch_array($query4)){
									$nombre_archivo = $arreglo4[0];
									$directorio_archivo = $arreglo4[1];
								}

								$observaciones_totales_2='';

								/*
									PRUEBA PARA MULTIPLES AREAS

								*/

								$n_flujo_padre_2 = -1;
								$n_flujo_hijo_2 = $id_hijo;

								$observaciones_areas_2 = [];
								$observaciones_observaciones_2 = [];

								$aux_cont_2 = 0;

								while($n_flujo_padre_2!=0){
									$sql5_2 = "SELECT flujo_procedimiento.id_flujo, flujo_procedimiento.observaciones, areas.nombre_area, flujo_procedimiento.id_flujo_padre FROM flujo_procedimiento INNER JOIN areas ON flujo_procedimiento.id_area_procedencia = areas.id_area WHERE flujo_procedimiento.id_flujo = $n_flujo_hijo_2";

									$query5_2 = mysqli_query($mysqli,$sql5_2);
									while($arreglo5_2=mysqli_fetch_array($query5_2)){
										$n_flujo_hijo_2 = $arreglo5_2[3];
										$n_flujo_padre_2 = $arreglo5_2[3];
										$observaciones_areas_2[$aux_cont_2] = $arreglo5_2[2];
										$observaciones_observaciones_2[$aux_cont_2] = $arreglo5_2[1];
									}
									$aux_cont_2 = $aux_cont_2 + 1;
								}
								for ($i=$aux_cont_2-2; $i >=0 ; $i--) { 
									$observaciones_totales_2 = $observaciones_totales_2 . $observaciones_areas_2[$i] . ' - ' . $observaciones_observaciones_2[$i] . "\n";
								}

								$nombre_modal_pendientes = 'exampleModal' . $num;
								$referencia_pendientes = '#' . $nombre_modal_pendientes;

								$nombre_modal_form = 'exampleModalForm' . $num;
								$referencia_form = '#' . $nombre_modal_form;

								$n_registro_a = $arreglo['codigo_hoja_ruta'];
								$gestion = $arreglo['gestion'];
								$tipo_procedimiento_a = $arreglo['nombre_tipo_procedimiento'];
								$solicitante_a = $arreglo['solicitante'];
								$descripcion_a = $arreglo['descripcion_solicitud'];
								$fecha_enviada = $arreglo['fecha_solicitud'];
                                $area_solicitud = $arreglo['nombre_area'];
                                $descripcion_cambio = $arreglo['descripcion_modificacion'];


							

                                echo "<tr>";
                                    //echo "<td>$num</td>";
                                    echo "<td>$n_registro_a/$gestion</td>";
                                    echo "<td>$tipo_procedimiento_a</td>";
                                    echo "<td>$solicitante_a</td>";
                                    echo "<td>$area_solicitud</td>";
                                    echo "<td>$fecha_enviada</td>";
									/*echo "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target=$referencia_pendientes>
									Ver progreso
								  </button></td>";*/

								  	if($estado_env==5){
										echo "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target=$referencia_form>
                                        Ver Solicitud
                                      </button><button type='button' class='btn btn-danger'>
                                      Rechazar Solicitud
                                    </button></td>";
									}else if($estado_env==6){
										echo "<td>Solicitud Aceptada</td>";
									}else if($estado_env==7){
										echo "<td>Solicitud Rechazada</td>";
									}else{
										echo "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target=$referencia_form>
									Solicitar Cambio
								  </button></td>";
									}
									
                                    //echo "<td>$arreglo[3]</td>";
                                    //echo "<td>$arreglo[4]</td>";
                                echo "</tr>";
                                

								echo "<div class='modal fade' id=$nombre_modal_pendientes tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
											<div class='modal-dialog modal-dialog-scrollable'>
											<div class='modal-content'>
												<div class='modal-header'>
												<h3 class='modal-title'>Progreso hasta el area de $area_usuario</h3>
												<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class='modal-body'>
													<h4>Hoja de ruta $n_registro_a/$gestion</h4>
													<h5>Solicitante</h5>
													<input type='text' class='form-control' value='$solicitante_a' disabled>
													<h5>Tipo de Solicitud</h5>
													<input type='text' class='form-control' value='$tipo_procedimiento_a' disabled>
													<h5>Fecha Recibida</h5>
													<input type='text' class='form-control' value='$fecha_enviada' disabled>
													<h5>Descripcion de la solicitud</h5>
													<input type='text' class='form-control' value='$descripcion_a' disabled>
													<h5>Observaciones</h5>
													<textarea type='text' class='form-control' value='$observaciones_totales_2' disabled>$observaciones_totales_2</textarea>
													<h5>Ver documento</h5>
													<button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button>
												
												</div>
												<div class='modal-footer'>
												<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
												<!--button type='button' class='btn btn-primary'>Save changes</button-->
												</div>
											</div>
											</div>
										</div>";

								echo "<div class='modal fade' id=$nombre_modal_form tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
									<div class='modal-dialog modal-dialog-scrollable'>
										<div class='modal-content'>
											<div class='modal-header'>
											<h3 class='modal-title'>SOLICITUD DE CAMBIO</h3>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
											</div>
											<div class='modal-body'>
												<h4>Hoja de ruta $n_registro_a/$gestion</h4>
												<h5>Solicitante</h5>
												<input type='text' class='form-control' value='$solicitante_a' disabled>
												<h5>Tipo de Solicitud</h5>
												<input type='text' class='form-control' value='$tipo_procedimiento_a' disabled>
												<h5>Fecha Recibida</h5>
												<input type='text' class='form-control' value='$fecha_enviada' disabled>
												<h5>Descripcion de la solicitud</h5>
												<input type='text' class='form-control' value='$descripcion_a' disabled>
												<h5>Observaciones</h5>
												<textarea type='text' class='form-control' value='$observaciones_totales_2' disabled>$observaciones_totales_2</textarea>
                                                <h5>Descripcion solicitud</h5>
												<textarea type='text' class='form-control' value='$descripcion_cambio' disabled>$descripcion_cambio</textarea>
												<h5>Ver documento</h5>
												<button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button>";
												?>
												<!--form enctype='multipart/form-data'  <?php //echo "action='llenarModificacion.php?id_flujo_cambio=$id_padre&id_usuario_cambio=$id_usuario'";?> name="form" method="POST">
												
													<div class="row form-group">
														<div class="col-md-12">
															<label for="solicitud_cambio">DESCRIPCION DE LA SOLICITUD DE CAMBIO</label>
															<textarea type="textarea" name="solicitud_cambio" id="solicitud_cambio" class="form-control"></textarea>
														</div>
													</div>

													<div class="row form-group">
														<div class="col-md-4">
															<input type="submit" class="btn btn-primary btn-block" name="boton" value="Solicitar cambio">
														</div>
													</div>
											
												</form-->
										
										<?php
										echo	"</div>
											<div class='modal-footer'>
											<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
											<button type='button' class='btn btn-primary'>Aceptar solicitud</button>
											</div>
										</div>
									</div>
									</div>";
							}
															
						?>



						
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<?php
			if($ses == 1){
				echo "<center>
				<a href='nuevo.php'>				
					<button class='btn btn-info'><font size='4'>Agregar Documento</font></button>
				</a>
			</center>";
			}
		?>
        <!--center>
            <a href="nuevo.php">				
                <button class="btn btn-info"><font size="4">Agregar Documento</font></button>
            </a>
        </center-->
        

	</div>
    



    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script-->

</body>
</html>