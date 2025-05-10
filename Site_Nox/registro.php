<?php
session_start();

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

$mensagem_erro = '';
if (isset($_SESSION['erro_registro'])) {
    $mensagem_erro = htmlspecialchars($_SESSION['erro_registro'], ENT_QUOTES, 'UTF-8');
    unset($_SESSION['erro_registro']);
}

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
    <title>Registro - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
    <style>
        .register-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .register-heading {
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
        
        .register-footer {
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
        
        .form-text {
            font-size: 12px;
            color: #666;
            display: block;
            margin-top: 5px;
        }
        
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
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
            <section class="register-section" aria-labelledby="register-heading">
                <h1 id="register-heading" class="register-heading">Criar Conta</h1>

                <?php if (!empty($mensagem_erro)): ?>
                    <div class="alert-error" role="alert">
                        <?= $mensagem_erro ?>
                    </div>
                <?php endif; ?>

                <div class="register-container">
                    <form action="php/processar_registro.php" method="POST" class="register-form" autocomplete="on" id="registerForm">
                        <div class="form-group">
                            <label for="tipo_usuario">Tipo de Usuário:</label>
                            <select name="tipo_usuario" id="tipo_usuario" class="form-control" required aria-required="true">
                                <option value="">Selecione...</option>
                                <option value="aluno">Aluno</option>
                                <option value="professor">Professor</option>
                            </select>
                        </div>

                        <div class="form-group" id="aluno_fields">
                            <label for="rm">RM:</label>
                            <input type="text" id="rm" name="rm" class="form-control" pattern="[0-9]{6,}" 
                                title="Digite seu RM (apenas números)" aria-describedby="rm-help">
                            <small id="rm-help" class="form-text">Exemplo: 123456</small>
                        </div>

                        <div class="form-group" id="email_fields">
                            <label for="email">E-mail Institucional:</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                                title="Digite um e-mail válido" required>
                        </div>

                        <div class="form-group">
                            <label for="nome_completo">Nome Completo:</label>
                            <input type="text" id="nome_completo" name="nome_completo" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control" required minlength="6">
                            <small class="form-text">Mínimo de 6 caracteres</small>
                        </div>

                        <div class="form-group">
                            <label for="confirmar_senha">Confirmar Senha:</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required minlength="6">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-primary">Registrar</button>
                        </div>
                    </form>

                    <div class="register-footer">
                        <p>Já tem uma conta? <a href="login.php" class="text-link">Faça login aqui</a></p>
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
            const emailInput = document.getElementById('email');
            const registerForm = document.getElementById('registerForm');

            function toggleFields() {
                const tipo = tipoUsuario.value;

                if (tipo === 'aluno') {
                    alunoFields.style.display = 'block';
                    emailFields.querySelector('label').textContent = 'E-mail Institucional:';
                } else if (tipo === 'professor') {
                    alunoFields.style.display = 'none';
                    emailFields.querySelector('label').textContent = 'E-mail Institucional:';
                } else {
                    alunoFields.style.display = 'none';
                }
            }

            tipoUsuario.addEventListener('change', toggleFields);
            toggleFields(); // Inicializa os campos corretamente

            registerForm.addEventListener('submit', function(e) {
                const senha = document.getElementById('senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;
                const tipo = tipoUsuario.value;

                if (senha !== confirmarSenha) {
                    alert('As senhas não coincidem!');
                    e.preventDefault();
                    return;
                }

                if (tipo === 'aluno' && !document.getElementById('rm').value.trim()) {
                    alert('Por favor, informe seu RM.');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>