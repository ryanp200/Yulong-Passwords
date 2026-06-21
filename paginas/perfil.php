<?php
    session_start();
    $usuario_logado = isset($_SESSION['usuario']);
    if (!$usuario_logado) {
        header('Location: login.php');
        exit();
    }
    require_once 'conexao.php';
    $id_user = $_SESSION['id_user'];
    if(isset($_POST['salvar_perfil'])) {
        $nome = trim($_POST['nome']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $data_nasc = trim($_POST['data_nasc']);

        if (!empty($nome) && !empty($username) && !empty($email) && !empty($data_nasc)) {
            $nome_esc = $conexao->real_escape_string($nome);
            $username_esc = $conexao->real_escape_string($username);
            $email_esc = $conexao->real_escape_string($email);

            $sql_update = "update usuario set nome='{$nome_esc}', username='{$username_esc}', email='{$email_esc}', data_nasc='{$data_nasc}' where id_user='$id_user'";
            
            if (mysqli_query($conexao, $sql_update)) {
                $_SESSION['usuario'] = $nome; 
                echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='perfil.php';</script>";
                exit();
            }else{
                echo "<script>alert('Erro ao atualizar o perfil.');</script>";
            }
        }else{
            echo "<script>alert('Por favor, preencha todos os campos.');</script>";
        }
    }

    $sql = "select nome, username, email, data_nasc from usuario where id_user='$id_user'";
    $pesquisa = mysqli_query($conexao,$sql);
    $dados_usuario = mysqli_fetch_array($pesquisa);
    if (!$dados_usuario) {
        die("Erro ao carregar os dados do perfil.");
    }
    $partes_nome = explode(' ', $dados_usuario['nome']);
    $primeiro_nome = $partes_nome[0];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yulong - Perfil</title>
    <link rel="stylesheet" href="../css/geral.css">
    <link rel="stylesheet" href="../css/logout-modal.css">
    <style>
        #perfil-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
        }
        .caixa-perfil {
            background: rgba(4, 59, 36, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 3rem 2.5rem;
            border-radius: 4px;
            width: 100%;
            max-width: 550px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
        }
        .cabecalho-perfil {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .avatar-falso {
            width: 80px;
            height: 80px;
            background-color: #137751;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            font-weight: 700;
            border-radius: 4px;
            margin: 0 auto 1rem auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(19, 119, 81, 0.3);
        }
        .cabecalho-perfil h2 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }
        .cabecalho-perfil p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }
        .info-grupo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .campo-visualizacao {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .campo-visualizacao.total {
            grid-column: span 2;
        }
        .campo-visualizacao span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.5);
        }
        .campo-visualizacao input {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            outline: none;
            width: 100%;
            transition: all 0.3s ease;
        }
        .campo-visualizacao input:focus {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
        }
        .campo-visualizacao input:disabled {
            background: rgba(255, 255, 255, 0.02);
            color: rgba(255, 255, 255, 0.6);
            border-color: rgba(255, 255, 255, 0.05);
            cursor: not-allowed;
        }
        .botoes-perfil {
            display: flex;
            gap: 1rem;
        }
        .botao-acao {
            flex: 1;
            padding: 1rem;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .botao-editar, .botao-salvar {
            background-color: #137751;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(19, 119, 81, 0.3);
        }
        .botao-editar:hover, .botao-salvar:hover {
            background-color: #0c5338;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(10, 102, 79, 0.4);
        }
        .botao-voltar {
            background-color: rgba(255, 255, 255, 0.06);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .botao-voltar:hover {
            background-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }
        @media (max-width: 480px) {
            .info-grupo {
                grid-template-columns: 1fr;
            }
            .campo-visualizacao {
                grid-column: span 1 !important;
            }
            .botoes-perfil {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav>
        <img src="../imgs/logos/yulong_logo_simplified.png" alt="Yulong Passwords">
        <ol>
            <li><a href="../index.php">Home</a></li>
            <li><a href="senhas.php">Senhas</a></li>
            <li id="nav-deslogar"><a href="#">Deslogar</a></li>
            <li><a href="perfil.php">Perfil</a></li>
        </ol>
    </nav>
    <section id="perfil-container">
        <section class="caixa-perfil">
            <section class="cabecalho-perfil">
                <section class="avatar-falso">
                    <?php echo strtoupper(substr($primeiro_nome, 0, 1)); ?>
                </section>
                <h2>Meu Perfil</h2>
                <p>Gerencie as informações da sua conta corporativa</p>
            </section>
            
            <form action="perfil.php" method="POST" id="form-perfil">
                <section class="info-grupo">
                    <section class="campo-visualizacao total">
                        <span>Nome Completo</span>
                        <input type="text" name="nome" value="<?php echo htmlspecialchars($dados_usuario['nome']); ?>" disabled required>
                    </section>
                    <section class="campo-visualizacao">
                        <span>Nome de Usuário</span>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($dados_usuario['username']); ?>" disabled required>
                    </section>
                    <section class="campo-visualizacao">
                        <span>Data de Nascimento</span>
                        <input type="date" name="data_nasc" value="<?php echo $dados_usuario['data_nasc']; ?>" disabled required>
                    </section>
                    <section class="campo-visualizacao total">
                        <span>Endereço de E-mail</span>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($dados_usuario['email']); ?>" disabled required>
                    </section>
                </section>
                
                <section class="botoes-perfil">
                    <a href="../index.php" class="botao-acao botao-voltar" id="btn-voltar">Voltar ao Início</a>
                    <button type="button" class="botao-acao botao-editar" id="btn-editar" onclick="ativarEdicao()">Editar Dados</button>
                    <button type="submit" name="salvar_perfil" class="botao-acao botao-salvar" id="btn-salvar" style="display: none;">Salvar Alterações</button>
                </section>
            </form>

        </section>
    </section>
    <script>
        function ativarEdicao(){
            const inputs =document.querySelectorAll('#form-perfil input');
            inputs.forEach(input => input.removeAttribute('disabled'));
            document.getElementById('btn-editar').style.display = 'none';
            document.getElementById('btn-salvar').style.display = 'block';
            const btnVoltar = document.getElementById('btn-voltar');
            btnVoltar.textContent = 'Cancelar';
            btnVoltar.href = 'perfil.php'; 
        }
        document.getElementById('form-perfil').addEventListener('submit', function() {
            const inputs = document.querySelectorAll('#form-perfil input');
            inputs.forEach(input => input.removeAttribute('disabled'));
        });
    </script>
    <script src="components/deslogar.js"></script>
</body>
</html>