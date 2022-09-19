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
    $solicitante = $_POST['solicitante'];
    echo "<script>console.log('$solicitante')</script>";
    $tip_proc = $_POST['tip_prc'];
    echo "<script>console.log('$tip_proc')</script>";
    $fecha = $_POST['fecha'];
    echo "<script>console.log('$fecha')</script>";
    $descripcion = $_POST['desc_sol'];
    echo "<script>console.log('$descripcion')</script>";
    $sig_area = $_POST['sig_area'];
    echo "<script>console.log('$sig_area')</script>";

    //sentencia sql
    $sql = "INSERT INTO `procedimiento`(`codigo_hoja_ruta`,`fecha_creada`,`solicitante`,`descripcion_solicitud`,`id_area_creada`,`id_tipo_procedimiento_realizado`) VALUES ('$n_reg','$fecha','$solicitante','$descripcion','$ses','$tip_proc')";
    
    //$sql = "INSERT INTO procedimiento VALUES ('$n_reg','$fecha','$solicitante','$descripcion','$ses','$tip_proc')";


    $ejecutar=mysqli_query($conexion, $sql);

    



    if(!$ejecutar){
    	 echo '<script>alert("huvo algun error registro de procedimiento")</script> ';
         //echo "<script>location.href='nuevo.php'</script>";	
    }else{
        echo '<script>alert("Su registro de procedimiento se realizo correctamente ")</script> ';

        $sql_3=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_reg");
   

        $ejecutar_3=mysqli_query($conexion, $sql_3);
        $n_proc=0;

        while($arreglo_3=mysqli_fetch_array($ejecutar_3)){
            $n_proc=$arreglo_3[0];
        }
        echo "<script>console.log('$n_proc')</script>";

        $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`) VALUES ('$n_proc','$ses','$sig_area','$id_user')";

        $ejecutar_2=mysqli_query($conexion, $sql_2);

        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';
            
            echo "<script>location.href='inicio.php'</script>";	
        }

        
       // echo "<script>location.href='llenarFlujo.php'</script>";	
    }
     
?>