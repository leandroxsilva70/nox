<?php
session_start();
require_once 'php/conexao.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$tipoUsuario = $_SESSION['usuario']['tipo'];
$idUsuario = $_SESSION['usuario']['id'];

// Consulta eventos ativos
$eventos = $conn->query("SELECT * FROM eventos");

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
</head>
<body>
    <!-- Cabeçalho idêntico ao index.php -->
    <header class="header">
        <div class="header-container">
            <div class="logo-container">
                <a href="index.php"><img src="imagem_logotipo/logo.png" alt="Logotipo do Nox" class="logo"></a>
            </div>
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="historia.php">Sobre Nós</a></li>
                    <li><a href="eventos.php">Eventos</a></li>
                    <?php if ($tipoUsuario === 'professor'): ?>
                        <li><a href="gerenciar_eventos.php">Gerenciar Eventos</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="user-info">
                <div class="user-dropdown">
                    <img src="<?= $_SESSION['imagem'] ?? 'imagem_logotipo/usuario_padrao.png' ?>" alt="Usuário" class="user-avatar">
                    <div class="dropdown-content">
                        <p><strong>Nome:</strong> <?= $_SESSION['usuario']['nome'] ?></p>
                        <p><strong>Tipo:</strong> <?= $tipoUsuario ?></p>
                        <a href="php/logout.php" class="logout-btn">Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main>
        <section class="intro">
            <h1>Eventos Ativos</h1>
            <p>Veja os eventos disponíveis e participe!</p>
        </section>

        <?php while ($evento = $eventos->fetch_assoc()) {
            $idEvento = $evento['id'];
        ?>
        <section class="evento">
            <h2><?= $evento['titulo'] ?></h2>
            <p><strong>Data:</strong> <?= $evento['data'] ?> | <strong>Hora:</strong> <?= $evento['hora'] ?></p>
            <p><strong>Local:</strong> <?= $evento['local'] ?></p>
            <p><?= $evento['descricao'] ?></p>

            <?php if ($tipoUsuario === 'aluno'): ?>
                <?php
                // Verifica inscrição
                $inscrito = $conn->query("SELECT * FROM inscricoes WHERE id_usuario = $idUsuario AND id_evento = $idEvento")->num_rows > 0;
                if (!$inscrito): ?>
                    <form action="php/inscrever_evento.php" method="POST">
                        <input type="hidden" name="id_evento" value="<?= $idEvento ?>">
                        <button type="submit">Inscrever-se</button>
                    </form>
                <?php else: ?>
                    <p>Você já está inscrito.</p>
                <?php endif; ?>

                <?php
                // Verifica avaliação
                $av = $conn->query("SELECT nota FROM avaliacoes WHERE id_usuario = $idUsuario AND id_evento = $idEvento");
                if ($av->num_rows === 0): ?>
                    <form action="php/avaliar_evento.php" method="POST">
                        <input type="hidden" name="id_evento" value="<?= $idEvento ?>">
                        <label>Avalie (1 a 5):</label>
                        <input type="number" name="nota" min="1" max="5" required>
                        <button type="submit">Avaliar</button>
                    </form>
                <?php else:
                    $nota = $av->fetch_assoc()['nota'];
                    echo "<p>Sua nota: $nota/5</p>";
                endif;
            endif;
            
            // Média geral
            $media = $conn->query("SELECT AVG(nota) AS media FROM avaliacoes WHERE id_evento = $idEvento")->fetch_assoc()['media'];
            if ($media) {
                echo "<p><strong>Média:</strong> " . round($media, 2) . "/5</p>";
            }
            ?>
        </section>
        <hr>
        <?php } ?>
    </main>

    <!-- Rodapé -->
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
