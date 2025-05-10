<?php
session_start();

// Configurações de segurança básicas
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// Mensagens de erro
$mensagem_erro = '';
if (isset($_SESSION['erro_login'])) {
    $mensagem_erro = htmlspecialchars($_SESSION['erro_login'], ENT_QUOTES, 'UTF-8');
    unset($_SESSION['erro_login']);
}

// Redireciona se já estiver logado
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
    <style>
        .login-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .login-heading {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary:hover {
            background-color: #0069d9;
        }
        
        .hidden {
            display: none;
        }
        
        .login-footer {
            margin-top: 20px;
            text-align: center;
        }
        
        .text-link {
            color: #007bff;
            text-decoration: none;
        }
        
        .text-link:hover {
            text-decoration: underline;
        }
        
        .alert-error {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-container">
                <a href="index.php" aria-label="Página inicial">
                    <img src="imagem_logotipo/logo.png" alt="Logotipo do Nox" class="logo">
                </a>
            </div>
            <div class="nav-container" style="flex-grow: 1; text-align: center;">
                <ul class="nav-links" style="display: inline-flex; list-style: none; padding: 0; margin: 0; gap: 20px;">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="historia.php">Sobre Nós</a></li>
                    <li><a href="eventos.php">Eventos</a></li>
                    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'professor'): ?>
                        <li><a href="gerenciar_eventos.php">Gerenciar Eventos</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="login-section" aria-labelledby="login-heading">
                <h1 id="login-heading" class="login-heading">Login</h1>

                <?php if (!empty($mensagem_erro)): ?>
                    <div class="alert-error" role="alert">
                        <?= $mensagem_erro ?>
                    </div>
                <?php endif; ?>

                <div class="login-container">
                    <form action="php/processar_login.php" method="POST" class="login-form" autocomplete="on" id="loginForm">
                        <div class="form-group">
                            <label for="tipo_usuario">Tipo de Usuário:</label>
                            <select name="tipo_usuario" id="tipo_usuario" class="form-control" required aria-required="true">
                                <option value="">Selecione...</option>
                                <option value="aluno">Aluno</option>
                                <option value="professor">Professor</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div class="form-group hidden" id="aluno_fields">
                            <label for="rm">RM:</label>
                            <input type="text" id="rm" name="rm" class="form-control" pattern="[0-9]{6,}" 
                                title="Digite seu RM (apenas números)" aria-describedby="rm-help">
                            <small id="rm-help" class="form-text">Exemplo: 123456</small>
                        </div>

                        <div class="form-group hidden" id="email_fields">
                            <label id="email_label" for="email">E-mail:</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                                title="Digite um e-mail válido">
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control" required minlength="6" aria-required="true">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-primary">Entrar</button>
                        </div>
                    </form>

                    <div class="login-footer">
                        <p>Não tem uma conta? <a href="registro.php" class="text-link">Registre-se aqui</a></p>
                        <p><a href="recuperar_senha.php" class="text-link">Esqueceu a senha?</a></p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?= date('Y') ?> Nox. Todos os direitos reservados.</p>
            <div class="social-icons">
                <a href="#" aria-label="Instagram"><img src="imagens_rede/instagram.svg" alt="Instagram"></a>
                <a href="#" aria-label="Facebook"><img src="imagens_rede/facebook.svg" alt="Facebook"></a>
                <a href="#" aria-label="WhatsApp"><img src="imagens_rede/whatsapp.svg" alt="WhatsApp"></a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoUsuario = document.getElementById('tipo_usuario');
            const alunoFields = document.getElementById('aluno_fields');
            const emailFields = document.getElementById('email_fields');
            const emailLabel = document.getElementById('email_label');
            const rmInput = document.getElementById('rm');
            const emailInput = document.getElementById('email');
            const loginForm = document.getElementById('loginForm');

            function toggleFields() {
                const tipo = tipoUsuario.value;

                alunoFields.classList.add('hidden');
                emailFields.classList.add('hidden');
                
                rmInput.required = false;
                emailInput.required = false;

                if (tipo === 'aluno') {
                    alunoFields.classList.remove('hidden');
                    rmInput.required = true;
                } else if (tipo === 'professor' || tipo === 'admin') {
                    emailFields.classList.remove('hidden');
                    emailInput.required = true;
                    emailLabel.textContent = tipo === 'professor' ? 'E-mail Institucional:' : 'E-mail:';
                }
            }

            tipoUsuario.addEventListener('change', toggleFields);
            
            loginForm.addEventListener('submit', function(e) {
                const tipo = tipoUsuario.value;
                let isValid = true;

                if (tipo === '') {
                    alert('Por favor, selecione um tipo de usuário.');
                    isValid = false;
                } else if (tipo === 'aluno' && !rmInput.value.trim()) {
                    alert('Por favor, informe seu RM.');
                    isValid = false;
                } else if ((tipo === 'professor' || tipo === 'admin') && !emailInput.value.trim()) {
                    alert('Por favor, informe seu e-mail.');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>