const API = "/parque_ecologico/api/agendamentos";

/**
 * Função padrão para requisições autenticadas
 */
async function fazerRequisicao(url, metodo = 'GET', body = null) {

    const csrf = localStorage.getItem("csrf_token");

    if (!csrf) {
        alert("Token CSRF não encontrado. Faça login novamente.");
        window.location.href = "/parque_ecologico/login.html";
        return;
    }

    const response = await fetch(url, {
        method: metodo,
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": csrf 
        },
        credentials: "include",
        body: body ? JSON.stringify(body) : null
    });

    const text = await response.text();

    

    return JSON.parse(text);
}

document.addEventListener('DOMContentLoaded', function() {
    async function verificarLogin() {
    const res = await fetch("/parque_ecologico/check-auth", {
        credentials: "include"
    });

    if (!res.ok) {
        window.location.href = "/parque_ecologico/login";
    }
}
    verificarLogin();
    // Evento para filtro de status
    const filtros = document.querySelectorAll('input[name="filtro"]');
    filtros.forEach(filtro => {
        filtro.addEventListener('change', procesarFiltro);
    });

    // Evento para atualizar lista
    const btnAtualizar = document.getElementById('btn-atualizar');
    if (btnAtualizar) {
        btnAtualizar.addEventListener('click', recarregarPagina);
    }
});

/**
 * Aprova um agendamento
 */
async function aprovar(id) {
    if (!confirm(`Deseja aprovar o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/aprovar/${id}`, 'PUT');

        if (resposta?.mensagem) {
            atualizarCardStatus(id, 'aprovado');
        }

    } catch (erro) {
        alert('Erro ao aprovar agendamento');
        console.error(erro);
    }
}

/**
 * Rejeita um agendamento
 */
async function rejeitar(id) {
    if (!confirm(`Deseja aprovar o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/rejeitar/${id}`, 'PUT');

        if (resposta?.mensagem) {
            atualizarCardStatus(id, 'rejeitado');
        }

    } catch (erro) {
        alert('Erro ao rejeitar agendamento');
        console.error(erro);
    }
}

/**
 * Exclui um agendamento
 */
async function excluir(id) {
    if (!confirm(`Deseja aprovar o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/excluir/${id}`, 'DELETE');

        if (resposta?.mensagem) {
            atualizarCardStatus(id, 'Excluido');
        }

    } catch (erro) {
        alert('Erro ao excluir agendamento');
        console.error(erro);
    }
}

/**
 * Atualiza o status visual e estrutura do card
 */
function atualizarCardStatus(id, novoStatus) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (!card) return;

    // Atualizar classe CSS
    card.classList.remove('pendente', 'aprovado', 'rejeitado');
    card.classList.add(novoStatus);

    // Atualizar data attribute
    card.setAttribute('data-status', novoStatus);

    // Atualizar badge
    const badge = card.querySelector('.status-badge');
    if (badge) {
        badge.textContent = capitalizar(novoStatus);
    }

    // Atualizar botões
    const acoes = card.querySelector('.card-actions');
    if (acoes) {
        const btnAprovar = acoes.querySelector('.btn-success');
        const btnRejeitar = acoes.querySelector('.btn-warning');

        if (novoStatus === 'aprovado' && btnAprovar) {
            btnAprovar.remove();
        }

        if (novoStatus === 'rejeitado' && btnRejeitar) {
            btnRejeitar.remove();
        }
    }
}

/**
 * Remove o card do DOM
 */
function removerCard(id) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (card) {
        card.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => card.remove(), 300);

        // Verificar se lista ficou vazia
        const lista = document.getElementById('lista');
        if (!lista.querySelector('.card')) {
            lista.innerHTML = '<div class="empty-state"><p>Nenhum agendamento encontrado</p></div>';
        }
    }
}

/**
 * Filtra cards por status
 */
function procesarFiltro() {
    const filtroAtivo = document.querySelector('input[name="filtro"]:checked').value;
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        const status = card.getAttribute('data-status');

        if (filtroAtivo === 'todos' || status === filtroAtivo) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

/**
 * Recarrega a página
 */
function recarregarPagina() {
    window.location.reload();
}

/**
 * Capitaliza primeira letra
 */
function capitalizar(texto) {
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}


