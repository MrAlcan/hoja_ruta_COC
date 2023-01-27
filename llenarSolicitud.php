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

    $id_flujo = $_GET['id_flujo'];
    $id_modificacion = $_GET['id_mod'];
    $n_hoja_ruta = $_GET['hoja_ruta'];
    $gestion = $_GET['gestion'];

    $boton = $_POST['botonSolicitud'];

    if($boton=='Aceptar'){
        $sql = "UPDATE `modificaciones` SET `id_estado_modificacion`=6 WHERE `id_modificacion`=$id_modificacion";
        $ejecutar = mysqli_query($conexion,$sql);
        if(!$ejecutar){
            echo '<script>alert("huvo algun error actualizacion de modificaciones")</script> ';
        }else{
            echo "<script>alert('Su actualizacion de modificaciones se realizo correctamente')</script> ";
            $sql_2 = "SELECT id_procedimiento_flujo FROM flujo_procedimiento WHERE id_flujo=$id_flujo";
            $ejecutar_2 = mysqli_query($conexion,$sql_2);
            $arreglo_2 = mysqli_fetch_assoc($ejecutar_2);
            $id_procedimiento = $arreglo_2['id_procedimiento_flujo'];

            $query_ul = "SELECT * FROM procedimiento ORDER BY id_procedimiento DESC LIMIT 0,1";
							
            $resp = mysqli_query($conexion, $query_ul) or die("Error");
            $ultimo_id;
                                    
            while($row = mysqli_fetch_array($resp)){
                $ultimo_id = $row["codigo_hoja_ruta"];
            }
            $ultimo_id = $ultimo_id+1;
            $ultimo_id_2 = $ultimo_id-$n_hoja_ruta;

            $sql_5 = "INSERT INTO procedimiento
            (`codigo_hoja_ruta`,`fecha_creada`,`solicitante`,`descripcion_solicitud`,`id_area_creada`,`id_tipo_procedimiento_realizado`,`gestion`)
            SELECT procedimiento.codigo_hoja_ruta+$ultimo_id_2, procedimiento.fecha_creada, procedimiento.solicitante, procedimiento.descripcion_solicitud, procedimiento.id_area_creada, procedimiento.id_tipo_procedimiento_realizado, procedimiento.gestion
            FROM procedimiento
            WHERE procedimiento.id_procedimiento=$id_procedimiento";
            $ejecutar_5 = mysqli_query($conexion,$sql_5);

            if(!$ejecutar_5){
                echo '<script>alert("huvo algun error creacion nuevo procedimiento")</script> ';
            }else{
                echo '<script>alert("creacion nuevo procedimiento exitoso")</script> ';

                $sql_6 = "SELECT id_procedimiento FROM procedimiento WHERE gestion=$gestion AND codigo_hoja_ruta=$ultimo_id";
                $ejecutar_6 = mysqli_query($conexion,$sql_6);
                $arreglo_6 = mysqli_fetch_assoc($ejecutar_6);
                $id_procedimiento_2 = $arreglo_6['id_procedimiento'];
                $id_procedimiento_resta = $id_procedimiento_2 - $id_procedimiento;

                //$arreglo_hijos[20];

                $iv=0;

                function cerrar_hijos($id_padre,$i){
                    //$arreglo_hijos[$i]=$id_padre;
                    global $conexion;
                    $i=$i+1;
                    $sql_8 = "UPDATE flujo_procedimiento SET estado_rev=4 WHERE id_flujo=$id_padre";
                    $ejecutar_8 = mysqli_query($conexion,$sql_8);

                    $sql_7 = "SELECT id_flujo FROM flujo_procedimiento WHERE id_flujo_padre=$id_padre";
                    $ejecutar_7 = mysqli_query($conexion,$sql_7);

                    while($arreglo_7 = mysqli_fetch_array($ejecutar_7)){
                        $id_padre_2 = $arreglo_7['id_flujo'];
                        cerrar_hijos($id_padre_2,$i);
                    }

                    /*if($ejecutar_7){
                        while($arreglo_7 = mysqli_fetch_array($ejecutar_7)){
                            $id_padre_2 = $arreglo_7['id_flujo'];
                            cerrar_hijos($id_padre_2);
                        }
                    }*/
                }

                cerrar_hijos($id_flujo,$iv);

                $sql_3 = "SELECT id_flujo FROM flujo_procedimiento WHERE id_procedimiento_flujo=$id_procedimiento AND estado_rev!=4";
                $ejecutar_3 = mysqli_query($conexion,$sql_3);
                while($arreglo_3 = mysqli_fetch_array($ejecutar_3)){
                    $sql_4 = "UPDATE flujo_procedimiento SET id_procedimiento_flujo=$id_procedimiento_2 WHERE id_flujo=$arreglo_3[0]";
                    $ejecutar_4 = mysqli_query($conexion,$sql_4);
                }

                $sql_9 = "UPDATE flujo_procedimiento SET estado_rev=1, id_procedimiento_flujo=$id_procedimiento_2 WHERE id_flujo=$id_flujo";
                $ejecutar_9 = mysqli_query($conexion,$sql_9);

                if(!$ejecutar_9){
                    echo '<script>alert("huvo algun error actualizar flujos")</script> ';
                }else{
                    echo "<script>alert('flujos actualizados correctamente $i')</script> ";
                    echo "<script>location.href='modificacion.php'</script>";
                }


            }

/*
            $sql_3 = "SELECT id_flujo FROM flujo_procedimiento WHERE id_procedimiento_flujo=$id_procedimiento AND estado_rev=1";
            $ejecutar_3 = mysqli_query($conexion,$sql_3);
            while($arreglo_3 = mysqli_fetch_array($ejecutar_3)){
                $sql_4 = "UPDATE flujo_procedimiento SET estado_rev=4 WHERE id_flujo=$arreglo_3[0]";
                $ejecutar_4 = mysqli_query($conexion,$sql_4);
            }

            echo "<script>location.href='modificacion.php'</script>";*/
        }

    }else{
        $sql = "UPDATE `modificaciones` SET `id_estado_modificacion`=7 WHERE `id_modificacion`=$id_modificacion";
        $ejecutar = mysqli_query($conexion,$sql);
        if(!$ejecutar){
            echo '<script>alert("huvo algun error actualizacion de modificaciones")</script> ';
        }else{
            echo "<script>alert('Su actualizacion de modificaciones se realizo correctamente')</script> ";
            echo "<script>location.href='modificacion.php'</script>";
        }
    }
/*
    $sql = "INSERT INTO modificaciones (`descripcion_modificacion`,`id_flujo_modificacion`,`id_usuario_modificacion`) VALUES ('$descripcion_cambio','$id_flujo','$id_user')";

    $ejecutar = mysqli_query($conexion,$sql);

    if(!$ejecutar){
        echo '<script>alert("huvo algun error registro de modificaciones")</script> ';
        // echo "<script>location.href='nuevo.php'</script>";	
    }else{
        echo "<script>alert('Su registro de modificaciones se realizo correctamente')</script> ";

        $sql_2 = "SELECT id_flujo FROM flujo_procedimiento WHERE id_flujo_padre = $id_flujo";
        $ejecutar_2 = mysqli_query($conexion,$sql_2);

        while($arreglo_2 = mysqli_fetch_array($ejecutar_2)){
            $id_flujo_hijo=$arreglo_2['id_flujo'];
            $sql_3 = "UPDATE `flujo_procedimiento` SET `estado_rev`=5 WHERE `id_flujo`=$id_flujo_hijo";
            $ejecutar_3 = mysqli_query($conexion,$sql_3);
        }

        echo "<script>location.href='enviados.php'</script>";
            
        //echo "<script>location.href='enviados.php'</script>";	
    }*/





    

    
     
?>