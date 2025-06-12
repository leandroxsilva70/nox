<header class="header">
    <div class="header-container">
        <div class="logo-container">
            <a href="index.php" aria-label="Página inicial">
                <img src="imagem_logotipo/logo.png" alt="Logotipo do Nox" class="logo">
            </a>
        </div>

        <nav class="nav-container">
            <ul class="nav-links">
                <li><a href="index.php">Inicial</a></li>
                <li><a href="historia.php">Sobre Nós</a></li>
                <li><a href="eventos.php">Eventos</a></li>
                <li><a href="gerenciar_eventos.php">Gerenciar Eventos</a></li>
            </ul>
        </nav>
        
        <div class="user-info">
            <div class="user-dropdown">
                <img src="<?= isset($_SESSION['usuario']['imagem']) ? htmlspecialchars($_SESSION['usuario']['imagem']) : 'imagem_logotipo/usuario_padrao.png' ?>" alt="Usuário" class="user-avatar">
                <div class="dropdown-content">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></p>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($_SESSION['usuario']['tipo']) ?></p>
                    <a href="php/logout.php" class="logout-btn">Sair</a>
                    <form action="php/upload_imagem.php" method="post" enctype="multipart/form-data">
                        <label for="nova_imagem">Trocar imagem:</label>
                        <input type="file" name="nova_imagem" id="nova_imagem" accept="image/*">
                        <button type="submit">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>