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
							echo "<li class='nav-item'><a href='modificacion.php' class='nav-link' aria-current='page'>Modificaciones</a></li>";
                        }
                    ?>
					<li class='nav-item'><a href='pendientes.php' class='nav-link active' aria-current='page'>Pendientes</a></li>
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
					<p>DOCUMENTOS PENDIENTES</p>
				</div>
			</div>
			<div class="row">


			<center><p>PENDIENTES</p></center>

				<div class="table-responsive">          
					<table class="table">
						<thead>
						<tr>
							<th>N° hoja de ruta</th>
							<th>Gestion</th>
							<th>Tipo de Solicitud</th>
							<th>Solicitante</th>
							<th>Area de Procedencia</th>
							<th>Usuario que envio</th>
							<th>Modificar</th>
							<th>Ver Documento</th>

							<!--th>Fecha de Salida</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

							
							$sql=("SELECT * FROM procedencia_documentos WHERE id_area_destino=$ses");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 1;
                            

							while($arreglo=mysqli_fetch_array($query)){

								$id_padre = $arreglo['id_flujo_padre'];

								$sql4=("SELECT documento_subido.nombre_archivo, documento_subido.directorio FROM documento_subido WHERE documento_subido.id_flujo_subido = $id_padre");

								$nombre_archivo = '';
								$directorio_archivo = '';
								
								$query4=mysqli_query($mysqli,$sql4);
								while($arreglo4=mysqli_fetch_array($query4)){
									$nombre_archivo = $arreglo4[0];
									$directorio_archivo = $arreglo4[1];
								}

								$gestion = $arreglo["gestion"];
							

                                echo "<tr>";
                                    //echo "<td>$num</td>";
                                    echo "<td>$arreglo[1]</td>";
									echo "<td>$gestion</td>";
                                    echo "<td>$arreglo[2]</td>";
                                    echo "<td>$arreglo[3]</td>";
									echo "<td>$arreglo[4]</td>";
									echo "<td>$arreglo[5]</td>";
									echo "<td><button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button></td>";



						
								


									echo "<td><a href='completar.php?n_flujo=$arreglo[0]&n_reg=$arreglo[1]&area_p=$arreglo[4]&tipo_procedimiento=$arreglo[2]&fecha=$arreglo[9]&gestion=$gestion&id_f_padre=$id_padre'><button class='btn btn-info'><font size='2'>Completar</font></button></a></td>";

									/*echo "<td><a href='completar.php?variable=<?php echo urlencode(`$arreglo[1]`);?>'><button class='btn btn-info'><font size='2'>Completar</font></button></a></td>";
								*/
                                    
                                echo `</tr>`;
                                $num = $num + 1;
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