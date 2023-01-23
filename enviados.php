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
                            if($ses == 1){
                                echo "<li><a href='nuevo.php'>Nuevo registro</a></li>";
                            }
							if($rol == 1){
                                echo "<li><a href='flujoActual.php'>Flujo actual</a></li>";
                            }
                            
                        
                        
                        ?>
						<li><a href='completados.php'>Completados</a></li>
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

    <div class="gtco-section border-bottom">
		<div class="gtco-container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center gtco-heading">
					<h2>COOPERATIVA APOSTOL SANTIAGO</h2>
					<p>DOCUMENTOS ENVIADOS</p>
				</div>
			</div>
			<div class="row">


			<center><p>ENVIADOS</p></center>

				<div class="table-responsive">          
					<table class="table">
						<thead>
						<tr>
                            <th>N° hoja de ruta</th>
							<th>Tipo de Solicitud</th>
							<th>Solicitante</th>
							<th>Area Enviada</th>
							<th>Ver Documento</th>
							<!--th>Fecha de Salida</th>
							<th>Hora de Salida</th>
                            <th>Precio</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

							$destinoArea = 100;

							

							
							$sql=("SELECT * FROM documentos_destino WHERE id_area_procedencia=$ses AND id_area_destino!=$destinoArea AND id_area_destino!=$ses");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 1;
                            

							while($arreglo=mysqli_fetch_array($query)){

								$sql4=("SELECT documento_subido.nombre_archivo, documento_subido.directorio FROM documento_subido WHERE documento_subido.id_flujo_subido = $arreglo[0]");

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
									echo "<td><button class='btn btn-dark'><a href='verpdf.php?nombreA=$nombre_archivo&directorioA=$directorio_archivo' target='_blank'>Ver documento</a></button></td>";

                                    //echo "<td>$arreglo[3]</td>";
                                    //echo "<td>$arreglo[4]</td>";
                                echo "</tr>";
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