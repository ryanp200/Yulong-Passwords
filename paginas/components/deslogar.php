<?php
    if(isset($_SESSION['usuario'])){
        $_SESSION['usuario'] = "";
        header("Location: ../../index.php");
    }else{
        header("Location: ../../index.php");
    };
?>