<?php
    session_start();
    $usuario_logado = isset($_SESSION['usuario']);
    if($usuario_logado){
        $usuario = $_SESSION['usuario'];
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Yulong Passwords</title>
        <link rel="stylesheet" href="css/geral.css">
        <style>
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
                gap: 1rem;
                margin-top: 2.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            .botao-principal, .botao-secundario{
                padding: 0.8rem 2rem;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
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
            .recursos{
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                width: 100%;
                margin-top: 5rem;
                padding-bottom: 4rem;
            }
            .card-recurso{
                background: rgba(4, 59, 36, 0.3);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                padding: 2.5rem 2rem;
                border-radius: 20px;
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
        </style>
    </head>
    <body>
        <nav>
            <img src="./imgs/logos/yulong_logo_simplified.png" alt="Yulong Passwords">
            <ol>
                <li><a href="index.php">Home</a></li>
                <li><a href="paginas/senhas.php">Senhas</a></li>

                <?php if ($usuario_logado):?>
                <li><a href="paginas/components/deslogar.php">Deslogar</a></li>
                <?php else:?>
                <li><a href="paginas/login.php">Login</a></li>
                <?php endif;?>   
                <li><a href="paginas/perfil.php">Perfil</a></li>
            </ol>
        </nav>

        <main>
            <section class="hero">
                <h1 class="texto-grande texto-sombra">Yulong Passwords</h1>
                <?php if ($usuario_logado):?>
                    <h2 class="texto-medio texto-sombra" style="margin-top: 1rem;">Bem-vindo, <?php echo $usuario ?>!</h2>
                    <p>
                        Você está logado e pronto para gerenciar e guardar suas credenciais digitais de forma segura!
                    </p>
                    <section class="hero-botoes">
                        <a href="paginas/senhas.php" class="botao-principal">Acessar gerenciador</a>
                        <a href="#recursos" class="botao-secundario">Como funciona</a>
                    </section>
                <?php else:?>
                    <p>
                        Guardar senha no bloco de notas é pedir pra dar errado. 
                        Aqui, suas credenciais ficam trancadas com criptografia real e só você tem a chave.
                    </p>
                    <section class="hero-botoes">
                        <a href="paginas/cadastro.php" class="botao-principal">Criar conta</a>
                        <a href="#recursos" class="botao-secundario">Como funciona</a>
                    </section>
                <?php endif;?>
            </section>
        
            <section id="recursos" class="recursos">
                <section class="card-recurso">
                    <h3>Criptografia de verdade</h3>
                    <p>Seus dados são cifrados no seu próprio dispositivo antes de qualquer coisa. Nem a gente consegue abrir.</p>
                </section>
                <section class="card-recurso">
                    <h3>Gerador que funciona</h3>
                    <p>Sem aqueles caracteres impossíveis de digitar. Gere senhas fortes, mas que ainda dá pra usar quando precisar.</p>
                </section>
                <section class="card-recurso">
                    <h3>Sem enrolação</h3>
                    <p>Interface limpa, busca rápida e sem coisas difíceis pra cadastrar a primeira senha.</p>
                </section>
            </section>
        </main>
    </body>
</html>