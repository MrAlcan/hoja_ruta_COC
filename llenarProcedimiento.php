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

    $ultimo_id;
    $gestion;

	$query_ul = "SELECT * FROM procedimiento ORDER BY id_procedimiento DESC LIMIT 0,1";
							
	$resp = mysqli_query($conexion, $query_ul) or die("Error");
							
	while($row = mysqli_fetch_assoc($resp)){
		$ultimo_id = $row["codigo_hoja_ruta"];
        $gestion = $row["gestion"];
	}
	$ultimo_id = $ultimo_id+1;

    //$n_reg = $_POST['n_registro'];
    $n_reg = $ultimo_id;
    echo "<script>console.log('$n_reg')</script>";
    $solicitante = $_POST['solicitante'];
    echo "<script>console.log('$solicitante')</script>";
    $tip_proc = $_POST['tip_prc'];
    echo "<script>console.log('$tip_proc')</script>";
    $fecha = $_POST['fecha'];
    echo "<script>console.log('$fecha')</script>";
    $descripcion = $_POST['desc_sol'];
    echo "<script>console.log('$descripcion')</script>";
    //$sig_area = $_POST['sig_area'];
    //echo "<script>console.log('$sig_area')</script>";

    $sql_7 = "SELECT * FROM areas";
    $ejecutar_7=mysqli_query($conexion, $sql_7);
    $sig_areas;
    $aux=0;

    while($arreglo_7=mysqli_fetch_array($ejecutar_7)){
        $check = 'check' . $arreglo_7[1];
        if(isset($_POST[$check])){
            $sig_areas[$aux]=$_POST[$check];
            $aux=$aux+1;
        }
    }





    

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

        $sql_3=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_reg AND gestion=$gestion");
   

        $ejecutar_3=mysqli_query($conexion, $sql_3);
        $n_proc=0;

        while($arreglo_3=mysqli_fetch_array($ejecutar_3)){
            $n_proc=$arreglo_3[0];
        }
        echo "<script>console.log('$n_proc')</script>";

        $sql_8 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`estado_rev`) VALUES ('$n_proc','$ses','$ses','$id_user','nula',2)";
        $ejecutar_8 = mysqli_query($conexion, $sql_8);

        $sql_9 = "SELECT flujo_procedimiento.id_flujo, flujo_procedimiento.fecha_subido FROM flujo_procedimiento WHERE flujo_procedimiento.id_procedimiento_flujo = $n_proc";
        $ejecutar_9=mysqli_query($conexion, $sql_9);
        $n_flujo_padre=0;
        $fecha_subido;

        while($arreglo_9=mysqli_fetch_array($ejecutar_9)){
            $n_flujo_padre=$arreglo_9[0];
            $fecha_subido=$arreglo_9[1];
        }


/*
        $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_areas[$posic]");//555
        $ejecutar_4=mysqli_query($conexion, $sql_4);
        $n_flujo_a=0;
        $fecha_subido;

        while($arreglo_4=mysqli_fetch_array($ejecutar_4)){
            $n_flujo_a=$arreglo_4[0];
            $fecha_subido=$arreglo_4[6];
        }*/

        $sql_5=("SELECT * FROM areas WHERE id_area=$ses");
        $ejecutar_5=mysqli_query($conexion, $sql_5);

        $nombreArea="";
        while($arreglo_5=mysqli_fetch_array($ejecutar_5)){
            $nombreArea=$arreglo_5[1];
        }

        $nombreArchivo = $n_reg .'_'.$nombreArea.'_'.$n_flujo_padre.'.pdf';
        $fname = date("YmdHis").'_'.$nombreArchivo;
        $directorio = "archivospdf/".$fname;

        $move =  move_uploaded_file($tempArchivo,"archivospdf/".$fname);
        if($move){

            $sql_6 = "INSERT INTO `documento_subido`(`nombre_archivo`,`directorio`,`fecha_subido`,`id_usuario_subido`,`id_procedimiento_subido`,`id_flujo_subido`) VALUES ('$nombreArchivo','$directorio','$fecha_subido','$id_user','$n_proc','$n_flujo_padre')";

            $ejecutar_6=mysqli_query($conexion, $sql_6);

            if(!$ejecutar_6){
                echo '<script>alert("huvo algun error registro de documento subido")</script> ';
                // echo "<script>location.href='nuevo.php'</script>";	
            }else{
                //echo '<script>alert("Su registro de documento subido se realizo correctamente ")</script> ';
                
            }

                    
        }

        for ($posic=0; $posic < $aux; $posic++) {
            $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`id_flujo_padre`) VALUES ('$n_proc','$ses','$sig_areas[$posic]','$id_user','$observacion','$n_flujo_padre')";//555
    
            $ejecutar_2=mysqli_query($conexion, $sql_2);
    
            if(!$ejecutar_2){
                echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
                // echo "<script>location.href='nuevo.php'</script>";	
            }else{
                echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';
                echo "<script>location.href='inicio.php'</script>";	
                    //echo "<script>location.href='inicio.php'</script>";	
            }
            
        }


/*

        for ($posic=0; $posic < $aux; $posic++) {
            $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`id_flujo_padre`) VALUES ('$n_proc','$ses','$sig_areas[$posic]','$id_user','$observacion','$n_flujo_padre')";//555

            $ejecutar_2=mysqli_query($conexion, $sql_2);

            if(!$ejecutar_2){
                echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
                // echo "<script>location.href='nuevo.php'</script>";	
            }else{
                echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';*/
                //----------------------
                /*
                $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_areas[$posic]");//555
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

                $nombreArchivo = $n_reg .'_'.$nombreArea.'_'.$n_flujo_a.'.pdf';
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
                */



                //------------------------
                
                //echo "<script>location.href='inicio.php'</script>";	
            /*}
            
        }*/

        /*$sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`) VALUES ('$n_proc','$ses','$sig_area','$id_user','$observacion')";//555

        $ejecutar_2=mysqli_query($conexion, $sql_2);

        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo '<script>alert("Su registro de flujo de procedimiento se realizo correctamente ")</script> ';
            //----------------------
            $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_area");//555
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

            $nombreArchivo = $n_reg .'_'.$nombreArea.'_'.$n_flujo_a.'.pdf';
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
        }*/

        
       // echo "<script>location.href='llenarFlujo.php'</script>";	
    }
     
?>