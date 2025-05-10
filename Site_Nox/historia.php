<?php
session_start(); // Adicione esta linha no início do arquivo
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Nox</title>
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
                    <!-- Corrigido para usar a mesma estrutura de sessão que o index.php -->
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

    <!-- Conteúdo principal -->
    <main class="main-content">
        <section class="sobre-nos">
            <h1>Sobre a história do Nox</h1>
            <p>O Nox foi criado para facilitar a organização de eventos escolares, permitindo que alunos e professores interajam de forma intuitiva.</p>

            <h2>Nosso Objetivo</h2>
            <p>Nosso sistema busca tornar a administração de eventos mais eficiente, proporcionando uma plataforma prática e acessível para todos.</p>

            <h2>Tecnologias Utilizadas</h2>
            <p>O Nox foi desenvolvido utilizando HTML, CSS e PHP, com um banco de dados MySQL para armazenar informações dos usuários e eventos.</p>
            <?php
            if (isset($_SESSION['usuario'])) {
                echo "<p style='margin-top: 1rem;'>Você está logado como: <strong>" . 
                     htmlspecialchars($_SESSION['usuario']['nome']) . "</strong> (" . 
                     htmlspecialchars($_SESSION['usuario']['tipo']) . ")</p>";
            }
            ?>
        </section>

        <section class="equipe">
            <h2>Conheça a Equipe</h2>
            <div class="membros">
                <div class="membro">
                    <img src="imagem_logotipo/membro1.jpg" alt="Membro 1 - Leandro">
                    <h3>Leandro</h3>
                    <p>Desenvolvedor Full Stack</p>
                </div>
                <div class="membro">
                    <img src="imagem_logotipo/membro2.jpg" alt="Membro 2 - João">
                    <h3>João</h3>
                    <p>Desenvolvedor Web</p>
                </div>
                <div class="membro">
                    <img src="imagem_logotipo/membro3.jpg" alt="Membro 3 - Victor">
                    <h3>Victor</h3>
                    <p>Desenvolvedor Mobile</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?= date('Y') ?> Nox. Todos os direitos reservados.</p>
            <div class="social-icons">
                <div style="display: flex; justify-content: center; gap: 15px; margin-top: 10px;">
                    <a href="#" class="social-link" aria-label="Instagram">
                        <img src="imagens_rede/instagram.svg" alt="Instagram">
                    </a>
                    <a href="#" class="social-link" aria-label="Facebook">
                        <img src="imagens_rede/facebook.svg" alt="Facebook">
                    </a>
                    <a href="#" class="social-link" aria-label="WhatsApp">
                        <img src="imagens_rede/whatsapp.svg" alt="WhatsApp">
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
