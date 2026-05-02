// Script global da aplicação
console.log('Parque Ecológico - Sistema Carregado');

// Utility: mostrar mensagem
function mostrarMensagem(elemento, mensagem, tipo = 'sucesso') {
    const msgElement = document.getElementById(elemento);
    if (msgElement) {
        msgElement.textContent = mensagem;
        msgElement.className = `form-message ${tipo}`;
        msgElement.style.display = 'block';
        
        // Auto-hide em 5 segundos
        setTimeout(() => {
            msgElement.style.display = 'none';
        }, 5000);
    }
}

// Utility: fazer requisições AJAX
async function fazerRequisicao(url, metodo = 'GET', dados = null) {
    try {
        const opcoes = {
            method: metodo,
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (dados && metodo !== 'GET') {
            opcoes.body = JSON.stringify(dados);
        }

        const resposta = await fetch(url, opcoes);
        return await resposta.json();
    } catch (erro) {
        console.error('Erro na requisição:', erro);
        throw erro;
    }
}
