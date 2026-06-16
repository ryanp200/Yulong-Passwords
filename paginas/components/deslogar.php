<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_POST['confirmar_logout'])) {
    unset($_SESSION['usuario']);
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Saída</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            margin-top: 50px; 
        }
        .alerta-logout { 
            border: 1px solid #ccc; 
            padding: 20px; 
            display: inline-block; 
            border-radius: 8px; 
        }
        .botao { 
            padding: 10px 20px; 
            margin: 10px; 
            cursor: pointer; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block;
            font-size: 16px;
        }
        .botao-confirmar { 
            background-color: #dc3545; 
            color: white; 
            border: none; 
        }
        .botao-cancelar { 
            background-color: #6c757d; 
            color: white; 
        }
    </style>
</head>
<body>
    <section class="alerta-logout">
        <h2>Deseja realmente sair?</h2>
        <p>Você precisará fazer login novamente para acessar sua conta.</p> 
        <form method="POST" action="">
            <button type="submit" name="confirmar_logout" class="botao botao-confirmar">Sim, deslogar</button>
            <a href="../../index.php" class="botao botao-cancelar">Cancelar</a>
        </form>
    </section>

</body>
</html>