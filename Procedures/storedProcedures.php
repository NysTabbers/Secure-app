<?php

$name = "localhost";
$user = "root";
$password = "";
$db = ""; //have to add name when I have made the db

$conn = new mysqli($name, $user, $root, $db);



function createRegisterProcedure($conn, $procedureName){
    if($conn->connect_error){
    die("Connection failed" . $conn->connect_error);
}



}

?>