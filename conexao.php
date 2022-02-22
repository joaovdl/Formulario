<?php

    $con = new mysqli('localhost:3307', 'root', 'root', 'clube');

    if($con->connect_error){
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Erro: ".$con->connect_error;
        echo "</div>";
        
    }

?>