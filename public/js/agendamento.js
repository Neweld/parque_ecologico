// Script específico da página de agendamento
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('agendamento-form');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Coletar dados do formulário
            const formData = new FormData(form);
            const dados = Object.fromEntries(formData);
            
            // Converter checkbox para booleano
            dados.usa_grama = dados.usa_grama === 'sim';
            dados.usa_quiosque = dados.usa_quiosque === 'sim';

            try {
                // Enviar para API
                const resposta = await fazerRequisicao(
                    API_AGENDAMENTOS,
                    'POST',
                    dados
                );

                mostrarMensagem('form-message', resposta.mensagem, 'sucesso');
                form.reset();
            } catch (erro) {
                mostrarMensagem('form-message', 'Erro ao enviar agendamento', 'erro');
            }
        });
    }
});
