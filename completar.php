<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTROS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

        require("conexion.php");
		session_start();
		$ses = $_SESSION['id'];
		$areas = $_SESSION['area'];

        $sql=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_registro_a");

		$query=mysqli_query($mysqli,$sql);

        $arreglo=mysqli_fetch_array($query);

        $solicitante_a = $arreglo[3];
        $descripcion_a = $arreglo[4]; 


    ?>

    <div class="tab-content">
		<div class="tab-content-inner active m-5" data-content="signup">
			<h3>Registra el nuevo registro</h3>

			<form action="llenarFlujo.php" method="POST" >


				<div class="row form-group">
					<div class="col-md-6">
						<label for="n_registro">N° DE REGISTRO</label>
                        <?php
                            echo "<input type='number' name='n_registro' id='n_registro' class='form-control' value='$n_registro_a' readonly>";
                        ?>
						
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="solicitante">SOLICITANTE</label>
                        <?php
                            echo "<input type='text' name='solicitante' id='solicitante' class='form-control' value='$solicitante_a' readonly>";
                        ?>
						
					</div>
				</div>


													
				<div class="row form-group">
					<div class="col-md-6">
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
					<div class="col-md-6">
						<label for="date">Fecha de ingreso</label>
						<input type="date" name="fecha" id="date" class="form-control">
					</div>
				</div>
				
				<div class="row form-group">
					<div class="col-md-6">
						<label for="desc_sol">DESCRIPCION DE LA SOLICITUD</label>
                        <?php
                            echo "<input type='text' name='desc_sol' id='desc_sol' class='form-control' value='$descripcion_a' readonly>";
                        ?>
						
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="obser">OBSERVACIONES</label>
						<input type="textarea" name="obser" id="obser" class="form-control">
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-6">
						<label for="sig_area">SIGUIENTE AREA</label>
						<select name="sig_area" id="sig_area" class="form-control" required="">
						<?php

							$sql2=("SELECT * FROM areas");

							$query2=mysqli_query($mysqli,$sql2);
							while($arreglo2=mysqli_fetch_array($query2)){
								echo "<option value='$arreglo2[0]'>$arreglo2[1]</option>";
							}
																
						?>
																
						</select>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md-12">
						<input type="submit" class="btn btn-primary btn-block" value="Agregar">
					</div>
				</div>
				
												
				
			</form>	
		</div>

										
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>