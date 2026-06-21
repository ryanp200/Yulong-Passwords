<?php
    session_start();
    $usuario_logado = isset($_SESSION['usuario']);
    if($usuario_logado){
        $id_user = $_SESSION['id_user'];
        $usuario_completo = $_SESSION['usuario'];
        $usuario = explode(' ',$usuario_completo);
    }
    require_once 'conexao.php';

    if (isset($_POST['salvar_senha'])){
        $nome_servico = $_POST['nome_servico'];
        $senha_inserida = $_POST['senha_servico'];
        $sql_inserir = "insert into senhas(nome, senha, id_user)values('$nome_servico', '$senha_inserida', '$id_user')";
        if(mysqli_query($conexao, $sql_inserir)){
            echo '<script>window.location.href="senhas.php";</script>';
            exit;
        }
    }

    if (isset($_POST['editar_senha'])){
        $id_senha = $_POST['id_senha'];
        $nome_servico = $_POST['nome_servico'];
        $senha_inserida = $_POST['senha_servico'];
        $sql_editar = "update senhas set nome = '$nome_servico', senha = '$senha_inserida' where id_senhas = '$id_senha' and id_user = '$id_user'";
        if(mysqli_query($conexao, $sql_editar)){
            echo '<script>window.location.href="senhas.php";</script>';
            exit;
        }
    }

    if (isset($_POST['deletar_senha'])){
        $id_senha = $_POST['id_senha'];
        $sql_deletar = "delete from senhas where id_senhas = '$id_senha' and id_user = '$id_user'";
        if(mysqli_query($conexao, $sql_deletar)){
            echo '<script>window.location.href="senhas.php";</script>';
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador</title>
    <link rel="stylesheet" href="../css/geral.css">
    <link rel="stylesheet" href="../css/logout-modal.css">
    <style>
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .conteudo-gerenciador {
            background: rgba(12, 43, 32, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 2.5rem;
            border-radius: 8px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }
        .topo-lista {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .tabela-senhas {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            table-layout: fixed;
        }
        .tabela-senhas th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.6);
        }
        .tabela-senhas td {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.04);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .tabela-senhas tr td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            font-weight: 600;
        }
        .tabela-senhas tr td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        .th-servico {
            width: 40%;
        }
        .th-senha {
            width: 40%;
        }
        .th-acoes {
            width: 20%;
            text-align: right;
            padding-right: 1rem;
        }
        .wrapper-senha {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
            max-width: 90%;
        }
        .coluna-senha {
            color: rgba(255, 255, 255, 0.8);
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }
        .btn-ver-senha {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
            min-width: 70px;
            text-align: center;
        }
        .btn-ver-senha:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .coluna-acoes {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            align-items: center;
        }
        .btn-acao {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-editar {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-editar:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .btn-deletar {
            background-color: #771326;
            color: white;
            box-shadow: 0 4px 12px rgba(119, 19, 38, 0.3);
        }
        .btn-deletar:hover {
            background-color: #530c17;
        }
        dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(4, 59, 36, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 8px;
            width: 90%;
            max-width: 440px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            color: #ffffff;
        }
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        dialog h2 {
            margin-bottom: 2rem;
            font-size: 1.75rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: -0.5px;
        }
        .botoes-acoes {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .botao-cancelar {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .botao-cancelar:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        .mensagem-login {
            text-align: center;
            margin: auto;
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.8);
        }
        .mensagem-login a {
            color: #ffffff;
            font-weight: 600;
            text-decoration: underline;
        }
    </style>
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
    <main>
        <?php if ($usuario_logado):?>
            <section class="conteudo-gerenciador">
                <section class="topo-lista">
                    <h2 class="texto-medio texto-sombra">Suas Senhas</h2>
                    <button id="btnAbrirModal" class="botao-enviar" style="width: auto; padding: 0.75rem 1.5rem; border-radius: 6px;">Adicionar Senha</button>
                </section>
                <table class="tabela-senhas">
                    <thead>
                        <tr>
                            <th class="th-servico">Serviço / Nome</th>
                            <th class="th-senha">Senha</th>
                            <th class="th-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql_senhas_salvas = "select * from senhas where id_user = {$id_user} order by nome asc";
                            $senhas_salvas = mysqli_query($conexao, $sql_senhas_salvas);
                            if(mysqli_num_rows($senhas_salvas) == 0){
                                echo '<tr>
                                        <td colspan="3" style="text-align: center; color: rgba(255, 255, 255, 0.5); padding: 2rem;">
                                            Você não tem senhas salvas.
                                        </td>
                                    </tr>';
                            }else{
                                while($senha_linha = mysqli_fetch_array($senhas_salvas)){
                                    echo '<tr>
                                            <td>'.htmlspecialchars($senha_linha['nome']).'</td>
                                            <td>
                                                <section class="wrapper-senha">
                                                    <span class="coluna-senha" data-senha="'.htmlspecialchars($senha_linha['senha']).'">••••••••</span>
                                                    <button type="button" class="btn-ver-senha" onclick="toggleVisibilidade(this)">Mostrar</button>
                                                </section>
                                            </td>
                                            <td>
                                                <section class="coluna-acoes">
                                                    <button class="btn-acao btn-editar" onclick="abrirEditar('.$senha_linha['id_senhas'].', \''.addslashes($senha_linha['nome']).'\', \''.addslashes($senha_linha['senha']).'\')">Editar</button>
                                                    <form method="POST" action="" onsubmit="return confirm(\'Deseja realmente deletar esta senha?\');" style="margin:0;">
                                                        <input type="hidden" name="id_senha" value="'.$senha_linha['id_senhas'].'">
                                                        <button type="submit" name="deletar_senha" class="btn-acao btn-deletar">Deletar</button>
                                                    </form>
                                                </section>
                                            </td>
                                        </tr>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </section>
            <dialog id="modalSenha">
                <h2>Nova Senha</h2>
                <form method="POST" action="">
                    <section class="container-entrada">
                        <label for="nome">Nome do Serviço</label>
                        <input type="text" id="nome" name="nome_servico" required maxlength="60">
                    </section> 
                    <section class="container-entrada">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha_servico" required maxlength="100">
                    </section>
                    <section class="botoes-acoes">
                        <button type="button" id="btnFecharModal" class="botao-cancelar">Cancelar</button>
                        <button type="submit" name="salvar_senha" class="botao-enviar" style="border-radius:6px;">Salvar</button>
                    </section>
                </form>
            </dialog>
            <dialog id="modalEditarSenha">
                <h2>Editar Senha</h2>
                <form method="POST" action="">
                    <input type="hidden" id="edit_id" name="id_senha">
                    <section class="container-entrada">
                        <label for="edit_nome">Nome do Serviço</label>
                        <input type="text" id="edit_nome" name="nome_servico" required maxlength="60">
                    </section> 
                    <section class="container-entrada">
                        <label for="edit_senha">Senha</label>
                        <input type="password" id="edit_senha" name="senha_servico" required maxlength="100">
                    </section>
                    <section class="botoes-acoes">
                        <button type="button" id="btnFecharEditarModal" class="botao-cancelar">Cancelar</button>
                        <button type="submit" name="editar_senha" class="botao-enviar" style="border-radius:6px;">Salvar Alterações</button>
                    </section>
                </form>
            </dialog>
            <script>
                const modal = document.getElementById('modalSenha');
                const btnAbrir = document.getElementById('btnAbrirModal');
                const btnFechar = document.getElementById('btnFecharModal');
                
                btnAbrir.addEventListener('click',()=> {
                    modal.showModal();
                });
                btnFechar.addEventListener('click',()=> {
                    modal.close();
                });
                const modalEditar = document.getElementById('modalEditarSenha');
                const btnFecharEditar = document.getElementById('btnFecharEditarModal');
                
                function abrirEditar(id, nome, senha){
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_nome').value = nome;
                    document.getElementById('edit_senha').value = senha;
                    modalEditar.showModal();
                }
                btnFecharEditar.addEventListener('click', () => {
                    modalEditar.close();
                });

                function toggleVisibilidade(botao){
                    const spanSenha = botao.previousElementSibling;
                    const senhaReal = spanSenha.getAttribute('data-senha');
                    
                    if (botao.innerText === "Mostrar"){
                        spanSenha.innerText = senhaReal;
                        botao.innerText = "Ocultar";
                    } else{
                        spanSenha.innerText = "••••••••";
                        botao.innerText = "Mostrar";
                    }
                }
            </script>
        <?php else:?>
            <section class="mensagem-login">
                <p>Por favor, faça <a href="login.php">login</a> para gerenciar suas senhas.</p>
            </section>
        <?php endif?> 
    </main>
    <script src="components/deslogar.js"></script>
</body>
</html>