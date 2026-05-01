<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel Admin</title>
<script>
async function verificarLogin() {

    const res = await fetch("http://localhost/parque_ecologico/public/check-auth", {
        credentials: "include"
    });

    if (!res.ok) {
        window.location.href = "./public/login.html";
    }
}

verificarLogin();
</script>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.card {
    border: 1px solid #ccc;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
}

.aprovado { background-color: #e6ffe6; }
.pendente { background-color: #fffbe6; }
.rejeitado { background-color: #ffe6e6; }

button {
    margin-right: 5px;
    cursor: pointer;
}
</style>

</head>
<body>

<h2>Painel de Agendamentos</h2>
<p>teste autenticaçao</p>
<button onclick="logout()">Logout</button>
<hr>

<div id="lista"></div>

<script>
const API = "http://localhost/parque_ecologico/public/agendamentos";


async function carregar() {
    const res = await fetch(API);
    const dados = await res.json();

    const lista = document.getElementById("lista");
    lista.innerHTML = "";

    dados.forEach(a => {
        const div = document.createElement("div");

        div.className = "card " + a.status;

        div.innerHTML = `
            <strong>${a.nome_responsavel}</strong><br>
            Email: ${a.email}<br>
            Data: ${a.data_reserva}<br>
            Horário Entrada: ${a.horario_entrada}<br>
            Horário Saída: ${a.horario_saida}<br>
            Visitantes: ${a.qtd_visitantes}<br>
            Status: ${a.status}<br><br>

            <button onclick="aprovar(${a.id})">Aprovar</button>
            <button onclick="rejeitar(${a.id})">Rejeitar</button>
            <button onclick="excluir(${a.id})">Excluir</button>
        `;

        lista.appendChild(div);
    });
}

async function aprovar(id) {
    await fetch(`${API}/aprovar/${id}`, {
    method: "PUT",
    credentials:"include"
    });
    carregar();
}


async function rejeitar(id) {
    await fetch(`${API}/rejeitar/${id}`, {
    method: "PUT",
    credentials:"include"
    });
    carregar();
}


async function excluir(id) {
    if (!confirm("Deseja excluir?")) return;

    await fetch(`${API}/excluir/${id}`, { method: "DELETE", credentials:"include" });
    carregar();
}

async function login() {
    const usuario = document.getElementById("user").value;
    const senha = document.getElementById("pass").value;

    const res = await fetch("http://localhost/parque_ecologico/public/login", {
        method: "POST",
        credentials: "include", // 
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ usuario, senha })
    });

    const data = await res.json();

    document.getElementById("status").innerText = data.mensagem || data.erro;
    carregar();
}

async function logout() {
    await fetch("http://localhost/parque_ecologico/public/logout", {
        method: "POST",
        credentials: "include"
    });

    window.location.href = "./public/login.html";
}


carregar();
</script>

</body>
</html>