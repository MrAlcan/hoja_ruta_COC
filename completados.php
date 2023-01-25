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
		<div class="container-fluid">
			
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
							echo "<li class='nav-item'><a href='flujoActual.php' class='nav-link' aria-current='page'>Flujo actual</a></li>";
                            echo "<li class='nav-item'><a href='modificacion.php' class='nav-link' aria-current='page'>Modificaciones</a></li>";
                        }
						if($rol == 1 && $ses==100){
                            echo "<li class='nav-item'><a href='flujoActual.php' class='nav-link' aria-current='page'>Flujo actual</a></li>";
                        }else{
                            echo "<li class='nav-item'><a href='pendientes.php' class='nav-link' aria-current='page'>Pendientes</a></li>";
                            echo "<li class='nav-item'><a href='enviados.php' class='nav-link' aria-current='page'>Enviados</a></li>";
                        }
                    ?>
                    <li class='nav-item'><a href='completados.php' class='nav-link active' aria-current='page'>Completados</a></li>
                    <li class='nav-item'><a href='terminados.php' class='nav-link' aria-current='page'>Terminados</a></li>
					<li class='nav-item'><a href="desconectar.php" class='nav-link' aria-current='page'><font color="red">Salir</font></a></li>
				</ul>
			</div>
		</div>
	</nav>

    <div class="gtco-section border-bottom">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<h2>COOPERATIVA APOSTOL SANTIAGO</h2>
					<p>DOCUMENTOS COMPLETADOS</p>
				</div>
			</div>
			<div class="row">


			<center><p>COMPLETADOS</p></center>

				<div class="table-responsive">          
					<table class="table">
						<thead>
						<tr>
                            <th>N° hoja de ruta</th>
							<th>Tipo de Solicitud</th>
							<th>Solicitante</th>
							<th>Ultima Area</th>
                            <th>Ultimo Usuario</th>
                            <!--th>Area Destino</th-->
                            <th>Fecha Terminada</th>
                            <th>Ver Documento</th>

							<!--th>Fecha de Salida</th>
							<th>Hora de Salida</th>
                            <th>Precio</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

                            $variableTerminado=100;
                            

                            /*if($rol == 1){
                                //consulta para todos los flujos terminados
                                $sql=("SELECT * FROM flujo_actual WHERE id_area_destino = '$variableTerminado'");
                            }else{
                                //consulta para todos los demas terminados
                                $sql=("SELECT * FROM flujo_actual WHERE id_area_destino = '$variableTerminado' AND id_area_procedencia='$ses'");
                            }*/


                            if($rol == 1){
                                //consulta para todos los flujos terminados
                                $sql=("SELECT * FROM documentos_completados WHERE id_area_destino = '$variableTerminado'");
                            }else{
                                //consulta para todos los demas terminados
                                $sql=("SELECT * FROM documentos_completados WHERE id_area_destino = '$variableTerminado' AND id_area_procedencia='$ses'");
                            }


							
							//$sql=("SELECT * FROM flujo_actual");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 1;
                            

							while($arreglo=mysqli_fetch_array($query)){

                                $sql4=("SELECT documento_subido.nombre_archivo, documento_subido.directorio FROM documento_subido WHERE documento_subido.id_flujo_subido = $arreglo[10]");

								$nombre_archivo = '';
								$directorio_archivo = '';
								
								$query4=mysqli_query($mysqli,$sql4);
								while($arreglo4=mysqli_fetch_array($query4)){
									$nombre_archivo = $arreglo4[0];
									$directorio_archivo = $arreglo4[1];
								}
							

                                echo "<tr>";
                                    //echo "<td>$num</td>";
                                    echo "<td>$arreglo[1]</td>";
                                    echo "<td>$arreglo[2]</td>";
                                    echo "<td>$arreglo[3]</td>";
                                    echo "<td>$arreglo[4]</td>";
                                    echo "<td>$arreglo[5]</td>";
                                    //echo "<td>$arreglo[6]</td>";
                                    echo "<td>$arreglo[9]</td>";
                                    echo "<td><button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button></td>";

                                echo "</tr>";
                                $num = $num + 1;
							}
															
						?>



						
						</tbody>
					</table>
				</div>

			</div>
		</div>
		
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