<?php
    session_start();
    $usuario_logado = isset($_SESSION['usuario']);
    $is_admin = false;
if($usuario_logado){
     require_once 'paginas/conexao.php'; 
        $id_user = intval($_SESSION['id_user']);
        $sql_checa_admin = "SELECT hierarquia, privilegio FROM usuario WHERE id_user = $id_user";
        $busca_admin = mysqli_query($conexao, $sql_checa_admin);
        
        if($busca_admin && $dados_usuario = mysqli_fetch_assoc($busca_admin)) {
            $_SESSION['privilegio']= $dados_usuario['privilegio'];
            $_SESSION['hierarquia']= intval($dados_usuario['hierarquia']);
        }else{
            $_SESSION['privilegio']= 'user';
            $_SESSION['hierarquia']= 1;
        }
        $usuario_completo = $_SESSION['usuario'];
        $usuario = explode(' ', $usuario_completo);
    $is_admin = ($_SESSION['privilegio']==='admin'||$_SESSION['hierarquia']>=2);
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Yulong Passwords</title>
        <link rel="stylesheet" href="css/geral.css">
        <link rel="stylesheet" href="css/logout-modal.css">
        <style>
            a{
                cursor: pointer;
            }
            main{
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 2rem;
                max-width: 1200px;
                margin: 0 auto;
                width: 100%;
            }
            .hero{
                text-align: center;
                padding: 5rem 1rem 3rem 1rem;
                max-width: 800px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .hero p{
                font-size: clamp(1rem, 3vw, 1.25rem);
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
                margin-top: 1.5rem;
            }
            .hero-botoes{
                display: flex;
                gap: 1.2rem;
                margin-top: 2.5rem;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
            }
            .botao-principal, .botao-secundario, .botao-admin{
                padding: 0.8rem 2rem;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            .botao-principal{
                background-color: #137751;
                color: white;
                box-shadow: 0 4px 12px rgba(19, 119, 81, 0.3);
            }
            .botao-principal:hover{
                background-color: #0c5338;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(10, 102, 79, 0.4);
            }
            .botao-secundario{
                background-color: rgba(255, 255, 255, 0.06);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            .botao-secundario:hover{
                background-color: rgba(255, 255, 255, 0.12);
                transform: translateY(-2px);
            }
            .botao-admin {
                background: linear-gradient(135deg, #8a182d 0%, #530c17 100%);
                color: white;
                border: 1px solid rgba(255, 99, 132, 0.2);
                box-shadow: 0 4px 15px rgba(119, 19, 38, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
                position: relative;
                overflow: hidden;
            }
            .botao-admin::before {
                content: '';
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
                transform: translateX(-100%);
                transition: transform 0.6s ease;
            }
            .botao-admin:hover {
                background: linear-gradient(135deg, #a61c36 0%, #6b101f 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(166, 28, 54, 0.6);
                border-color: rgba(255, 99, 132, 0.4);
            }
            .botao-admin:hover::before {
                transform: translateX(100%);
            }
            .recursos{
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                width: 100%;
                margin-top: 5rem;
            }
            .card-recurso{
                background: rgba(4, 59, 36, 0.3);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                padding: 2.5rem 2rem;
                border-radius: 4px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
                transition: transform 0.3s ease;
            }
            .card-recurso:hover{
                transform: translateY(-5px);
            }
            .card-recurso h3{
                font-size: 1.35rem;
                font-weight: 700;
                margin-bottom: 1rem;
                color: #ffffff;
            }
            .card-recurso p{
                font-size: 0.95rem;
                color: rgba(255, 255, 255, 0.7);
                line-height: 1.6;
            }
            .secao-texto-adicional {
                margin-top: 5rem;
                padding-bottom: 4rem;
                max-width: 800px;
                text-align: center;
            }
            .secao-texto-adicional h2 {
                font-size: 1.8rem;
                color: #ffffff;
                margin-bottom: 1.5rem;
            }
            .secao-texto-adicional p {
                font-size: 1rem;
                color: rgba(255, 255, 255, 0.75);
                line-height: 1.8;
                margin-bottom: 1.2rem;
            }
        </style>
    </head>
    <body>
        <nav>
            <img src="./imgs/logos/yulong_logo_simplified.png" alt="Yulong Passwords">
            <ol>
                <li><a href="index.php">Home</a></li>
                <li><a href="paginas/senhas.php">Senhas</a></li>
                <?php if ($usuario_logado):?>
                    <li id="nav-deslogar"><a href="#">Deslogar</a></li>
                <?php else:?>
                    <li><a href="paginas/login.php">Login</a></li>
                <?php endif;?>  
                <li><a href="paginas/perfil.php">Perfil</a></li>
            </ol>
        </nav>
        <main>
            <section class="hero">
                <h1 class="texto-grande texto-sombra">Yulong Passwords</h1>
                <?php if($usuario_logado):?>
                    <h2 class="texto-medio texto-sombra" style="margin-top: 1rem;">Bem-vindo, <?php echo htmlspecialchars($usuario[0]); ?>!</h2>
                    <p>Você está pronto para gerenciar e guardar suas credenciais digitais de forma segura!</p>
                    <section class="hero-botoes">
                        <a href="paginas/senhas.php" class="botao-principal">Acessar gerenciador</a>
                        
                        <?php if($is_admin): ?>
                            <a href="paginas/admin.php" class="botao-admin">Painel Admin</a>
                        <?php endif; ?>
                        <a onclick="alert('Em manutenção :)');" class="botao-secundario">Como funciona</a>
                    </section>
                <?php else:?>
                    <p>
                        Guardar senha no bloco de notas é pedir pra dar errado. 
                        Aqui, suas credenciais ficam trancadas com criptografia real e só você tem a chave.
                    </p>
                    <section class="hero-botoes">
                        <a href="paginas/login.php" class="botao-principal">Login</a>
                        <a onclick="alert('Em manutenção :)');" class="botao-secundario">Como funciona</a>
                    </section>
                <?php endif;?>
            </section>
            <section id="recursos" class="recursos">
                <section class="card-recurso">
                    <h3>Criptografia de verdade</h3>
                    <p>Seus dados são cifrados no seu próprio dispositivo antes de qualquer coisa. Nem a gente consegue abrir.</p>
                </section>
                <section class="card-recurso">
                    <h3>Sem enrolação</h3>
                    <p>Interface limpa, busca rápida e sem coisas difíceis pra cadastrar a primeira senha.</p>
                </section>
                <section class="card-recurso">
                    <h3>O Significado de Yulong</h3>
                    <p>Inspirado no lendário Dragão de Jade (玉龙), o Yulong simboliza a proteção inabalável, sabedoria e a força necessária para blindar suas senhas contra qualquer ameaça digital.</p>
                </section>
            </section>
            <section class="secao-texto-adicional">
                <h2>Conheça a história por trás do nome</h2>
                <p>Diz a antiga lenda do povo <strong>Naxi</strong> que os guerreiros gêmeos <strong>Yulong</strong> (<strong>Dragão de Jade</strong>) e <strong>Haba</strong> viviam às margens do <strong>Rio Jinsha</strong> (trecho superior do <strong>Yangtzé</strong>), na região que hoje é a província de <strong>Yunnan</strong>, sudoeste da <strong>China</strong>. Um dia, um <strong>demônio</strong> terrível dominou o rio, impedindo que o povo pescasse e vivesse em paz.</p>
                <p>Para defender seu povo, os irmãos lutaram bravamente. <strong>Haba</strong> foi decapitado pelo demônio, e <strong>Yulong</strong>, consumido pela dor, combateu por <strong>dois dias e três noites</strong> até que quebrou suas <strong>13 espadas</strong> e expulsou a criatura para sempre.</p>
                <p>Para garantir que o mal nunca mais voltasse, <strong>Yulong</strong> permaneceu de guarda, segurando suas armas dia e noite, até que ambos se transformaram nas montanhas que conhecemos hoje: a imponente <strong>Montanha Nevada do Dragão de Jade</strong> (<strong>玉龙雪山</strong>), cujos <strong>13 picos brancos</strong> representam as espadas fincadas contra qualquer perigo, e a <strong>Montanha Nevada Haba</strong> (<strong>哈巴雪山</strong>), "sem cabeça", do outro lado do <strong>Desfiladeiro do Salto do Tigre</strong> (<strong>虎跳峡</strong>).</p>  
                <p>O suor da batalha de <strong>Yulong</strong>, dizem, transformou-se nos rios <strong>Heishui</strong> (<strong>Água Negra</strong>) e <strong>Baishui</strong> (<strong>Água Branca</strong>) que correm pelos vales. A montanha é sagrada para o povo <strong>Naxi</strong>, e seu deus protetor, <strong>Sanduo</strong>, é considerado a encarnação do próprio <strong>Dragão de Jade</strong>.</p>
            </section>
        </main>
        <script>
            document.addEventListener("DOMContentLoaded",()=>{
                const botaoSair = document.getElementById("nav-deslogar");
                if (botaoSair){
                    botaoSair.addEventListener("click",(evento)=>{
                        evento.preventDefault();
                        const fundo = document.createElement("section");
                        fundo.className = "fundo-modal"; 
                        fundo.innerHTML = `
                            <section class="caixa-logout">
                                <h2>Deseja realmente sair?</h2>
                                <p>Você precisará fazer login novamente para acessar sua conta.</p> 
                                <section class="container-botoes">
                                    <a href="paginas/components/deslogar.php" class="botao-logout botao-confirmar">Sim, deslogar</a>
                                    <button type="button" class="botao-logout botao-cancelar" id="btn-cancelar">Cancelar</button>
                                </section>
                            </section>`;
                        document.body.appendChild(fundo);
                        fundo.querySelector("#btn-cancelar").addEventListener("click", () => fundo.remove());
                        fundo.addEventListener("click", (e) =>{if (e.target === fundo) fundo.remove();});
                    });
                }
            });   
        </script>
    </body>
</html>