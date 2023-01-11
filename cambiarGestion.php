<?php

    $usuario = "root"; //en ste caso root por ser localhost
    $password = "";  //contraseña por si tiene algun servicio de hosting 
    $servidor = "localhost"; //localhost por lo del xampp
    $basededatos ="correspondencia"; //nombre de la base de datos



$conexion = mysqli_connect  ($servidor,$usuario,"") or die ("Error con el servidor de la Base de datos"); 



$db = mysqli_select_db($conexion, $basededatos) or die ("Error conexion al conectarse a la Base de datos");

    session_start();
    $ses = $_SESSION['area'];
    echo "<script>console.log('$ses')</script>";
    $id_user = $_SESSION['id'];
    echo "<script>console.log('$id_user')</script>";

    $ultimo_gestion = 0;

	$query_ul = "SELECT * FROM procedimiento ORDER BY id_procedimiento DESC LIMIT 0,1";
							
	$resp = mysqli_query($conexion, $query_ul) or die("Error");
							
	while($row = mysqli_fetch_assoc($resp)){
		$ultimo_gestion = $row["gestion"];
	}

    $ultimo_gestion_1 = $ultimo_gestion+1;

    
    //ALTER TABLE flujo_procedimiento
    //MODIFY id_flujo INT NOT NULL AUTO_INCREMENT;
    //sentencia sql
    $sql = "ALTER TABLE procedimiento MODIFY gestion INT NOT NULL DEFAULT $ultimo_gestion_1";
    
    //$sql = "INSERT INTO procedimiento VALUES ('$n_reg','$fecha','$solicitante','$descripcion','$ses','$tip_proc')";


    $ejecutar=mysqli_query($conexion, $sql);

    



    if(!$ejecutar){
    	 echo '<script>alert("huvo algun error registro de procedimiento")</script> ';
         //echo "<script>location.href='nuevo.php'</script>";	
    }else{
        echo '<script>alert("Su cambio de gestion se realizo correctamente ")</script> ';
        $sql_2 = "INSERT INTO `procedimiento`(`codigo_hoja_ruta`,`fecha_creada`,`solicitante`,`descripcion_solicitud`,`id_area_creada`,`id_tipo_procedimiento_realizado`) VALUES (0,'','','',1,1)";
        $ejecutar_2=mysqli_query($conexion, $sql_2);
        echo "<script>location.href='inicio.php'</script>";

        
    }
     
?>