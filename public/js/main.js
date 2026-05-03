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
