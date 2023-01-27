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

    $id_flujo = $_GET['id_flujo_cambio'];

    $descripcion_cambio = $_POST['solicitud_cambio'];

    $sql = "INSERT INTO modificaciones (`descripcion_modificacion`,`id_flujo_modificacion`,`id_usuario_modificacion`) VALUES ('$descripcion_cambio','$id_flujo','$id_user')";

    $ejecutar = mysqli_query($conexion,$sql);

    if(!$ejecutar){
        echo '<script>alert("huvo algun error registro de modificaciones")</script> ';
        // echo "<script>location.href='nuevo.php'</script>";	
    }else{
        echo "<script>alert('Su registro de modificaciones se realizo correctamente')</script> ";

        /*$sql_2 = "SELECT id_flujo FROM flujo_procedimiento WHERE id_flujo_padre = $id_flujo";
        $ejecutar_2 = mysqli_query($conexion,$sql_2);

        while($arreglo_2 = mysqli_fetch_array($ejecutar_2)){
            $id_flujo_hijo=$arreglo_2['id_flujo'];
            $sql_3 = "UPDATE `flujo_procedimiento` SET `estado_rev`=5 WHERE `id_flujo`=$id_flujo_hijo";
            $ejecutar_3 = mysqli_query($conexion,$sql_3);
        }*/

        echo "<script>location.href='enviados.php'</script>";
            
        //echo "<script>location.href='enviados.php'</script>";	
    }





    

    
     
?>