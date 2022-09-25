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

    $n_reg = $_POST['n_registro'];
    echo "<script>console.log('$n_reg')</script>";
    $sig_area = $_POST['sig_area'];
    echo "<script>console.log('$sig_area')</script>";
    $observaciones = $_POST['obser'];


    //sentencia sql

    $sql_3=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_reg");

   

    $ejecutar_3=mysqli_query($conexion, $sql_3);
    $n_proc=0;

    while($arreglo_3=mysqli_fetch_array($ejecutar_3)){
        $n_proc=$arreglo_3[0];
    }
    echo "<script>console.log('$n_proc')</script>";

    $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`) VALUES ('$n_proc','$ses','$sig_area','$id_user','$observaciones')";

    $ejecutar_2=mysqli_query($conexion, $sql_2); 



    if(!$ejecutar_2){
    	 echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
        // echo "<script>location.href='nuevo.php'</script>";	
    }else{
        echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';
        
        echo "<script>location.href='enviados.php'</script>";	
    }
     
?>