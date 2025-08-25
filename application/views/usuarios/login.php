<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($tela == 'register') ? 'Registrar' : 'Login'; ?> - Sistema de Tarefas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-container {
            padding: 2rem;
        }

        .alert {
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert a {
            color: #721c24;
            text-decoration: underline;
            font-weight: bold;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 1rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background-color: var(--light);
            border-top: 1px solid var(--light-gray);
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .container {
                max-width: 100%;
            }
            
            .header, .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-tasks"></i>
                <?= ($tela == 'register') ? 'Criar Conta' : 'Acessar Sistema'; ?>
            </h1>
            <p>
                <?= ($tela == 'register') ? 'Registre-se para gerenciar suas tarefas' : 'Entre para acessar suas tarefas'; ?>
            </p>
        </div>

        <div class="form-container">
            <?php if(isset($erro)): ?>
                <?php if(isset($mensagem)): ?>
                    <div class="alert" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $erro; ?>
                </div>
                     <?php if ($this->session->flashdata('erro')): ?>
                <div class="alert" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $this->session->flashdata('erro'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('mensagem')): ?>
                <div class="alert" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $this->session->flashdata('mensagem'); ?>
                </div>
            <?php endif; ?>
            <?php endif; ?>

            <form method="post" action="">
                <?php if($tela == 'register'): ?>
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nome" class="form-control" placeholder="Seu nome completo" required value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="Seu e-mail" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="senha" id="passwordField" class="form-control" placeholder="Sua senha" required>
                    <button type="button" id="togglePassword" class="password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <?= ($tela == 'register') ? 'Criar Conta' : 'Entrar'; ?>
                </button>
            </form>
        </div>

        <div class="footer">
            <?php if($tela == 'register'): ?>
                <p>Já tem uma conta? <a href="<?php echo site_url('usuarios/login'); ?>">Faça login aqui</a></p>
            <?php else: ?>
                <p>Não tem uma conta? <a href="<?php echo site_url('usuarios/register'); ?>">Cadastre-se aqui</a></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('passwordField');
            
            togglePassword.addEventListener('click', function() {
                // Alternar o tipo do campo de senha
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Alternar o ícone
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('passwordField');
            const form = document.querySelector('form');
            
            // Validação do email
        form.addEventListener('submit', function(e) {
            const emailInput = document.querySelector('input[name="email"]');
                const email = emailInput.value;
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(email)) {
            e.preventDefault(); // Impede o envio do formulário
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert';
            alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Por favor, insira um email válido.';
            const formContainer = document.querySelector('.form-container');
            formContainer.insertBefore(alertDiv, form);
            setTimeout(() => alertDiv.remove(), 5000); // Remove o alerta após 5 segundos
            return;
        }
    });

    
        })
    
</script>
</body>
</html>