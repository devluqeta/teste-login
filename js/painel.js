/* ========== RELÓGIO ========== */
const clockEl = document.getElementById('clock');

function tick() {
    clockEl.textContent = new Date().toLocaleTimeString('pt-BR');
}
setInterval(tick, 1000);
tick();

/* ========== GRÁFICO (Chart.js) ========== */
const ctx = document.getElementById('chartVendas').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Vendas',
            data: [12, 19, 7, 9, 15, 22, 18, 25, 20, 30, 28, 35],
            backgroundColor: '#4569e9'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2, // evita esticar para baixo
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#fff'
                }
            },
            y: {
                ticks: {
                    color: '#fff'
                }
            }
        }
    }
});

/* ========== LISTA DE TAREFAS (TODO) ========== */
const todoInput = document.getElementById('todoInput');
const todoAdd = document.getElementById('todoAdd');
const todoList = document.getElementById('todoList');

function renderTodos() {
    const todos = JSON.parse(localStorage.getItem('todos') || '[]');
    todoList.innerHTML = '';
    todos.forEach((t, i) => {
        const li = document.createElement('li');
        li.innerHTML = `<span>${t}</span>
            <button data-i="${i}" class="btn small danger">Excluir</button>`;
        todoList.appendChild(li);
    });
}

todoAdd.addEventListener('click', () => {
    const v = todoInput.value.trim();
    if (!v) return;
    const todos = JSON.parse(localStorage.getItem('todos') || '[]');
    todos.push(v);
    localStorage.setItem('todos', JSON.stringify(todos));
    todoInput.value = '';
    renderTodos();
});

todoList.addEventListener('click', (e) => {
    if (e.target.matches('button[data-i]')) {
        const i = +e.target.getAttribute('data-i');
        const todos = JSON.parse(localStorage.getItem('todos') || '[]');
        todos.splice(i, 1);
        localStorage.setItem('todos', JSON.stringify(todos));
        renderTodos();
    }
});

renderTodos();

/* ========== CRUD LOCAL (NAMESPACE) ========== */
const STORAGE_KEY = 'painelCrudData';
const form = document.getElementById('crudForm');
const tbody = document.querySelector('#crudTable tbody');

function loadCrudData() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
}

function saveCrudData(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

function renderTable() {
    tbody.innerHTML = '';
    const data = loadCrudData();
    const keys = Object.keys(data).sort();

    if (keys.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan="3" style="text-align:center;color:#ccc;">Nenhum item cadastrado</td>`;
        tbody.appendChild(tr);
        return;
    }

    keys.forEach(k => {
        const v = data[k];
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><code>${k}</code></td>
            <td>${v}</td>
            <td>
                <button class="btn small" data-edit="${k}">Editar</button>
                <button class="btn small danger" data-del="${k}">Excluir</button>
            </td>`;
        tbody.appendChild(tr);
    });
}

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const k = (document.getElementById('itemKey').value || '').trim();
    const v = (document.getElementById('itemValue').value || '').trim();
    if (!k) return;

    const data = loadCrudData();
    data[k] = v;
    saveCrudData(data);

    form.reset();
    renderTable();
});

tbody.addEventListener('click', (e) => {
    if (e.target.matches('button[data-del]')) {
        const k = e.target.getAttribute('data-del');
        const data = loadCrudData();
        delete data[k];
        saveCrudData(data);
        renderTable();
    }
    if (e.target.matches('button[data-edit]')) {
        const k = e.target.getAttribute('data-edit');
        const data = loadCrudData();
        const v = prompt('Novo valor para "' + k + '":', data[k] || '');
        if (v !== null) {
            data[k] = v;
            saveCrudData(data);
            renderTable();
        }
    }
});

renderTable();