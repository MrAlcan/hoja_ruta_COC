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

    $boton = $_POST['boton'];

    $n_reg = $_POST['n_registro'];
    echo "<script>console.log('$n_reg')</script>";
    $n_flujo_aaa = $_POST['n_flujo_aa'];
   /* $sig_area = $_POST['sig_area'];
    echo "<script>console.log('$sig_area')</script>";*/
    $observaciones = $_POST['obser'];

    $gestion = $_GET['gestion'];
    $id_flujo_padre = $_GET['id_flujo_padre'];

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
/*
    if($boton=='Completar'){
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=3 WHERE `id_flujo`=$n_flujo_aaa";
    }else if($boton=='Cerrar'){
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=4 WHERE `id_flujo`=$n_flujo_aaa";
    }else{
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=2 WHERE `id_flujo`=$n_flujo_aaa";
    }*/

    if($boton=='Completar'){
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=2 WHERE `id_flujo`=$n_flujo_aaa";
    }else if($boton=='Cerrar'){
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=2 WHERE `id_flujo`=$n_flujo_aaa";
    }else{
        $sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=2 WHERE `id_flujo`=$n_flujo_aaa";
    }

    //$sql_8 = "UPDATE `flujo_procedimiento` SET `estado_rev`=2 WHERE `id_flujo`=$n_flujo_aaa";
    $ejecutar_8 = mysqli_query($conexion, $sql_8);

    //sentencia sql

    $sql_3=("SELECT * FROM procedimiento WHERE codigo_hoja_ruta=$n_reg AND gestion=$gestion");

   

    $ejecutar_3=mysqli_query($conexion, $sql_3);
    $n_proc=0;

    while($arreglo_3=mysqli_fetch_array($ejecutar_3)){
        $n_proc=$arreglo_3[0];
    }
    echo "<script>console.log('$n_proc')</script>";




    //PRUEBAS PARA SUBIR ARCHIVOS
    $nombreArchivo=$_FILES['archivo']['name'];
    $tamanoArchivo=$_FILES['archivo']['size'];
    $tipoArchivo=$_FILES['archivo']['type'];
    $tempArchivo=$_FILES['archivo']['tmp_name'];
    // $caption1=$_POST['caption'];
    // $link=$_POST['link'];
/*
    $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_areas[$posic]");
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

    $nombreArchivo = $n_reg .'_'.$nombreArea.'_'.$n_flujo_aaa.'.pdf';
    $fname = date("YmdHis").'_'.$nombreArchivo;
    $directorio = "archivospdf/".$fname;

    $move =  move_uploaded_file($tempArchivo,"archivospdf/".$fname);
    if($move){

        $sql_6 = "INSERT INTO `documento_subido`(`nombre_archivo`,`directorio`,`id_usuario_subido`,`id_procedimiento_subido`,`id_flujo_subido`) VALUES ('$nombreArchivo','$directorio','$id_user','$n_proc','$n_flujo_aaa')";

        $ejecutar_6=mysqli_query($conexion, $sql_6);

        if(!$ejecutar_6){
            echo '<script>alert("huvo algun error registro de documento subido")</script> ';
                 // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            //echo '<script>alert("Su registro de documento subido se realizo correctamente ")</script> ';
    	
        }

             
    }

    if($boton=='Completar'){
        $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`estado_rev`,`id_flujo_padre`) VALUES ('$n_proc','$ses',100,'$id_user','$observaciones',3,'$n_flujo_aaa')";
    
        $ejecutar_2=mysqli_query($conexion, $sql_2); 
    
    
    
        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo "<script>alert('Su registro de flujo de procedimiento se realizo correctamente $gestion')</script> ";
    
            echo "<script>location.href='enviados.php'</script>";
                
            //echo "<script>location.href='enviados.php'</script>";	
        }
        
    }else if($boton=='Cerrar'){
        $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`id_flujo_padre`) VALUES ('$n_proc','$ses',100,'$id_user','$observaciones','$n_flujo_aaa')";
    
        $ejecutar_2=mysqli_query($conexion, $sql_2); 

        $sql_9 = "SELECT procedencia_documentos.id_flujo_prc FROM procedencia_documentos WHERE procedencia_documentos.gestion = $gestion AND procedencia_documentos.codigo_hoja_ruta = $n_reg";
        $ejecutar_9 = mysqli_query($conexion, $sql_9);

        while($arreglo_9=mysqli_fetch_array($ejecutar_9)){
            $sql_10 = "UPDATE `flujo_procedimiento` SET `estado_rev`=4 WHERE `id_flujo`=$arreglo_9[0]";
            $ejecutar_10 = mysqli_query($conexion, $sql_10);
        }
    
    
        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo "<script>alert('Su registro de flujo de procedimiento se realizo correctamente $gestion')</script> ";
    
            echo "<script>location.href='enviados.php'</script>";
                
            //echo "<script>location.href='enviados.php'</script>";	
        }
        
    }else{
        for ($posic=0; $posic < $aux; $posic++) {
            $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`id_flujo_padre`) VALUES ('$n_proc','$ses','$sig_areas[$posic]','$id_user','$observaciones','$n_flujo_aaa')";
    
            $ejecutar_2=mysqli_query($conexion, $sql_2); 
    
    
    
            if(!$ejecutar_2){
                echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
                // echo "<script>location.href='nuevo.php'</script>";	
            }else{
                echo "<script>alert('Su registro de flujo de procedimiento se realizo correctamente $gestion')</script> ";
    
                echo "<script>location.href='enviados.php'</script>";
                
                //echo "<script>location.href='enviados.php'</script>";	
            }
    
        }
        
    }

    














    
/*
    for ($posic=0; $posic < $aux; $posic++) {
        $sql_2 = "INSERT INTO `flujo_procedimiento`(`id_procedimiento_flujo`,`id_area_procedencia`,`id_area_destino`,`id_usuario_envia`,`observaciones`,`id_flujo_padre`) VALUES ('$n_proc','$ses','$sig_areas[$posic]','$id_user','$observaciones','$n_flujo_aaa')";

        $ejecutar_2=mysqli_query($conexion, $sql_2); 



        if(!$ejecutar_2){
            echo '<script>alert("huvo algun error registro de flujo de procedimiento")</script> ';
            // echo "<script>location.href='nuevo.php'</script>";	
        }else{
            echo "<script>alert('Su registro de flujo de procedimiento se realizo correctamente $gestion')</script> ";

            //PRUEBAS PARA SUBIR ARCHIVOS
            $nombreArchivo=$_FILES['archivo']['name'];
            $tamanoArchivo=$_FILES['archivo']['size'];
            $tipoArchivo=$_FILES['archivo']['type'];
            $tempArchivo=$_FILES['archivo']['tmp_name'];
            // $caption1=$_POST['caption'];
            // $link=$_POST['link'];

            $sql_4=("SELECT * FROM flujo_procedimiento WHERE id_procedimiento_flujo=$n_proc AND id_area_procedencia=$ses AND id_area_destino=$sig_areas[$posic]");
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
                    echo "<script>location.href='enviados.php'</script>";	
                }

                    
            }
            
            //echo "<script>location.href='enviados.php'</script>";	
        }

    }*/

    
     
?>