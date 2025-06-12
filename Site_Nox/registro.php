<?php
session_start();

// Configurações de segurança
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

require_once 'php/conexao.php';

// Verifique se a conexão foi estabelecida
if (!isset($conexao) || $conexao->connect_error) {
    die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Mensagens de erro
$mensagem_erro = '';
if (isset($_SESSION['erro_registro'])) {
    $mensagem_erro = htmlspecialchars($_SESSION['erro_registro'], ENT_QUOTES, 'UTF-8');
    unset($_SESSION['erro_registro']);
}

if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Buscar ETECs do banco de dados
$etecs = [];
try {
    $resultado = $conexao->query("SELECT id, nome_etec FROM etecs WHERE ativo = 1");
    if ($resultado) {
        $etecs = $resultado->fetch_all(MYSQLI_ASSOC);
    } else {
        throw new Exception("Erro ao buscar ETECs: " . $conexao->error);
    }
} catch (Exception $e) {
    error_log("Erro ao buscar ETECs: " . $e->getMessage());
    $mensagem_erro = "Erro ao carregar ETECs. Por favor, recarregue a página.";
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
        /* Estilos gerais baseados no tema escuro */
        .register-page {
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .register-container {
            width: 100%;
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .register-heading {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            color: var(--text-light);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: var(--darker-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--hover-blue);
            transform: translateY(-2px);
        }
        
        .form-text {
            font-size: 12px;
            color: var(--text-muted);
            display: block;
            margin-top: 5px;
        }
        
        .alert-error {
            padding: 15px;
            margin-bottom: 20px;
            background-color: rgba(217, 83, 79, 0.2);
            border-left: 4px solid var(--danger-red);
            color: var(--text-light);
            border-radius: 4px;
        }
        
        .register-footer {
            margin-top: 20px;
            text-align: center;
            color: var(--text-muted);
        }
        
        .text-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .text-link:hover {
            text-decoration: underline;
        }
        
        /* Esconder campos inicialmente */
        .hidden {
            display: none;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .register-container {
                padding: 20px;
                margin: 20px auto;
            }
            
            .register-heading {
                font-size: 20px;
            }
        }
    </style>
</head>
<body class="register-page">
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
                        <input type="hidden" name="tipo" id="hidden_tipo" value="">
                        
                        <div class="form-group">
                            <label for="nome">Nome Completo:</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>

                        <div class="form-group hidden" id="email_fields">
                            <label for="email">E-mail Pessoal:</label>
                            <input type="email" id="email" name="email" class="form-control"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            <small class="form-text">Apenas para contato administrativo</small>
                        </div>

                        <div class="form-group">
                            <label for="tipo_usuario">Tipo de Usuário:</label>
                            <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="aluno">Aluno</option>
                                <option value="professor">Professor</option>
                            </select>
                        </div>

                        <div class="form-group hidden" id="aluno_fields">
                            <label for="rm">RM:</label>
                            <input type="text" id="rm" name="rm" class="form-control" pattern="[0-9]{6,}">
                            <small class="form-text">Exemplo: 123456</small>
                            
                            <label for="etec" style="margin-top: 15px;">ETEC:</label>
                            <select id="etec" name="etec" class="form-control" required>
                                <option value="">Selecione sua ETEC</option>
                                <?php foreach ($etecs as $etec): ?>
                                    <option value="<?= $etec['id'] ?>"><?= htmlspecialchars($etec['nome_etec']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group hidden" id="professor_fields">
                            <label for="email_institucional">E-mail Institucional:</label>
                            <input type="email" id="email_institucional" name="email_institucional" class="form-control" required
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            <small class="form-text">Seu e-mail @etec.sp.gov.br</small>
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
            const professorFields = document.getElementById('professor_fields');
            const emailFields = document.getElementById('email_fields');
            const hiddenTipo = document.getElementById('hidden_tipo');
            const registerForm = document.getElementById('registerForm');

            function toggleFields() {
                const tipo = tipoUsuario.value;
                
                // Oculta todos os campos específicos
                alunoFields.classList.add('hidden');
                professorFields.classList.add('hidden');
                emailFields.classList.add('hidden');
                
                // Mostra apenas os campos relevantes
                if (tipo === 'aluno') {
                    alunoFields.classList.remove('hidden');
                } else if (tipo === 'professor') {
                    professorFields.classList.remove('hidden');
                    emailFields.classList.remove('hidden');
                }
                
                // Atualiza o campo hidden que será enviado
                hiddenTipo.value = tipo;
            }

            tipoUsuario.addEventListener('change', toggleFields);
            
            // Validação do formulário
            registerForm.addEventListener('submit', function(e) {
                const tipo = tipoUsuario.value;
                const senha = document.getElementById('senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;
                
                // Verifica se as senhas coincidem
                if (senha !== confirmarSenha) {
                    alert('As senhas não coincidem!');
                    e.preventDefault();
                    return;
                }
                
                // Validações específicas por tipo
                if (tipo === 'aluno') {
                    const rm = document.getElementById('rm').value;
                    const etec = document.getElementById('etec').value;
                    
                    if (!rm || !etec) {
                        alert('Por favor, preencha todos os campos obrigatórios para aluno.');
                        e.preventDefault();
                    }
                } else if (tipo === 'professor') {
                    const emailInst = document.getElementById('email_institucional').value;
                    const email = document.getElementById('email').value;
                    
                    if (!emailInst || !email) {
                        alert('Por favor, informe todos os e-mails obrigatórios.');
                        e.preventDefault();
                    }
                } else {
                    alert('Selecione um tipo de usuário válido.');
                    e.preventDefault();
                }
            });
            
            // Inicializa os campos
            toggleFields();
        });
    </script>
</body>
</html>