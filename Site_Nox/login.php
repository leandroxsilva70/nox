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
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos específicos para a página de login */
        .login-page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--dark-bg);
        }
        
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .login-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            text-align: center;
            margin: 30px 0;
        }
        
        .login-logo {
            margin-bottom: 30px;
        }
        
        .login-logo img {
            height: 60px;
            width: auto;
        }
        
        .login-title {
            color: var(--text-light);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .login-form select, 
        .login-form input {
            width: 100%;
            padding: 12px 15px;
            background-color: var(--darker-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        
        .login-form select:focus, 
        .login-form input:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background-color: var(--hover-blue);
            transform: translateY(-2px);
        }
        
        .login-divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: var(--text-muted);
        }
        
        .login-divider::before, 
        .login-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        
        .login-divider::before {
            margin-right: 15px;
        }
        
        .login-divider::after {
            margin-left: 15px;
        }
        
        .social-login {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background-color: var(--darker-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: var(--primary-blue);
        }
        
        .social-btn img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        
        .login-footer-links {
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-muted);
        }
        
        .login-footer-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            margin: 0 5px;
        }
        
        .login-footer-links a:hover {
            text-decoration: underline;
        }
        
        .alert-error {
            padding: 15px;
            margin-bottom: 25px;
            background-color: rgba(217, 83, 79, 0.2);
            border-left: 4px solid var(--danger-red);
            color: var(--text-light);
            border-radius: 4px;
            text-align: left;
            font-size: 14px;
        }
        
        .hidden {
            display: none;
        }
        
        /* Ajustes para o header e footer existentes */
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 20px;
            }
            
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-container {
                position: static;
                transform: none;
                order: 2;
                width: 100%;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body class="login-page">
    <!-- Header completo com navegação -->
    <header class="header">
        <div class="header-container">
            <div class="logo-container">
                <a href="index.php" aria-label="Página inicial">
                    <img src="imagem_logotipo/logo.png" alt="Logotipo do Nox" class="logo">
                </a>
            </div>
            <div class="nav-container">
                <ul class="nav-links">
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

    <main class="login-main">
        <div class="login-card">
            <div class="login-logo">
                <img src="imagem_logotipo/logo.png" alt="Nox">
            </div>
            
            <h1 class="login-title">Entrar no Nox</h1>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alert-error" role="alert">
                    <?= $mensagem_erro ?>
                </div>
            <?php endif; ?>
            
            <form action="php/processar_login.php" method="POST" class="login-form" autocomplete="on" id="loginForm">
                <div class="form-group">
                    <label for="tipo_usuario">Tipo de Usuário</label>
                    <select name="tipo_usuario" id="tipo_usuario" class="form-control" required aria-required="true">
                        <option value="">Selecione...</option>
                        <option value="aluno">Aluno</option>
                        <option value="professor">Professor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <div class="form-group hidden" id="aluno_fields">
                    <label for="rm">RM</label>
                    <input type="text" id="rm" name="rm" class="form-control" pattern="[0-9]{6,}" 
                        title="Digite seu RM (apenas números)" aria-describedby="rm-help">
                    <small id="rm-help" style="display: block; margin-top: 5px; font-size: 12px; color: var(--text-muted);">Exemplo: 123456</small>
                </div>
                
                <div class="form-group hidden" id="email_fields">
                    <label id="email_label" for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                        title="Digite um e-mail válido">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required minlength="6" aria-required="true">
                </div>
                
                <button type="submit" class="login-btn">Continuar</button>
            </form>
            
            <div class="login-divider">ou</div>
            
            <div class="social-login">
                <button type="button" class="social-btn">
                    <img src="imagens_rede/google.svg" alt="Google">
                    Continuar com Google
                </button>
                <button type="button" class="social-btn">
                    <img src="imagens_rede/microsoft.svg" alt="Microsoft">
                    Continuar com Microsoft
                </button>
            </div>
            
            <div class="login-footer-links">
                <a href="recuperar_senha.php">Não consegue entrar?</a> • 
                <a href="registro.php">Criar uma conta</a>
            </div>
            
            <!-- Seção de contas de teste (pode ser removida em produção) -->
            <div style="margin-top: 30px; padding: 15px; background-color: rgba(0,0,0,0.1); border-radius: 8px;">
                <h3 style="color: var(--text-light); font-size: 14px; margin-bottom: 10px;">Contas para teste:</h3>
                <ul style="text-align: left; color: var(--text-muted); font-size: 12px; list-style-type: none; padding-left: 5px;">
                    <li><strong>Admin:</strong> admin@nox.com / senha123</li>
                    <li><strong>Professor:</strong> carlos.henrique@etec.sp.gov.br / password </li>
                    <li><strong>Aluno:</strong> RM: 888888 / password</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- RODAPÉ -->
    <footer>
        <div class="footer-container" style="max-width: 1200px; margin: 0 auto; padding: 30px 20px 20px; background-color: var(--darker-bg); text-align: left;">
            <!-- Seção "Sobre o Nox" -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: var(--text-light); margin-bottom: 15px; font-size: 1.1rem;">Sobre o Nox</h3>
                <p style="color: var(--text-muted); margin-bottom: 20px; max-width: 600px;">
                    Plataforma de organização de eventos escolares para professores e alunos.
                </p>
                
                <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                    <div>
                        <h4 style="color: var(--text-light); font-size: 0.95rem; margin-bottom: 10px;">Eventos</h4>
                        <a href="eventos.php" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">Ver eventos disponíveis</a>
                    </div>
                    <div>
                        <h4 style="color: var(--text-light); font-size: 0.95rem; margin-bottom: 10px;">Contato</h4>
                        <a href="mailto:suporte@nox.com" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">suporte@nox.com</a>
                    </div>
                </div>
            </div>

            <hr style="border: none; height: 1px; background-color: var(--border-color); margin: 20px 0;">

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="display: flex; flex-wrap: wrap; gap: 15px 25px; margin-right: auto;">
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Português (BR)</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Privacidade</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Termos</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">Cookies</a>
                </div>
                
                <div style="display: flex; align-items: center; gap: 20px; margin-left: auto;">
                    <span style="color: var(--text-muted); font-size: 0.85rem;">
                        © 2025 Nox. Todos os direitos reservados.
                    </span>
                    
                    <div class="social-icons" style="display: flex; gap: 15px;">
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/instagram.svg" alt="Instagram" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/facebook.svg" alt="Facebook" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                        <a href="#" style="display: inline-flex; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.1); align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <img src="imagens_rede/whatsapp.svg" alt="WhatsApp" style="height: 16px; filter: brightness(0.8); transition: all 0.3s ease;">
                        </a>
                    </div>
                </div>
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
            
            // Mostra/oculta campos com base no tipo de usuário selecionado
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
            
            // Validação do formulário antes do envio
            document.getElementById('loginForm').addEventListener('submit', function(e) {
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
            
            // Foco inicial no campo de seleção
            tipoUsuario.focus();
        });
    </script>
</body>
</html>