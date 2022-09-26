<?php
    $nombre_archivo = $_GET['nombreA'];
    $directorio_archivo = $_GET['directorioA'];


    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=$nombre_archivo");//nombre_archivo
    readfile("$directorio_archivo");//directorio
?>

