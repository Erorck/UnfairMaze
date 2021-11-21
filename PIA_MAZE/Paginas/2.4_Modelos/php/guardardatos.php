<?php

    include("conect.php");

    $con = conectar();
    $resultado;


    if($_POST){
        if(strlen($_POST['name']) >= 1 AND strlen($_POST['puntos']) >= 1){
            $name = $_POST['name'];
            $puntos = $_POST['puntos'];
            

            $consulta = "INSERT INTO Usuarios(usuario,puntuacion) VALUES ('$name', '$puntos')";

            //$consulta = "CALL agregar_puntos('$name', '$nivel', '$puntos')";

            //$resultado = $con->prepare($consulta);

            
            //$resultado->execute();
            $resultado = mysqli_query($con, $consulta);
           // header("Location: http://www.google.com/");
        }
    }

    ?>