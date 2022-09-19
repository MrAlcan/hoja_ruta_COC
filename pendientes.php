<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
                        <?php
                            session_start();
                            $ses = $_SESSION['area'];
                            if($ses == 1){
                                echo "<li><a href='nuevo.php'>Nuevo registro</a></li>";
                            }
                            
                        
                        
                        ?>
						
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
							<th>Solicitante</th>
							<th>Area de Procedencia</th>
							<th>Usuario que envio</th>
							<th>Modificar</th>
							<!--th>Fecha de Salida</th-->
						</tr>
						</thead>

						<tbody>

                        <?php

                            require("conexion.php");

							
							$sql=("SELECT * FROM documentos_procedencia WHERE id_area_destino=$ses");

							$query=mysqli_query($mysqli,$sql);
							
                            $num = 1;
                            

							while($arreglo=mysqli_fetch_array($query)){
							

                                echo "<tr>";
                                    //echo "<td>$num</td>";
                                    echo "<td>$arreglo[1]</td>";
                                    echo "<td>$arreglo[2]</td>";
                                    echo "<td>$arreglo[3]</td>";
									echo "<td>$arreglo[4]</td>";
									echo "<td>$arreglo[5]</td>";


						
								


									echo "<td><a href='completar.php?n_reg=$arreglo[1]&area_p=$arreglo[4]&tipo_procedimiento=$arreglo[2]'><button class='btn btn-info'><font size='2'>Completar</font></button></a></td>";

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
    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>