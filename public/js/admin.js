const API = "/parque_ecologico/api/agendamentos";

async function fazerRequisicao(url, metodo = "GET", body = null) {
    const csrf = localStorage.getItem("csrf_token");

    if (!csrf) {
        alert("Token CSRF não encontrado. Faça login novamente.");
        window.location.href = "/parque_ecologico/login";
        return null;
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
    const data = text ? JSON.parse(text) : {};

    if (!response.ok) {
        throw new Error(data.erro || "Erro na requisição");
    }

    return data;
}

document.addEventListener("DOMContentLoaded", function() {
    verificarLogin();

    document.querySelectorAll('input[name="filtro"]').forEach((filtro) => {
        filtro.addEventListener("change", processarFiltro);
    });

    document.getElementById("btn-atualizar")?.addEventListener("click", recarregarPagina);
    document.getElementById("logout")?.addEventListener("click", logout);
});

async function verificarLogin() {
    const res = await fetch("/parque_ecologico/check-auth", {
        credentials: "include"
    });

    if (!res.ok) {
        window.location.href = "/parque_ecologico/login";
    }
}

async function aprovar(id) {
    if (!confirm(`Deseja aprovar o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/aprovar/${id}`, "PUT");

        if (resposta?.mensagem) {
            atualizarCardStatus(id, "aprovado");
        }
    } catch (erro) {
        alert(erro.message || "Erro ao aprovar agendamento");
    }
}

async function rejeitar(id) {
    if (!confirm(`Deseja rejeitar o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/rejeitar/${id}`, "PUT");

        if (resposta?.mensagem) {
            atualizarCardStatus(id, "rejeitado");
        }
    } catch (erro) {
        alert(erro.message || "Erro ao rejeitar agendamento");
    }
}

async function excluir(id) {
    if (!confirm(`Deseja excluir o agendamento #${id}?`)) return;

    try {
        const resposta = await fazerRequisicao(`${API}/excluir/${id}`, "DELETE");

        if (resposta?.mensagem) {
            removerCard(id);
        }
    } catch (erro) {
        alert(erro.message || "Erro ao excluir agendamento");
    }
}

function atualizarCardStatus(id, novoStatus) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (!card) return;

    card.classList.remove("pendente", "aprovado", "rejeitado");
    card.classList.add(novoStatus);
    card.setAttribute("data-status", novoStatus);

    const badge = card.querySelector(".status-badge");
    if (badge) {
        badge.textContent = capitalizar(novoStatus);
    }

    const acoes = card.querySelector(".card-actions");
    if (!acoes) return;

    const btnAprovar = acoes.querySelector(".btn-success");
    const btnRejeitar = acoes.querySelector(".btn-warning");

    if (novoStatus === "aprovado" && btnAprovar) {
        btnAprovar.remove();
    }

    if (novoStatus === "rejeitado" && btnRejeitar) {
        btnRejeitar.remove();
    }
}

function removerCard(id) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (!card) return;

    card.style.animation = "fadeOut 0.3s ease-out";

    setTimeout(() => {
        card.remove();

        const lista = document.getElementById("lista");
        if (lista && !lista.querySelector(".card")) {
            lista.innerHTML = '<div class="empty-state"><p>Nenhum agendamento encontrado.</p></div>';
        }
    }, 300);
}

function processarFiltro() {
    const filtroAtivo = document.querySelector('input[name="filtro"]:checked').value;

    document.querySelectorAll(".admin-card").forEach((card) => {
        const status = card.getAttribute("data-status");
        card.style.display = filtroAtivo === "todos" || status === filtroAtivo ? "" : "none";
    });
}

function recarregarPagina() {
    window.location.reload();
}

async function logout() {
    await fetch("/parque_ecologico/logout", {
        method: "POST",
        credentials: "include"
    });

    localStorage.removeItem("csrf_token");
    window.location.href = "/parque_ecologico/login";
}

function capitalizar(texto) {
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}
