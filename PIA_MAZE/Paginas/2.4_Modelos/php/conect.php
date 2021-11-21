<?php

function conectar(){

    $user = "root";
    $pass = "";
    $server = "localhost";
    $db = "GRAFICAS";
    $con = mysqli_connect($server, $user, $pass, $db) or die ("Error al conectar a la base de datos".msql_error());

    return $con;
}

?>