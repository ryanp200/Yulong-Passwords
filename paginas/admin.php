<?php
session_start();

$usuario_logado = isset($_SESSION['usuario']);
$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 0;

if (!$usuario_logado || !$id_user) {
    header('Location: ../index.php');
    exit;
}

require_once 'conexao.php';

$sql_checa_status = "select hierarquia, privilegio from usuario where id_user = $id_user";
$query_status = mysqli_query($conexao, $sql_checa_status);
$dados_status = mysqli_fetch_assoc($query_status);

if (!$dados_status || (intval($dados_status['hierarquia']) < 2 && $dados_status['privilegio'] !== 'admin')) {
    unset($_SESSION['hierarquia']);
    header('Location: ../index.php');
    exit;
}

$minha_hierarquia = intval($dados_status['hierarquia']);
$meu_privilegio = $dados_status['privilegio'];
$_SESSION['hierarquia'] = $minha_hierarquia;

$usuario_completo = $_SESSION['usuario'];
$usuario = explode(' ', $usuario_completo);

$sql_admin = "select nome, username, email from usuario where id_user = $id_user";
$query_admin = mysqli_query($conexao, $sql_admin);
$dados_admin = mysqli_fetch_assoc($query_admin);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['alterar_privilegio'])) {
    $id_usuario_alvo = intval($_POST['id_usuario_alvo']);
    $acao = $_POST['acao_solicitada'];

    if ($id_usuario_alvo !== $id_user) {
        $sql_alvo = "select hierarquia, privilegio from usuario where id_user = $id_usuario_alvo";
        $query_alvo = mysqli_query($conexao, $sql_alvo);
        $dados_alvo = mysqli_fetch_assoc($query_alvo);

        if ($dados_alvo) {
            $hierarquia_alvo = intval($dados_alvo['hierarquia']);
            $privilegio_alvo = $dados_alvo['privilegio'];

            if ($minha_hierarquia === 1 && $meu_privilegio === 'admin') {
                header('Location: admin.php');
                exit;
            }

            if ($minha_hierarquia === 2) {
                if ($hierarquia_alvo === 1) {
                    if ($acao === 'tornar_admin') {
                        $sql_update = "update usuario set privilegio = 'admin' where id_user = $id_usuario_alvo";
                        mysqli_query($conexao, $sql_update);
                    } elseif ($acao === 'remover_admin') {
                        $sql_update = "update usuario set privilegio = null where id_user = $id_usuario_alvo";
                        mysqli_query($conexao, $sql_update);
                    }
                }
            }

            if ($minha_hierarquia === 3) {
                if ($acao === 'tornar_admin') {
                    $sql_update = "update usuario set hierarquia = 2, privilegio = 'admin' where id_user = $id_usuario_alvo";
                    mysqli_query($conexao, $sql_update);
                } elseif ($acao === 'remover_admin') {
                    $sql_update = "update usuario set hierarquia = 1, privilegio = null where id_user = $id_usuario_alvo";
                    mysqli_query($conexao, $sql_update);
                } elseif ($acao === 'alterar_nivel') {
                    $novo_nivel = intval($_POST['novo_nivel']) === 2 ? 2 : 1;
                    $sql_update = "update usuario set hierarquia = $novo_nivel where id_user = $id_usuario_alvo";
                    mysqli_query($conexao, $sql_update);
                } elseif ($acao === 'editar_usuario') {
                    $nome_editado = mysqli_real_escape_string($conexao, $_POST['nome']);
                    $username_editado = mysqli_real_escape_string($conexao, $_POST['username']);
                    $email_editado = mysqli_real_escape_string($conexao, $_POST['email']);
                    $senha_editada = $_POST['senha'];

                    if (!empty($senha_editada)) {
                        $senha_hash = password_hash($senha_editada, PASSWORD_DEFAULT);
                        $sql_update = "update usuario set nome = '$nome_editado', username = '$username_editado', email = '$email_editado', senha = '$senha_hash' where id_user = $id_usuario_alvo";
                    } else {
                        $sql_update = "update usuario set nome = '$nome_editado', username = '$username_editado', email = '$email_editado' where id_user = $id_usuario_alvo";
                    }
                    mysqli_query($conexao, $sql_update);
                }
            }
        }
    }

    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yulong - Painel Admin</title>
    <link rel="stylesheet" href="../css/geral.css">
    <link rel="stylesheet" href="../css/logout-modal.css">
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        main{
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1.5rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .card-admin-logado{
            background: rgba(12, 43, 32, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 1.5rem 2rem;
            width: 100%;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
        }

        .info-admin{
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .badge-admin{
            background-color: #137751;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            width: fit-content;
            box-shadow: 0 4px 12px rgba(19, 119, 81, 0.3);
        }

        .info-admin h3{
            font-size: 1.4rem;
            color: #ffffff;
            margin: 0;
            font-weight: 600;
        }

        .info-admin p{
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .conteudo-gerenciador{
            background: rgba(4, 59, 36, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 2.5rem;
            border-radius: 4px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
            box-sizing: border-box;
        }

        .topo-lista{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1.2rem;
        }

        .topo-lista h2{
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }

        /* GRID DE CARDS REAIS */
        .container-usuarios {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card-usuario {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-usuario:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .card-header-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 0.8rem;
        }

        .card-corpo {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .dado-item {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            min-width: 0;
        }

        .dado-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
            font-weight: 600;
        }

        .dado-valor {
            color: #ffffff;
            font-size: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dado-valor.id-badge {
            color: rgba(255, 255, 255, 0.4);
            font-family: monospace;
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.1rem 0.4rem;
            border-radius: 3px;
        }

        .badge-status {
            background: rgba(19, 119, 81, 0.15);
            padding: 0.35rem 0.7rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            width: fit-content;
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.2);
        }

        .coluna-acoes {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .coluna-acoes form {
            flex: 1;
            min-width: fit-content;
        }

        .btn-toggle-admin, .btn-toggle-user, .btn-editar {
            width: 100%;
            padding: 0.55rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
            text-align: center;
            box-sizing: border-box;
        }

        .btn-toggle-admin {
            background-color: #137751;
            color: white;
            box-shadow: 0 4px 12px rgba(19, 119, 81, 0.2);
        }
        .btn-toggle-admin:hover {
            background-color: #0c5338;
        }

        .btn-toggle-user {
            background-color: transparent;
            color: rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .btn-toggle-user:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .btn-editar {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.15);
        }
        .btn-editar:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .select-hierarquia {
            background: rgba(12, 43, 32, 0.9);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            width: 100%;
            height: 32px;
            outline: none;
            box-sizing: border-box;
        }

        .texto-protegido {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.4);
            font-style: italic;
            width: 100%;
            text-align: center;
            padding: 0.4rem 0;
        }

        .modal-edicao {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(12, 43, 32, 0.7);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            padding: 1rem;
            box-sizing: border-box;
        }

        .modal-conteudo {
            background: rgba(12, 43, 32, 0.95);
            padding: 2.5rem;
            border-radius: 4px;
            width: 100%;
            max-width: 480px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
            box-sizing: border-box;
            animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn{
            from{ transform: scale(0.97); opacity: 0; }
            to{ transform: scale(1); opacity: 1; }
        }

        .modal-conteudo h2{
            margin-top: 0;
            color: white;
            margin-bottom: 1.8rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .form-grupo{
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
        }

        .form-grupo label{
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .form-grupo input{
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-grupo input:focus{
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05);
        }

        .modal-botoes{
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 2rem;
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
    <main>
        <?php if($dados_admin): ?>
            <section class="card-admin-logado">
                <section class="info-admin">
                    <span class="badge-admin">Nível <?php echo $minha_hierarquia; ?> <?php echo ($minha_hierarquia===1)?'(Mod)':'Admin'; ?></span>
                    <h3><?php echo htmlspecialchars($dados_admin['nome']); ?></h3>
                    <p>Username: @<?php echo htmlspecialchars($dados_admin['username']); ?></p>
                </section>
            </section>
        <?php endif; ?>

        <section class="conteudo-gerenciador">
            <section class="topo-lista">
                <h2>Gerenciamento de Usuários</h2>
            </section>
            
            <section class="container-usuarios">
                <?php
                $sql_usuarios = "select id_user, nome, username, email, hierarquia, privilegio from usuario where id_user != $id_user order by nome asc";
                $resultado = mysqli_query($conexao, $sql_usuarios);
                
                if(mysqli_num_rows($resultado) === 0){
                    echo '<section style="text-align: center; color: rgba(255,255,255,0.6); padding: 3rem; grid-column: 1/-1;">Nenhum outro usuário cadastrado.</section>';
                } else {
                    while($user_linha = mysqli_fetch_assoc($resultado)){
                        $hierarquia_alvo = intval($user_linha['hierarquia']);
                        $privilegio_alvo = $user_linha['privilegio'];
                        $is_target_admin = ($privilegio_alvo === 'admin' || $hierarquia_alvo >= 2);
                        $status_texto = "H: ".$hierarquia_alvo." | ".($privilegio_alvo ? strtoupper($privilegio_alvo) : 'USER');
                        
                        echo '<article class="card-usuario">';
                        
                        // Header do Card (Nome, ID e Badge de Status)
                        echo '<div class="card-header-info">';
                        echo '  <div class="dado-item">';
                        echo '      <span class="dado-valor" style="font-weight:700; font-size:1.1rem;" title="'.htmlspecialchars($user_linha['nome']).'">'.htmlspecialchars($user_linha['nome']).'</span>';
                        echo '      <span class="dado-valor" style="font-size:0.85rem; color:rgba(255,255,255,0.6);">@'.htmlspecialchars($user_linha['username']).'</span>';
                        echo '  </div>';
                        echo '  <span class="dado-valor id-badge">#'.htmlspecialchars($user_linha['id_user']).'</span>';
                        echo '</div>';
                        
                        // Corpo do Card (E-mail e Nível)
                        echo '<div class="card-corpo">';
                        echo '  <div class="dado-item">';
                        echo '      <span class="dado-label">E-mail</span>';
                        echo '      <span class="dado-valor" title="'.htmlspecialchars($user_linha['email']).'">'.htmlspecialchars($user_linha['email']).'</span>';
                        echo '  </div>';
                        echo '  <div class="dado-item">';
                        echo '      <span class="dado-label">Nível de Acesso</span>';
                        echo '      <span class="badge-status">'.$status_texto.'</span>';
                        echo '  </div>';
                        echo '</div>';
                        
                        // Rodapé do Card (Ações do painel)
                        echo '<div class="coluna-acoes">';
                        if($minha_hierarquia === 1 && $meu_privilegio === 'admin'){
                            echo '<span class="texto-protegido">Sem permissão</span>';
                        } elseif($minha_hierarquia === 2){
                            if($hierarquia_alvo === 1){
                                $acao = $is_target_admin ? 'remover_admin' : 'tornar_admin';
                                $texto_botao = $is_target_admin ? 'Remover Admin' : 'Tornar Admin';
                                $classe_botao = $is_target_admin ? 'btn-toggle-user' : 'btn-toggle-admin';
                                echo '<form method="POST" action="">
                                <input type="hidden" name="id_usuario_alvo" value="'.$user_linha['id_user'].'">
                                <input type="hidden" name="acao_solicitada" value="'.$acao.'">
                                <button type="submit" name="alterar_privilegio" class="'.$classe_botao.'">'.$texto_botao.'</button>
                                </form>';
                            } else {
                                echo '<span class="texto-protegido">Protegido</span>';
                            }
                        } elseif($minha_hierarquia === 3){
                            if($hierarquia_alvo === 3){
                                echo '<span class="texto-protegido">Super Admin</span>';
                            } else {
                                $acao = $is_target_admin ? 'remover_admin' : 'tornar_admin';
                                $texto_botao = $is_target_admin ? 'Remover Admin' : 'Tornar Admin';
                                $classe_botao = $is_target_admin ? 'btn-toggle-user' : 'btn-toggle-admin';
                                
                                echo '<button type="button" class="btn-editar" style="flex:1;" onclick="abrirModalEdicao('.$user_linha['id_user'].',\''.htmlspecialchars($user_linha['nome'],ENT_QUOTES).'\',\''.htmlspecialchars($user_linha['username'],ENT_QUOTES).'\',\''.htmlspecialchars($user_linha['email'],ENT_QUOTES).'\')">Editar</button>';
                                
                                echo '<form method="POST" action="">
                                <input type="hidden" name="id_usuario_alvo" value="'.$user_linha['id_user'].'">
                                <input type="hidden" name="acao_solicitada" value="'.$acao.'">
                                <button type="submit" name="alterar_privilegio" class="'.$classe_botao.'">'.$texto_botao.'</button>
                                </form>';
                                
                                echo '<form method="POST" action="" style="flex: 1; min-width: 80px;">
                                <input type="hidden" name="id_usuario_alvo" value="'.$user_linha['id_user'].'">
                                <input type="hidden" name="acao_solicitada" value="alterar_nivel">
                                <select name="novo_nivel" class="select-hierarquia" onchange="this.form.submit()">
                                <option value="1" '.($hierarquia_alvo===1?'selected':'').'>H: 1</option>
                                <option value="2" '.($hierarquia_alvo===2?'selected':'').'>H: 2</option>
                                </select>
                                <input type="hidden" name="alterar_privilegio" value="1">
                                </form>';
                            }
                        }
                        echo '</div>'; 
                        echo '</article>'; // Fim do card-usuario
                    }
                }
                ?>
            </section>
        </section>
    </main>

    <section id="modalEdicaoUsuario" class="modal-edicao">
        <section class="modal-conteudo">
            <h2>Editar Usuário</h2>
            <form method="POST" action="">
                <input type="hidden" name="id_usuario_alvo" id="modal_id_user">
                <input type="hidden" name="acao_solicitada" value="editar_usuario">
                <section class="form-grupo">
                    <label for="modal_nome">Nome</label>
                    <input type="text" name="nome" id="modal_nome" required>
                </section>
                <section class="form-grupo">
                    <label for="modal_username">Usuário</label>
                    <input type="text" name="username" id="modal_username" required>
                </section>
                <section class="form-grupo">
                    <label for="modal_email">E-mail</label>
                    <input type="email" name="email" id="modal_email" required>
                </section>
                <section class="form-grupo">
                    <label for="modal_senha">Nova Senha (deixe em branco para manter)</label>
                    <input type="password" name="senha" id="modal_senha">
                </section>
                <section class="modal-botoes">
                    <button type="button" class="btn-toggle-user" onclick="fecharModalEdicao()">Cancelar</button>
                    <button type="submit" name="alterar_privilegio" class="btn-toggle-admin">Salvar</button>
                </section>
            </form>
        </section>
    </section>

    <script src="components/deslogar.js"></script>
    <script>
        function abrirModalEdicao(id,nome,username,email){
            document.getElementById('modal_id_user').value=id;
            document.getElementById('modal_nome').value=nome;
            document.getElementById('modal_username').value=username;
            document.getElementById('modal_email').value=email;
            document.getElementById('modal_senha').value='';
            document.getElementById('modalEdicaoUsuario').style.display='flex';
        }
        function fecharModalEdicao(){
            document.getElementById('modalEdicaoUsuario').style.display='none';
        }
        window.onclick=function(event){
            var modal=document.getElementById('modalEdicaoUsuario');
            if(event.target===modal){
                modal.style.display='none';
            }
        }
    </script>
</body>
</html>