document.addEventListener("DOMContentLoaded", () => {
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
                <a href="components/deslogar.php" name="confirm_logout" class="botao-logout botao-confirmar">Sim, deslogar</a>
                <button type="button" class="botao-logout botao-cancelar" id="btn-cancelar">Cancelar</button>
            </section>
        </section>`;
      document.body.appendChild(fundo);
      fundo.querySelector("#btn-cancelar").addEventListener("click", () => fundo.remove());
      fundo.addEventListener("click", (e) =>{if (e.target === fundo) fundo.remove();});
    });
  }
});