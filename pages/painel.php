<?php

declare(strict_types=1);
require __DIR__ . '/../php/auth.php';
$username = $_SESSION['username'] ?? 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Painel de Informações</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/painel.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include '../includes/favicon.php'; ?>
</head>

<body class="dash-bg">
    <header class="topbar">
        <div class="brand">
            <i class='bx bxs-rocket logo'></i>
            <strong>Painel de Informações</strong>
        </div>
        <nav class="actions">
            <span class="user"><strong>Olá, <?php echo htmlspecialchars($username); ?></strong></span>
            <a class="btn ghost" href="../php/logout.php"><i class='bx bx-log-out'></i> Sair</a>
        </nav>
    </header>

    <main class="grid">
        <section class="card">
            <h2><i class='bx bxs-bar-chart-alt-2'></i> Vendas Mensais</h2>
            <canvas id="chartVendas"></canvas>
        </section>

        <section class="card">
            <h2><i class='bx bxs-task'></i> Tarefas</h2>
            <div class="todo">
                <input id="todoInput" type="text" placeholder="Nova tarefa...">
                <button id="todoAdd" class="btn"><i class='bx bx-plus'></i> Adicionar</button>
            </div>
            <ul id="todoList" class="todo-list"></ul>
        </section>

        <section class="card center">
            <h2><i class='bx bxs-time'></i> Relógio</h2>
            <div id="clock" class="clock"></div>
        </section>

        <section class="card">
            <h2><i class='bx bxs-folder-open'></i> Cadastro de Chaves</h2>
            <p>(Ex: Chave: Nome Valor: Lucas)</p>
            <form id="crudForm" class="crud">
                <input type="text" id="itemKey" placeholder="Chave" required>
                <input type="text" id="itemValue" placeholder="Valor" required>
                <button type="submit" class="btn"><i class='bx bxs-save'></i> Salvar</button>
            </form>
            <div id="crudTableWrap" class="table-wrap">
                <table class="table" id="crudTable">
                    <thead>
                        <tr>
                            <th>Chave</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footer">
        <small>Painel para testes de Login • Sessão protegida por PHP</small>
    </footer>

    <script src="../js/painel.js"></script>
</body>

</html>