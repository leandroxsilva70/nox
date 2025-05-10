<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
</head>
<body>
    <!-- Cabeçalho -->
    <header class="header">
        <div class="header-container" style="display: flex; align-items: center; justify-content: space-between;">
            <!-- Logo -->
            <div class="logo-container">
                <a href="index.php" aria-label="Página inicial">
                    <img src="imagem_logotipo/logo.png" alt="Logotipo do Nox" class="logo">
                </a>
            </div>

            <!-- Navegação -->
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

            <!-- Informações do usuário -->
            <div class="user-info" style="margin-left: auto;">
                <div class="user-dropdown">
                    <!-- Exibe a imagem do usuário, ou a imagem padrão se não estiver logado -->
                    <img src="<?= isset($_SESSION['imagem']) ? $_SESSION['imagem'] : 'imagem_logotipo/usuario_padrao.png' ?>" alt="Usuário" class="user-avatar">
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <p><strong>Nome:</strong> <?= $_SESSION['usuario']['nome'] ?></p>
                            <p><strong>Tipo:</strong> <?= $_SESSION['usuario']['tipo'] ?></p>
                            <a href="php/logout.php" class="logout-btn">Sair</a>
                            <!-- Formulário para trocar imagem -->
                            <form action="php/upload_imagem.php" method="post" enctype="multipart/form-data">
                                <label for="nova_imagem">Trocar imagem:</label>
                                <input type="file" name="nova_imagem" id="nova_imagem" accept="image/*">
                                <button type="submit">Atualizar</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="intro">
            <h1>Bem-vindo ao Nox</h1>
            <p>Organize e participe de eventos escolares de forma simples e intuitiva.</p>

            <?php
            if (isset($_SESSION['nome'])) {
                echo "<p style='margin-top: 1rem;'>Você está logado como: <strong>" . $_SESSION['nome'] . "</strong> (" . $_SESSION['tipo'] . ")</p>";
            }
            ?>
        </section>

        <section class="slider">
            <div class="slide active">
                <h2>Organize eventos escolares</h2>
                <p>Com o Nox, professores e alunos podem criar e participar de eventos com facilidade.</p>
            </div>
            <div class="slide">
                <h2>Comunicação direta</h2>
                <p>Mantenha todos os participantes informados em tempo real com atualizações.</p>
            </div>
            <div class="slide">
                <h2>Gestão acessível</h2>
                <p>Ferramentas simples e intuitivas para gerenciar inscrições, presença e mais.</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; 2025 Nox. Todos os direitos reservados.</p>
            <div class="social-icons">
                <a href="#"><img src="imagens_rede/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="imagens_rede/facebook.svg" alt="Facebook"></a>
                <a href="#"><img src="imagens_rede/whatsapp.svg" alt="WhatsApp"></a>
            </div>
        </div>
    </footer>
</body>
</html>