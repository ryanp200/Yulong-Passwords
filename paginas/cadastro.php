<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Yulong - Cadastro</title>
        <link rel="stylesheet" href="../css/geral.css">
        <style>
    #login{
        padding: 1rem;
    }
    .caixa-login{
        padding: 1.5rem 2rem;
        max-width: 500px;
        width: 100%;
    }
    .caixa-login h2{
        margin-bottom: 1rem;
    }
    .grid-campos{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .col-total{
        grid-column: span 2;
    }
    .container-entrada{
        margin-bottom: 0.75rem;
        display: flex;
        flex-direction: column;
    }
    .container-entrada input{
        padding: 0.75rem;
        width: 100%;
    }
    .container-entrada input[type="date"]::-webkit-calendar-picker-indicator {
        display: none;
        -webkit-appearance: none;
    }
    .dica-cadastro{
        margin-top: 1rem;
    }
    @media (max-width: 480px) {
        .grid-campos {
            grid-template-columns: 1fr;
        }
        .grid-campos > section {
            grid-column: span 1 !important;
        }
    }
</style>
    </head>
    <body>
        <nav>
            <img src="../imgs/logos/yulong_logo_simplified.png" alt="Yulong Logo">
            <ol>
                <li><a href="../index.php">Home</a></li>
                <li><a href="senhas.php">Senhas</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="perfil.php">Perfil</a></li>
            </ol>
        </nav>
        <section id="login">
            <section class="caixa-login">
                <h2>Criar Conta</h2>   
                <form action="#" method="POST" id="form-cadastro">
                    <section class="grid-campos">
                        <section class="container-entrada col-total">
                            <label for="nome-completo">Nome Completo</label>
                            <input type="text" id="nome-completo" name="nome" placeholder="Digite seu nome completo" required autocomplete="name">
                        </section>

                        <section class="container-entrada">
                            <label for="data-nasc">Data de Nascimento</label>
                            <input type="date" id="data-nasc" name="data_nasc" required>
                        </section>

                        <section class="container-entrada">
                            <label for="nome">Nome de Usuário</label>
                            <input type="text" id="nome" name="username" placeholder="Escolha seu usuário" required autocomplete="username">
                        </section>

                        <section class="container-entrada col-total">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required autocomplete="email">
                        </section>

                        <section class="container-entrada">
                            <label for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required autocomplete="new-password">
                        </section>

                        <section class="container-entrada">
                            <label for="confirma-senha">Confirmar</label>
                            <input type="password" id="confirma-senha" name="confirm_password" placeholder="Repita a senha" required autocomplete="new-password">
                        </section>
                    </section>
                    <button type="submit" class="botao-enviar" style="margin-top: 1rem;">Cadastrar</button>
                    <section class="dica-cadastro">
                        Já tem cadastro? <a href="login.php">Acesse sua conta</a>
                    </section>
                </form>
            </section>
        </section>
        <script>
            const senha = document.getElementById('senha');
            const confirmaSenha = document.getElementById('confirma-senha');
            function verificarSenhas(){
                if(senha.value !== confirmaSenha.value){
                    confirmaSenha.setCustomValidity('As senhas não coincidem');
                }else{
                    confirmaSenha.setCustomValidity('');
                }
            }
            senha.addEventListener('change', verificarSenhas);
            confirmaSenha.addEventListener('keyup', verificarSenhas);
        </script>

        <?php
        require_once 'conexao.php';

        if ($conexao->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = trim($_POST['nome']);
            $data_nasc = trim($_POST['data_nasc']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $senha = $_POST['senha'];
            $confirma_snh = $_POST['confirm_password'];

            if (empty($nome) || empty($data_nasc) || empty($username) || empty($email) || empty($senha)) {
                die("Por favor, preencha todos os campos obrigatórios.");
            }

            if ($senha !== $confirma_snh) {
                die("Erro: As senhas digitadas não coincidem.");
            }

            $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nome, data_nasc, email, senha, username) VALUES (?, ?, ?, ?, ?)";

            $stmt = $conexao->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssss", $nome, $data_nasc, $email, $senha_criptografada, $username);

                if ($stmt->execute()) {
                    echo "<script>\n                    alert('Cadastro realizado com sucesso!');\n                    window.location.href = 'login.php';\n                  </script>";
                } else {
                    echo "Erro ao cadastrar usuário: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erro na preparação do banco de dados: " . $conexao->error;
            }
        }

        $conexao->close();
        ?>
    </body>
</html>