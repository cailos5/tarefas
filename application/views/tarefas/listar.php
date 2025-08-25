<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #e51274;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #3ab7d8;
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
        }

        .main-content {
            padding: 2rem 0;
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-select {
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid var(--light-gray);
            background-color: white;
        }

        .tarefas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .tarefa-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 1.2rem;
            transition: transform 0.3s ease;
            border-left: 4px solid var(--primary);
            position: relative;
        }

        .tarefa-card:hover {
            transform: translateY(-5px);
        }

        .tarefa-card.alta {
            border-left-color: var(--danger);
        }

        .tarefa-card.media {
            border-left-color: var(--warning);
        }

        .tarefa-card.baixa {
            border-left-color: var(--success);
        }

        .tarefa-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .tarefa-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .tarefa-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pendente {
            background-color: #fff0f3;
            color: var(--danger);
        }

        .status-andamento {
            background-color: #e6f2ff;
            color: var(--info);
        }

        .status-concluido {
            background-color: #e8f8f5;
            color: #16a085;
        }

        .tarefa-details {
            margin-bottom: 1rem;
        }

        .tarefa-detail {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .tarefa-detail i {
            margin-right: 8px;
            width: 16px;
        }

        .tarefa-criacao {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tarefa-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 1rem;
        }

        .tarefa-action {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--light-gray);
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .modal-message {
            margin-bottom: 1.5rem;
            color: var(--gray);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .tarefas-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .tarefa-imagem {
                max-width: 100%;
                border-radius: 5px;
                margin-top: 10px;
                border: 1px solid var(--light-gray);
            }

            .imagem-preview {
                max-width: 200px;
                border-radius: 5px;
                margin-top: 10px;
                border: 1px solid var(--light-gray);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-tasks"></i>
                    Gerenciador de Tarefas
                </div>
                <div class="user-info">
                    <span>Olá, <?php echo htmlspecialchars($this->session->userdata('usuario_nome') ?: 'Usuário'); ?></span>
                    <a href="<?php echo site_url('usuarios/logout'); ?>" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-list-check"></i> Minhas Tarefas
            </h1>

            <!-- Alertas -->
            <?php if ($this->session->flashdata('mensagem')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('mensagem'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('erro')): ?>
                <div class="alert alert-error">
                    <?php echo $this->session->flashdata('erro'); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="filters">
                    <div class="filter-item">
                        <label for="status-filter">Filtrar por status:</label>
                        <select id="status-filter" class="filter-select">
                            <option value="all">Todas</option>
                            <option value="pendente">Pendentes</option>
                            <option value="andamento">Em andamento</option>
                            <option value="concluido">Concluídas</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="priority-filter">Filtrar por prioridade:</label>
                        <select id="priority-filter" class="filter-select">
                            <option value="all">Todas</option>
                            <option value="alta">Alta</option>
                            <option value="media">Média</option>
                            <option value="baixa">Baixa</option>
                        </select>
                    </div>
                    <a href="<?php echo site_url('tarefas/add'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nova Tarefa
                    </a>
                </div>

                <div class="tarefas-grid">
                    <?php if (!empty($tarefas)): ?>
                        <?php foreach ($tarefas as $tarefa): ?>
                            <?php
                            // Determina a classe de prioridade
                            $prioridade_class = '';
                            $prioridade_label = '';
                            if ($tarefa->prioridade == 2) {
                                $prioridade_class = 'alta';
                                $prioridade_label = 'Alta Prioridade';
                            } elseif ($tarefa->prioridade == 1) {
                                $prioridade_class = 'media';
                                $prioridade_label = 'Média Prioridade';
                            } else {
                                $prioridade_class = 'baixa';
                                $prioridade_label = 'Baixa Prioridade';
                            }
                            
                            // Formata as datas
                            $data_vencimento = date('d/m/Y H:i', strtotime($tarefa->data_vencimento));
                            $data_criacao = date('d/m/Y H:i', strtotime($tarefa->data_criacao));
                            ?>
                            
                            <div class="tarefa-card <?php echo $prioridade_class; ?>">
                                <div class="tarefa-header">
                                    <h3 class="tarefa-title"><?php echo htmlspecialchars($tarefa->descricao); ?></h3>
                                    <span class="tarefa-status status-<?php echo $tarefa->status; ?>">
                                        <?php 
                                        echo $tarefa->status == 'pendente' ? 'Pendente' :
                                             $tarefa->status == 'andamento' ? 'Em andamento' : 'Concluído';
                                        ?>
                                    </span>
                                </div>
                                <div class="tarefa-details">
                                    <div class="tarefa-detail">
                                        <i class="fas fa-flag"></i>
                                        <span class="priority-badge priority-<?php echo $prioridade_class; ?>">
                                            <?php echo $prioridade_label; ?>
                                        </span>
                                    </div>
                                    <div class="tarefa-detail">
                                        <i class="fas fa-calendar-day"></i>
                                        Vencimento: <?php echo $data_vencimento; ?>
                                    </div>
                                    <div class="tarefa-criacao">
                                        <i class="fas fa-clock"></i>
                                        Criada em: <?php echo $data_criacao; ?>
                                    </div>
                                    <?php if ($tarefa->imagem): ?>
                                        <div class="tarefa-detail">
                                            <i class="fas fa-image"></i>
                                            <img src="<?php echo base_url('Uploads/' . $tarefa->imagem); ?>" alt="Imagem da tarefa" class="tarefa-imagem">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="tarefa-actions">
                                    <a href="<?php echo site_url('tarefas/edit/' . $tarefa->id); ?>" class="btn btn-success tarefa-action">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button class="btn btn-danger tarefa-action delete-btn" data-id="<?php echo $tarefa->id; ?>" data-title="<?php echo htmlspecialchars($tarefa->descricao); ?>">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Nenhuma tarefa encontrada.</p>
                            <a href="<?php echo site_url('tarefas/add'); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Primeira Tarefa
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de Confirmação -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title">Confirmar Exclusão</h3>
            <p class="modal-message" id="modalMessage">Tem certeza que deseja excluir a tarefa "<span id="taskTitle"></span>"? Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button id="cancelDelete" class="btn btn-outline">Cancelar</button>
                <button id="confirmDelete" class="btn btn-danger">Excluir</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Variáveis globais
        let currentTaskId = null;

        // Elementos do DOM
        const deleteModal = document.getElementById('deleteModal');
        const modalMessage = document.getElementById('modalMessage');
        const taskTitleSpan = document.getElementById('taskTitle');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const confirmDeleteBtn = document.getElementById('confirmDelete');

        // Filtros interativos
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('status-filter');
            const priorityFilter = document.getElementById('priority-filter');
            const tarefas = document.querySelectorAll('.tarefa-card');
            
            // Definir valores dos filtros baseados na URL
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            const priorityParam = urlParams.get('priority');
            
            if (statusParam) statusFilter.value = statusParam;
            if (priorityParam) priorityFilter.value = priorityParam;
            
            function filterTarefas() {
                const statusValue = statusFilter.value;
                const priorityValue = priorityFilter.value;
                
                tarefas.forEach(tarefa => {
                    const statusText = tarefa.querySelector('.tarefa-status').textContent.toLowerCase();
                    let statusMatch = statusValue === 'all' || 
                                    (statusValue === 'pendente' && statusText.includes('pendente')) ||
                                    (statusValue === 'andamento' && statusText.includes('andamento')) ||
                                    (statusValue === 'concluido' && statusText.includes('concluído'));
                    let priorityMatch = priorityValue === 'all' || tarefa.classList.contains(priorityValue);
                    
                    tarefa.style.display = statusMatch && priorityMatch ? 'block' : 'none';
                });
            }
            
            statusFilter.addEventListener('change', aplicarFiltros);
            priorityFilter.addEventListener('change', aplicarFiltros);
            
            // Redirecionar quando os filtros mudarem
            function aplicarFiltros() {
                const statusValue = statusFilter.value;
                const priorityValue = priorityFilter.value;
                
                let url = '<?php echo site_url("tarefas"); ?>?';
                if (statusValue !== 'all') url += 'status=' + statusValue + '&';
                if (priorityValue !== 'all') url += 'priority=' + priorityValue;
                
                if (url.endsWith('&')) url = url.slice(0, -1);
                
                window.location.href = url;
            }
            
            // Configurar eventos de exclusão
            setupDeleteButtons();
            
            // Aplicar filtros iniciais
            filterTarefas();
        });

        // Configurar botões de exclusão
        function setupDeleteButtons() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-id');
                    const taskTitle = this.getAttribute('data-title');
                    
                    showDeleteModal(taskId, taskTitle);
                });
            });
        }

        // Mostrar modal de confirmação
        function showDeleteModal(taskId, taskTitle) {
            currentTaskId = taskId;
            taskTitleSpan.textContent = taskTitle;
            deleteModal.style.display = 'flex';
        }

        // Esconder modal
        function hideDeleteModal() {
            deleteModal.style.display = 'none';
            currentTaskId = null;
        }

        // Event Listeners para o modal
        cancelDeleteBtn.addEventListener('click', hideDeleteModal);
        
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentTaskId) {
                $.ajax({
                    url: '<?php echo site_url("tarefas/delete/"); ?>' + currentTaskId,
                    type: 'POST',
                    data: { '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Erro ao excluir a tarefa.');
                    }
                });
                hideDeleteModal();
            }
        });

        // Fechar modal clicando fora dele
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>