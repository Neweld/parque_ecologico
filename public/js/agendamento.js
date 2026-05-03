document.getElementById("agendamento-form").addEventListener("submit", async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const data = {
        nome_instituicao: formData.get("nome_instituicao"),
        nome_diretor: formData.get("nome_diretor"),
        nome_responsavel: formData.get("nome_responsavel"),
        email: formData.get("email"),
        data_reserva: formData.get("data_reserva"),
        
        faixa_etaria: formData.get("faixa_etaria"),
        qtd_visitantes: parseInt(formData.get("qtd_visitantes")),
        proposito_visita: formData.get("proposito_visita"),
        usa_grama: formData.get("usa_grama") ? 1 : 0,
        usa_quiosque: formData.get("usa_quiosque") ? 1 : 0,
        horario_entrada: formData.get("horario_entrada"),
        horario_saida: formData.get("horario_saida")
    };

    try {
        const response = await fetch("http://localhost/parque_ecologico/api/agendamentos/enviar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        document.getElementById("resultado").innerText = result.erro || result.mensagem;

    } catch (error) {
        document.getElementById("resultado").innerText = `Erro ao enviar ${error}`;
        
    }
});