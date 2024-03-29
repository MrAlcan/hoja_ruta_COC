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

	<?php
        session_start();

        if($_SESSION['area']){
            $ses = $_SESSION['area'];
            $rol = $_SESSION['rol'];
			$area_usuario = $_SESSION['nombre_area_usuario'];
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
						echo "<li class='nav-item'><a href='nuevo.php' class='nav-link active' aria-current='page'>Nuevo registro</a></li>";
                        if($rol == 1){
							echo "<li class='nav-item'><a href='flujoActual.php' class='nav-link' aria-current='page'>Flujo actual</a></li>";
							echo "<li class='nav-item'><a href='modificacion.php' class='nav-link' aria-current='page'>Modificaciones</a></li>";
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

    

    <div class="tab-content m-5">
		<div class="tab-content-inner active m-5" data-content="signup">
			<h3>Registra el nuevo registro</h3>

			<form enctype="multipart/form-data" action="llenarProcedimiento.php" name="form" method="POST" >

				<div class="row form-group">
					<div class="col-md-6">
						<?php
							require("conexion.php");
							$ultimo_id;
							$ultimo_gestion;

							$query_ul = "SELECT * FROM procedimiento ORDER BY id_procedimiento DESC LIMIT 0,1";
							
							$resp = mysqli_query($mysqli, $query_ul) or die("Error");
							
							while($row = mysqli_fetch_assoc($resp)){
								$ultimo_id = $row["codigo_hoja_ruta"];
								$ultimo_gestion = $row["gestion"];
							}
							$ultimo_id = $ultimo_id+1;
							echo "<label for='n_registros'>H.R. $ultimo_id/$ultimo_gestion</label>";
						?>
						<!--input type="number" name="n_registro" id="n_registro" class="form-control"-->
					</div>
				</div>


				<div class="row form-group">
					<div class="col-md-6">
						<label for="n_registro">N° DE REGISTRO</label>
						<?php
							
							echo "<input type='number' name='n_registro' id='n_registro' class='form-control' value='$ultimo_id' readonly>";
						?>
						<!--input type="number" name="n_registro" id="n_registro" class="form-control"-->
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="solicitante">SOLICITANTE</label>
						<input type="text" name="solicitante" id="solicitante" class="form-control">
					</div>
				</div>


													
				<div class="row form-group">
					<div class="col-md-6">
						<label for="tip_proc">Tipo de Solicitud</label>
						<select name="tip_prc" id="tip_proc" class="form-control" required="">
						<?php

							
							session_start();
							$ses = $_SESSION['id'];
							$areas = $_SESSION['area'];

							$sql=("SELECT * FROM tipo_procedimiento");

							$query=mysqli_query($mysqli,$sql);
							while($arreglo=mysqli_fetch_array($query)){
								echo "<option value='$arreglo[0]'>$arreglo[1]</option>";
							}
																
						?>
																
						</select>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="date">Fecha de ingreso</label>
						<input type="date" name="fecha" id="date" class="form-control">
					</div>
				</div>
				
				<div class="row form-group">
					<div class="col-md-6">
						<label for="desc_sol">DESCRIPCION DE LA SOLICITUD</label>
						<input type="text" name="desc_sol" id="desc_sol" class="form-control">
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="obser">OBSERVACIONES</label>
						<textarea type="textarea" name="obser" id="obser" class="form-control"></textarea>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="areas">SIGUIENTEES AREAS</label>
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
					<div class="col-md-6">
						<label for="archivo">SUBIR ARCHIVO</label>
						<input type="file" name="archivo" id="archivo" class="form-control">
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-4">
						<input type="submit" class="btn btn-primary btn-block" value="Guardar y Enviar">
					</div>
					<div class="col-md-4">
						<input type="submit" class="btn btn-primary btn-block" value="Completar">
					</div>
					<div class="col-md-4">
						<input type="submit" class="btn btn-primary btn-block" value="Cerrar">
					</div>
				</div>
				
												
				
			</form>	
		</div>

										
	</div>

    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script-->

	
</body>
</html>