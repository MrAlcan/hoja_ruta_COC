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
    $observacion = $_POST['obser'];

    //PRUEBAS PARA SUBIR ARCHIVOS
    $nombreArchivo=$_FILES['archivo']['name'];
    $tamanoArchivo=$_FILES['archivo']['size'];
    $tipoArchivo=$_FILES['archivo']['type'];
    $tempArchivo=$_FILES['archivo']['tmp_name'];
    // $caption1=$_POST['caption'];
    // $link=$_POST['link'];
    

    //FIN PRUEBAS PARA SUBIR ARCHIVOS

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

        $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`) VALUES ('$n_proc','$ses','$sig_area','$id_user','$observacion')";

        $ejecutar_2=mysqli_query($conexion, $sql_2);

        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';
            //----------------------
            $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_area");
            $ejecutar_4=mysqli_query($conexion, $sql_4);
            $n_flujo_a=0;
            $fecha_subido;

            while($arreglo_4=mysqli_fetch_array($ejecutar_4)){
                $n_flujo_a=$arreglo_4[0];
                $fecha_subido=$arreglo_4[6];
            }

            $sql_5=("SELECT * FROM areas WHERE id_area=$ses");
            $ejecutar_5=mysqli_query($conexion, $sql_5);

            $nombreArea="";
            while($arreglo_5=mysqli_fetch_array($ejecutar_5)){
                $nombreArea=$arreglo_5[1];
            }

            $nombreArchivo = $n_reg .'_'.$nombreArea.'_'.$n_flujo_a;
            $fname = date("YmdHis").'_'.$nombreArchivo;
            $directorio = "archivospdf/".$fname;

            $move =  move_uploaded_file($tempArchivo,"archivospdf/".$fname);
            if($move){

                $sql_6 = "INSERT INTO `documento_subido`(`nombre_archivo`,`directorio`,`fecha_subido`,`id_usuario_subido`,`id_procedimiento_subido`,`id_flujo_subido`) VALUES ('$nombreArchivo','$directorio','$fecha_subido','$id_user','$n_proc','$n_flujo_a')";

                $ejecutar_6=mysqli_query($conexion, $sql_6);

                if(!$ejecutar_6){
                    echo '<script>alert("huvo algun error registro de documento subido")</script> ';
                    // echo "<script>location.href='nuevo.php'</script>";	
                }else{
                    echo '<script>alert("Su registro de documento subido se realizo correctamente ")</script> ';
                    echo "<script>location.href='inicio.php'</script>";	
                }

                
            }
            



            //------------------------
            
            //echo "<script>location.href='inicio.php'</script>";	
        }

        
       // echo "<script>location.href='llenarFlujo.php'</script>";	
    }
     
?>