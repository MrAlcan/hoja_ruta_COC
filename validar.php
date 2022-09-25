<?php

session_start();
	require("conexion.php");

	$username=$_POST['mail'];
	$pass=$_POST['pass'];
    $rolsec = 1;
	$areasec = 1;


	$sql2=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE correo='$username'");
	if($f2=mysqli_fetch_assoc($sql2)){
		if($pass==$f2['contrasena']){
            if($rolsec==$f2['id_area_usuario']){

                $_SESSION['id']=$f2['ci'];
                $_SESSION['user']=$f2['username'];
                $_SESSION['rol']=$f2['id_rol_usuario'];
				$_SESSION['area']=$f2['id_area_usuario'];

                echo '<script>alert("ADMINISTRADOR")</script> ';
                echo "<script>location.href='nuevo.php'</script>";

            }

            /*
            $_SESSION['id']=$f2['id'];
			$_SESSION['user']=$f2['user'];
			$_SESSION['rol']=$f2['rol'];

			echo '<script>alert("ADMINISTRADOR")</script> ';
			echo "<script>location.href='admin.php'</script>";
            */
			
		
		}
	}


	$sql=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE correo='$username'");
	if($f=mysqli_fetch_assoc($sql)){
		if($pass==$f['contrasena']){
			$_SESSION['id']=$f['ci'];
			$_SESSION['user']=$f['username'];
			$_SESSION['rol']=$f['id_rol_usuario'];
            $_SESSION['area']=$f['id_area_usuario'];

			if($rolsec==$f['id_rol_usuario']){
				header("Location: flujoActual.php");
			}else if($areasec==$f['id_area_usuario']){
				header("Location: enviados.php");
			} else {
				header("Location: pendientes.php");
			}

			//header("Location: inicio.php");
		}else{
			echo '<script>alert("CONTRASEÑA INCORRECTA")</script> ';
		
			echo "<script>location.href='index.html'</script>";
		}
	}else{
		
		echo '<script>alert("ESTE USUARIO NO EXISTE, PORFAVOR REGISTRESE PARA PODER INGRESAR")</script> ';
		
		echo "<script>location.href='index.html'</script>";	

	}

?>