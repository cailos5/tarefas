<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tarefa) ? 'Editar': 'Adicionar'?> Tarefa</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .page-title {
            font-size: 2rem;
            margin: 2rem 0;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            border: 1px solid var(--light-gray);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--gray);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .priority-indicator {
            display: flex;
            gap: 10px;
            margin-top: 0.5rem;
        }

        .indicator {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator-baixa {
            background-color: #e8f8f5;
            color: #16a085;
        }

        .indicator-media {
            background-color: #fff7e6;
            color: var(--warning);
        }

        .indicator-alta {
            background-color: #fff0f3;
            color: var(--danger);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
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
            .container {
                padding: 1rem;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <i class="fas fa-tasks"></i>
            Gerenciador de Tarefas
        </div>
    </header>

    <div class="container">
        <h1 class="page-title">
            <i class="fas <?= isset($tarefa) ? 'fa-edit' : 'fa-plus' ?>"></i>
            <?= isset($tarefa) ? 'Editar' : 'Adicionar' ?> Tarefa
        </h1>

        <!-- Mensagens de alerta -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($tarefa) && !empty($tarefa->data_criacao)): ?>
            <div class="form-group">
                <label class="form-label">Data de Criação:</label>
                <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i', strtotime($tarefa->data_criacao)); ?>" readonly>
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle"></i> Esta data é automaticamente gerada quando a tarefa é criada.
                </small>
            </div>
        <?php endif; ?>
        <div class="card">
            <form method="post" action="">
                <div class="form-group">
                    <label class="form-label">Descrição:</label>
                    <textarea name="descrição" class="form-control" required placeholder="Descreva sua tarefa..."><?= isset($tarefa) ? htmlspecialchars($tarefa->descricao) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Data de Vencimento:</label>
                    <input type="date" name="data_vencimento" class="form-control" value="<?= isset($tarefa) ? $tarefa->data_vencimento : '' ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-control">
                        <option value="pendente" <?= (isset($tarefa) && $tarefa->status == 'pendente') ? 'selected' : '' ?>>Pendente</option>
                        <option value="andamento" <?= (isset($tarefa) && $tarefa->status == 'andamento') ? 'selected' : '' ?>>Em andamento</option>
                        <option value="concluido" <?= (isset($tarefa) && $tarefa->status == 'concluido') ? 'selected' : '' ?>>Concluído</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Prioridade:</label>
                    <select name="prioridade" class="form-control" id="prioridade-select">
                        <option value="0" <?= (isset($tarefa) && $tarefa->prioridade == 0) ? 'selected' : '' ?>>Baixa</option>
                        <option value="1" <?= (isset($tarefa) && $tarefa->prioridade == 1) ? 'selected' : '' ?>>Média</option>
                        <option value="2" <?= (isset($tarefa) && $tarefa->prioridade == 2) ? 'selected' : '' ?>>Alta</option>
                    </select>
                    <div class="priority-indicator">
                        <span class="indicator indicator-baixa" id="indicator-baixa">Baixa</span>
                        <span class="indicator indicator-media" id="indicator-media">Média</span>
                        <span class="indicator indicator-alta" id="indicator-alta">Alta</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Imagem:</label>
                     <input type="file" name="imagem" class="form-control" accept="image/*">
                    <?php if (isset($tarefa) && $tarefa->imagem): ?>
                    <div class="mt-2">
                     <img src="<?php echo base_url('uploads/' . $tarefa->imagem); ?>" alt="Imagem da tarefa" style="max-width: 200px;">
                    <br>
            <small>Imagem atual</small>
            </div>
        <?php endif; ?>
            </div>
                <div class="form-actions">
                    <a href="<?= site_url('tarefas') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Atualizar indicadores de prioridade
        document.addEventListener('DOMContentLoaded', function() {
            const prioridadeSelect = document.getElementById('prioridade-select');
            const indicators = {
                0: document.getElementById('indicator-baixa'),
                1: document.getElementById('indicator-media'),
                2: document.getElementById('indicator-alta')
            };
            
            function updatePriorityIndicators() {
                // Reset all indicators
                Object.values(indicators).forEach(indicator => {
                    indicator.style.opacity = '0.5';
                    indicator.style.transform = 'scale(0.95)';
                });
                
                // Highlight selected priority
                const selectedValue = prioridadeSelect.value;
                indicators[selectedValue].style.opacity = '1';
                indicators[selectedValue].style.transform = 'scale(1)';
            }
            
            // Initial update
            updatePriorityIndicators();
            
            // Update on change
            prioridadeSelect.addEventListener('change', updatePriorityIndicators);
            
            // Add click events to indicators to change selection
            Object.entries(indicators).forEach(([value, element]) => {
                element.addEventListener('click', () => {
                    prioridadeSelect.value = value;
                    updatePriorityIndicators();
                });
            });
        });
    </script>

<script>
    // Pré-visualização de imagem
    document.addEventListener('DOMContentLoaded', function() {
        const imagemInput = document.querySelector('input[name="imagem"]');
        
        if (imagemInput) {
            imagemInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove pré-visualizações anteriores
                        document.querySelectorAll('.imagem-preview').forEach(el => el.remove());
                        
                        // Cria nova pré-visualização
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.className = 'imagem-preview';
                        preview.alt = 'Pré-visualização da imagem';
                        
                        // Insere após o campo de upload
                        imagemInput.parentNode.appendChild(preview);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
</body>
</html>