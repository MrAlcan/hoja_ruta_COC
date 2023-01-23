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
        }else{
            echo "<script>location.href='index.html'</script>";
        }
        echo "<script>console.log($ses)</script>";
    ?>

    <nav class="gtco-nav" role="navigation">
		<div class="gtco-container">
			
			<div class="row">
				<div class="col-sm-4 col-xs-12">
					<div id="gtco-logo"><a href="index.html">Cooperativa <em> Apostol Santiago</em></a></div>
				</div>
				<div class="col-xs-8 text-right menu-1">
					<ul>
						<li><a href="inicio.php">Inicio</a></li>
                        <?php
                            
                            if($ses == 1){
                                echo "<li><a href='nuevo.php'>Nuevo registro</a></li>";
								echo "<li><a href='flujoActual.php'>Flujo actual</a></li>";
                            }
							if($rol == 1 && $ses==100){
                                echo "<li><a href='flujoActual.php'>Flujo actual</a></li>";
                            }else{
                                echo "<li><a href='pendientes.php'>Pendientes</a></li>";
                                echo "<li><a href='enviados.php'>Enviados</a></li>";
                            }
                            
                        
                        
                        ?>

						
						<li><a href='completados.php'>Completados</a></li>
						<li><a href='terminados.php'>Terminados</a></li>
						
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
							<th>Tipo de Solicitud</th>
							<th>Procedencia</th>
							<!--th>Fecha de Salida</th>
							<th>Hora de Salida</th>
                            <th>Precio</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

							
							$sql=("SELECT * FROM procedimiento");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 1;
                            

							while($arreglo=mysqli_fetch_array($query)){
							

                                echo "<tr>";
                                    //echo "<td>$num</td>";
                                    echo "<td>$arreglo[0]</td>";
                                    echo "<td>$arreglo[1]</td>";
                                    echo "<td>$arreglo[5]</td>";
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

			$ultimo_gestion = 0;

			//function obtenerGestion(){
				$ultimo_gestion = 0;

				$query_ul = "SELECT * FROM procedimiento ORDER BY id_procedimiento DESC LIMIT 0,1";
							
				$resp = mysqli_query($mysqli, $query_ul) or die("Error");
							
				while($row = mysqli_fetch_assoc($resp)){
					$ultimo_gestion = $row["gestion"];
				}
				$ultimo_gestion_1 = $ultimo_gestion+1;
				//echo $ultimo_gestion;
			//}
                            
            if($ses == 1){


                echo "<center>
				<a href='nuevo.php'>				
					<button class='btn btn-info'><font size='4'>Agregar Documento</font></button>
				</a>
			</center>";

			echo "<button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#staticBackdrop' onclick='obtenerGestion();'>
			Terminar Gestion
			</button>";
            }
                           
        ?>

		<!--button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="dd()">
		Terminar Gestion
		</button-->
		

		<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">¿Esta seguro de terminar la gestion?</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php
						echo "<p>El cambio de gestion de $ultimo_gestion a $ultimo_gestion_1 sera irreversible</p>";
					?>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<form action="cambiarGestion.php" method="POST">
						<input type="submit" class="btn btn-primary btn-block" value="Cambiar Gestion">
					</form>
					<!--button type="button" class="btn btn-primary">Cambiar gestion</button-->
				</div>
				</div>
			</div>
		</div>
        
        

	</div>
    



    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script-->

</body>
</html>