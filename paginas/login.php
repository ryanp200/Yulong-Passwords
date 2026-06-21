<?php
    session_start();
    $usuario_logado = isset($_SESSION['usuario']);
    if ($usuario_logado) {
        header('Location: perfil.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yulong - Login</title>
    <link rel="stylesheet" href="../css/geral.css">
</head>
<body>
    <nav>
        <img src="../imgs/logos/yulong_logo_simplified.png" alt="Yulong Passwords">
        <ol>
            <li><a href="../index.php">Home</a></li>
            <li><a href="senhas.php">Senhas</a></li>
            <?php if ($usuario_logado):?>
                <li id="nav-deslogar"><a href="#">Deslogar</a></li>
            <?php else:?>
                <li><a href="login.php">Login</a></li>
            <?php endif;?>  
            <li><a href="perfil.php">Perfil</a></li>
        </ol>
    </nav>
    <section id="login">
        <section class="caixa-login">
            <h2>Acessar Conta</h2>   
            <form action="#" method="POST">
                <section class="container-entrada">
                    <label for="nome">Usuário ou Email</label>
                    <input type="text" id="nome" name="user" placeholder="Digite seu usuário ou email" required>
                </section>

                <section class="container-entrada">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </section>

                <section class="acoes-formulario">
                    <a href="recuperar.php" class="link-esqueci">Esqueceu a senha?</a>
                </section>

                <button type="submit" name="login" class="botao-enviar">Entrar</button>

                <section class="dica-cadastro">
                    Não tem cadastro? <a href="cadastro.php">Crie uma conta</a>
                </section>
            </form>
        </section>
        <?php
        require_once 'conexao.php';

        if (isset($_POST['login'])) {
            $user = $_POST['user'];
            $senha = $_POST['senha'];
            $sql = "select * from usuario where username = '$user' OR email = '$user'";
            $pesquisa = mysqli_query($conexao, $sql);
            $resultado = mysqli_fetch_array($pesquisa);

            if ($resultado){
                $senha_pesquisa = $senha.$resultado['email'];
                if (password_verify($senha_pesquisa, $resultado['senha'])) {
                    $_SESSION['id_user'] = $resultado['id_user'];
                    $_SESSION['usuario'] = $resultado['nome'];
                    header("Location: ../index.php");
                    exit();
                }else{
                    echo "<script>alert('Senha incorreta. Tente novamente.');
                    window.location.href='login.php'</script>";
                }
            }else{
                echo "<script>alert('Usuário ou email não encontrado. Tente novamente.');</script>";
            }
        }
        ?>
    </section>
    <script src="components/deslogar.js"></script>
</body>
</html>
