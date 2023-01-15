<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTROS</title>

	<!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet/css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"-->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


</head>
<body>

    <nav class="gtco-nav" role="navigation">
		<div class="gtco-container">
			
			<div class="row">
				<div class="col-sm-4 col-xs-12">
					<div id="gtco-logo"><a href="index.html">Cooperativa <em> Apostol Santiago</em></a></div>
				</div>
				<div class="col-xs-8 text-right menu-1">
					<ul>

						<?php
                            session_start();
                            $ses = $_SESSION['area'];
                            $rol = $_SESSION['rol'];
                            

                            if($rol == 1){
                                echo "<li><a href='flujoActual.php'>Flujo actual</a></li>";
                            }

                        ?>
						<li><a href='terminados.php'>Terminados</a></li>
						
						<li><a href="inicio.php">Inicio</a></li>
						<li><a href="pendientes.php">Pendientes</a></li>
						<li><a href="enviados.php">Enviados</a></li>
						<li><a href="desconectar.php"><font color="black">Salir</font></a></li>
					</ul>
				</div>
			</div>
			
		</div>
	</nav>

    <?php
    
        $n_registro_a=$_GET['n_reg'];
        $area_proc_a = $_GET['area_p'];
        $tipo_procedimiento_a = $_GET['tipo_procedimiento'];
		$fecha_a = $_GET['fecha'];
		$n_flujo_a = $_GET['n_flujo'];
		$gestion = $_GET['gestion'];
		$id_flujo_padre = $_GET['id_f_padre'];

        require("conexion.php");
		$ses = $_SESSION['id'];
		$areas = $_SESSION['area'];

        $sql=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_registro_a AND gestion=$gestion");

		$query=mysqli_query($mysqli,$sql);

        $arreglo=mysqli_fetch_array($query);

        $solicitante_a = $arreglo[3];
        $descripcion_a = $arreglo[4];


		$sql3 = ("SELECT procedimiento.codigo_hoja_ruta, flujo_procedimiento.observaciones, areas.nombre_area, flujo_procedimiento.id_flujo_padre FROM flujo_procedimiento INNER JOIN areas ON flujo_procedimiento.id_area_procedencia = areas.id_area INNER JOIN procedimiento ON procedimiento.id_procedimiento = flujo_procedimiento.id_procedimiento_flujo WHERE procedimiento.codigo_hoja_ruta = $n_registro_a");

		$query3 = mysqli_query($mysqli,$sql3);




    ?>

    <div class="row m-5">
		<div class="col">
			<div class="tab-content-inner active" data-content="signup">
				<h3>Registra el nuevo registro</h3>

				<form enctype="multipart/form-data"  <?php echo "action='llenarFlujo.php?gestion=$gestion&id_flujo_padre=$id_flujo_padre'";?> name="form" method="POST">

					<div class="row form-group">
						<div class="col-md-12">
							<!--label for="n_flujo_aa"></label-->
							<?php
								echo "<label for='n_flujo_aa' style='font-size:2rem'>H.R. $n_registro_a/$gestion</label>";
								echo "<input type='number' name='n_flujo_aa' id='n_flujo_aa' class='form-control' value='$n_flujo_a' style='background-color:#FFFFFF; color:#FFFFFF; border-color:#FFFFFF' readonly>";
							?>
							
						</div>
					</div>
					


					<div class="row form-group">
						<div class="col-md-12">
							<label for="n_registro">N° DE REGISTRO</label>
							<?php
								echo "<input type='number' name='n_registro' id='n_registro' class='form-control' value='$n_registro_a' readonly>";
							?>
							
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-12">
							<label for="solicitante">SOLICITANTE</label>
							<?php
								echo "<input type='text' name='solicitante' id='solicitante' class='form-control' value='$solicitante_a' readonly>";
							?>
							
						</div>
					</div>


														
					<div class="row form-group">
						<div class="col-md-12">
							<label for="tip_proc">Tipo de Solicitud</label>
							<?php
								echo "<input type='text' name='tip_prc' id='tip_proc' class='form-control' value='$tipo_procedimiento_a' readonly>";
							?>
							

							<!--select name="tip_prc" id="tip_proc" class="form-control" required=""-->
							<?php

								

								/*$sql=("SELECT * FROM tipo_procedimiento");

								$query=mysqli_query($mysqli,$sql);
								while($arreglo=mysqli_fetch_array($query)){
									echo "<option value='$arreglo[0]'>$arreglo[1]</option>";
								}*/
																	
							?>
																	
							<!--/select-->
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-12">
							<label for="date">Fecha enviada</label>
							<?php
								echo "<input type='text' name='fecha' id='fecha' class='form-control' value='$fecha_a' disabled>";
							?>
							<!--input type="date" name="fecha" id="date" class="form-control"-->
						</div>
					</div>
					
					<div class="row form-group">
						<div class="col-md-12">
							<label for="desc_sol">DESCRIPCION DE LA SOLICITUD</label>
							<?php
								echo "<input type='text' name='desc_sol' id='desc_sol' class='form-control' value='$descripcion_a' readonly>";
							?>
							
						</div>
					</div>
					
					<div class="row form-group">
						<div class="col-md-12">
							<label for="observaciones_pasadas">OBSERVACIONES PASADAS</label>
							<?php

								$observaciones_totales='';

								/*
									PRUEBA PARA MULTIPLES AREAS

								*/

								$n_flujo_padre = -1;
								$n_flujo_hijo = $n_flujo_a;

								$observaciones_areas = [];
								$observaciones_observaciones = [];

								$aux_cont = 0;

								while($n_flujo_padre!=0){
									$sql5 = "SELECT flujo_procedimiento.id_flujo, flujo_procedimiento.observaciones, areas.nombre_area, flujo_procedimiento.id_flujo_padre FROM flujo_procedimiento INNER JOIN areas ON flujo_procedimiento.id_area_procedencia = areas.id_area WHERE flujo_procedimiento.id_flujo = $n_flujo_hijo";

									$query5 = mysqli_query($mysqli,$sql5);
									while($arreglo5=mysqli_fetch_array($query5)){
										$n_flujo_hijo = $arreglo5[3];
										$n_flujo_padre = $arreglo5[3];
										$observaciones_areas[$aux_cont] = $arreglo5[2];
										$observaciones_observaciones[$aux_cont] = $arreglo5[1];
									}
									$aux_cont = $aux_cont + 1;
								}
								for ($i=$aux_cont-2; $i >=0 ; $i--) { 
									$observaciones_totales = $observaciones_totales . $observaciones_areas[$i] . ' - ' . $observaciones_observaciones[$i] . "\n";
								}
								/*

								$sql5 = "SELECT flujo_procedimiento.id_flujo, flujo_procedimiento.observaciones, areas.nombre_area, flujo_procedimiento.id_flujo_padre FROM flujo_procedimiento INNER JOIN areas ON flujo_procedimiento.id_area_procedencia = areas.id_area WHERE flujo_procedimiento.id_flujo = $n_flujo_hijo";

								$query5 = mysqli_query($mysqli,$sql5);

								$observaciones_totales = $observaciones_totales . $query5 . "\n";*/
								// FIN PRUEBA
							
								/*while($arreglo3=mysqli_fetch_array($query3)){
									$observaciones_totales = $observaciones_totales . $arreglo3[2] . ' - ' . $arreglo3[1] . "\n";
								}*/

								echo "<script>console.log('$observaciones_totales')</script>";

								echo "<textarea type='textarea' name='observaciones_pasadas' id='observaciones_pasadas' class='form-control' readonly>$observaciones_totales</textarea>";
							
							?>
							
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-12">
							<label for="obser">OBSERVACIONES</label>
							<textarea type="textarea" name="obser" id="obser" class="form-control"></textarea>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-12">
							<label for="areas">SIGUIENTES AREAS</label>
							<?php

								$sql2=("SELECT * FROM areas");
								//$contadorAreas=0;

								$query2=mysqli_query($mysqli,$sql2);
								while($arreglo2=mysqli_fetch_array($query2)){
									$check = 'check' . $arreglo2[1];
									echo "<div class='form-check'>";
									echo "<input class='form-check-input' type='checkbox' value='$arreglo2[0]' id='$check' name='$check'>";
									echo "<label class='form-check-label' for='$check'>$arreglo2[1]</label>";
									echo "</div>";

								}

								
							?>

						</div>
					</div>

					

					<!--div class="row form-group">
						<div class="col-md-6">
							<label for="sig_area">SIGUIENTE AREA</label>
							<select name="sig_area" id="sig_area" class="form-control" required="">
							<?php/*

								$sql2=("SELECT * FROM areas");

								$query2=mysqli_query($mysqli,$sql2);
								while($arreglo2=mysqli_fetch_array($query2)){
									echo "<option value='$arreglo2[0]'>$arreglo2[1]</option>";
								}*/
																	
							?>
																	
							</select>
						</div>
					</div-->

					<div class="row form-group">
						<div class="col-md-12">
							<label for="archivo">SUBIR ARCHIVO</label>
							<input type="file" name="archivo" id="archivo" class="form-control">
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-4">
							<input type="submit" class="btn btn-primary btn-block" name="boton" value="Guardar y Enviar">
						</div>
						<div class="col-md-4">
							<input type="submit" class="btn btn-primary btn-block" name="boton" value="Completar">
						</div>
						<div class="col-md-4">
							<input type="submit" class="btn btn-primary btn-block" name="boton" value="Cerrar">
						</div>
					</div>
					
													
					
				</form>

				<?php
				
					$sql4=("SELECT documento_subido.nombre_archivo, documento_subido.directorio FROM documento_subido WHERE documento_subido.id_flujo_subido = $id_flujo_padre");

					$nombre_archivo = '';
					$directorio_archivo = '';
					
					$query4=mysqli_query($mysqli,$sql4);
					while($arreglo4=mysqli_fetch_array($query4)){
						$nombre_archivo = $arreglo4[0];
						$directorio_archivo = $arreglo4[1];
					}
				
				?>
				<!--div class="row form-group">
					<div class="col-md-12">
						<?php
							echo "<h4>$nombre_archivo</h4>";/*
							echo "<button class='alert-success'><a href='descargar.php?nombreA=<?php echo $nombre_archivo;?>&directorioA=<?php echo $directorio_archivo?>'>Descargar</a></button>";*/
							echo "<button class='alert-success'><a href='descargar.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo'>Descargar</a></button>";

						?>
						
						
					</div>
				</div-->

				<div class="row form-group">
					<div class="col-md-12">
						<h4>Ver online</h4>
						<?php
							echo "<h4>$nombre_archivo</h4>";/*
							echo "<button class='alert-success'><a href='descargar.php?nombreA=<?php echo $nombre_archivo;?>&directorioA=<?php echo $directorio_archivo?>'>Descargar</a></button>";*/
							echo "<button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button>";

						?>
						
						
					</div>
				</div>


			</div>
		</div>
		

		<div class="col">
			<h3>Otros registros de la hoja de ruta</h3>

			


		</div>

										
	</div>

    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script-->

</body>
</html>