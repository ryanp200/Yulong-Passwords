<?php
    $host = "localhost";
    $usuario_db = "root";
    $senha_db = "";
    $banco = "yulongpasswords";

    $conexao = mysqli_connect($host, $usuario_db, $senha_db, $banco) or die ("Falha na conexão com o banco de dados: ".mysqli_connect_error());
?>