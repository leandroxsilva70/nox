<?php
session_start();
require_once __DIR__ . '/php/conexao.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conexao) || !($conexao instanceof mysqli) || $conexao->connect_error) {
    die("Database connection error. Please try again later.");
}

$stmt = $conexao->prepare("SELECT * FROM eventos WHERE status = 'ativo'");
if (!$stmt->execute()) {
    die("Error querying events: " . $conexao->error);
}
$eventos = $stmt->get_result();

$tipoUsuario = null;
$idUsuario = null;
if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
    $tipoUsuario = $_SESSION['usuario']['tipo'] ?? null;
    $idUsuario = $_SESSION['usuario']['id'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
    <style>
        .evento {
            margin-bottom: 30px;
            padding: 20px;
            background: var(--bg-color);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }
        
        .evento-imagem {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .evento-imagem img {
            max-width: 100%;
            max-height: 400px;
            width: auto;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            aspect-ratio: 16/9;
        }
        
        .evento h2 {
            color: var(--text-light);
            margin-bottom: 10px;
        }
        
        .evento p {
            margin-bottom: 10px;
            color: var(--text-muted);
        }
        
        .evento form {
            margin-top: 15px;
            padding: 10px 0;
            border-top: 1px solid var(--border-color);
        }
        
        .evento button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .evento button:hover {
            background-color: var(--primary-dark);
        }
        
        .evento input[type="number"],
        .evento textarea {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: var(--darker-bg);
            color: var(--text-light);
        }
        
        .evento input[type="number"] {
            width: 60px;
            margin-right: 10px;
        }
        
        .evento textarea {
            width: 100%;
            min-height: 80px;
            resize: vertical;
        }
        
        .intro {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: var(--darker-bg);
            border-radius: 8px;
        }
        
        hr {
            border: 0;
            height: 1px;
            background-color: var(--border-color);
            margin: 20px 0;
        }
        
        .avaliacoes-container {
            margin-top: 20px;
            padding: 15px;
            background-color: var(--darker-bg);
            border-radius: 8px;
        }
        
        .avaliacao {
            padding: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .avaliacao p {
            margin: 5px 0;
        }

        .user-avatar, .avaliacao img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
        }
    </style>
</head>
<body>
    
    <header class="header">
        <div class="header-container" style="display: flex; align-items: center; justify-content: space-between;">
            
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
                    <?php if (isset($tipoUsuario) && $tipoUsuario === 'professor'): ?>
                        <li><a href="gerenciar_eventos.php">Gerenciar Eventos</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            
            <div class="user-info">
                <div class="user-dropdown">
                    <img src="<?= isset($_SESSION['imagem']) ? $_SESSION['imagem'] : 'imagem_logotipo/usuario_padrao.png' ?>" 
                         alt="Foto do usuário" class="user-avatar">
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></p>
                            <p><strong>Tipo:</strong> <?= htmlspecialchars($_SESSION['usuario']['tipo']) ?></p>
                            <a href="php/logout.php" class="logout-btn">Sair</a>
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
            <h1>Eventos Ativos</h1>
            <p>Veja os eventos disponíveis na nossa plataforma</p>
        </section>

        <?php 
        if ($eventos->num_rows > 0) {
            while ($evento = $eventos->fetch_assoc()) {
                $idEvento = $evento['id'];
                $dataEvento = !empty($evento['data']) ? date('d/m/Y', strtotime($evento['data'])) : date('d/m/Y', strtotime($evento['data_inicio']));
                $horaEvento = !empty($evento['hora']) ? substr($evento['hora'], 0, 5) : date('H:i', strtotime($evento['data_inicio']));
        ?>
        <section class="evento">
            <?php if (!empty($evento['imagem'])): ?>
                <div class="evento-imagem">
                    <img src="<?= htmlspecialchars($evento['imagem']) ?>" alt="Imagem do evento <?= htmlspecialchars($evento['titulo'] ?? $evento['nome']) ?>" loading="lazy">
                </div>
            <?php endif; ?>
            
            <h2><?= htmlspecialchars($evento['titulo'] ?? $evento['nome']) ?></h2>
            <p><strong>Data:</strong> <?= $dataEvento ?> | <strong>Hora:</strong> <?= $horaEvento ?></p>
            <?php if (!empty($evento['local'])): ?>
                <p><strong>Local:</strong> <?= htmlspecialchars($evento['local']) ?></p>
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($evento['descricao'])) ?></p>

            <?php if (isset($_SESSION['usuario'])): ?>
                <?php if ($tipoUsuario === 'aluno'): ?>
                    <?php
                    // Check registration
                    $stmt = $conexao->prepare("SELECT * FROM inscricoes_eventos WHERE usuario_id = ? AND evento_id = ?");
                    $stmt->bind_param("ii", $idUsuario, $idEvento);
                    $stmt->execute();
                    $inscrito = $stmt->get_result();
                    
                    if ($inscrito->num_rows > 0): ?>
                        <p>Você já está inscrito neste evento.</p>
                    <?php else: ?>
                        <form action="php/inscrever_evento.php" method="POST">
                            <input type="hidden" name="id_evento" value="<?= $idEvento ?>">
                            <button type="submit">Inscrever-se</button>
                        </form>
                    <?php endif; ?>

                    <?php
                    // Check if user has already rated this event
                    $stmt = $conexao->prepare("SELECT nota, comentario FROM avaliacoes WHERE usuario_id = ? AND evento_id = ?");
                    $stmt->bind_param("ii", $idUsuario, $idEvento);
                    $stmt->execute();
                    $avaliacao = $stmt->get_result();
                    
                    if ($avaliacao->num_rows === 0): ?>
                        <form action="php/avaliar_evento.php" method="POST">
                            <input type="hidden" name="id_evento" value="<?= $idEvento ?>">
                            <div>
                                <label>Avalie (1 a 5):</label>
                                <input type="number" name="nota" min="1" max="5" required>
                            </div>
                            <div>
                                <label>Comentário (opcional):</label>
                                <textarea name="comentario" placeholder="Deixe seu comentário sobre o evento"></textarea>
                            </div>
                            <button type="submit">Enviar Avaliação</button>
                        </form>
                    <?php else: 
                        $av = $avaliacao->fetch_assoc();
                        $nota = $av['nota'];
                        $comentario = $av['comentario'];
                        echo "<p><strong>Sua nota:</strong> $nota/5</p>";
                        if (!empty($comentario)) {
                            echo "<p><strong>Seu comentário:</strong> " . nl2br(htmlspecialchars($comentario)) . "</p>";
                        }
                        
                        // Average rating
                        $stmt = $conexao->prepare("SELECT AVG(nota) AS media, COUNT(*) AS total FROM avaliacoes WHERE evento_id = ?");
                        $stmt->bind_param("i", $idEvento);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        $media = $result['media'];
                        $totalAvaliacoes = $result['total'];
                        
                        if ($media) {
                            echo "<p><strong>Avaliação média:</strong> " . round($media, 2) . "/5 (baseado em $totalAvaliacoes avaliações)</p>";
                        }
                    endif;
                    ?>
                    
                    <?php
                    // Show other users' ratings and comments
                    $stmt = $conexao->prepare("SELECT a.*, u.nome, u.imagem 
                                             FROM avaliacoes a 
                                             JOIN usuarios u ON a.usuario_id = u.id 
                                             WHERE a.evento_id = ? AND a.comentario IS NOT NULL AND a.comentario != '' 
                                             ORDER BY a.data_avaliacao DESC");
                    $stmt->bind_param("i", $idEvento);
                    $stmt->execute();
                    $comentarios = $stmt->get_result();
                    
                    if ($comentarios->num_rows > 0): ?>
                        <div class="avaliacoes-container">
                            <h3>Avaliações e Comentários</h3>
                            <?php while ($comentario = $comentarios->fetch_assoc()): ?>
                                <div class="avaliacao">
                                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                        <img src="<?= htmlspecialchars($comentario['imagem']) ?>" alt="<?= htmlspecialchars($comentario['nome']) ?>" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                                        <div>
                                            <p style="font-weight: bold; margin: 0;"><?= htmlspecialchars($comentario['nome']) ?></p>
                                            <p style="margin: 0;"><?= $comentario['nota'] ?>/5 - <?= date('d/m/Y', strtotime($comentario['data_avaliacao'])) ?></p>
                                        </div>
                                    </div>
                                    <p><?= nl2br(htmlspecialchars($comentario['comentario'])) ?></p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <p><a href="login.php">Faça login</a> para se inscrever nos eventos.</p>
            <?php endif; ?>
        </section>
        <hr>
        <?php 
            }
        } else {
            echo "<p>Nenhum evento ativo no momento.</p>";
        }
        ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container" style="max-width: 1200px; margin: 0 auto; padding: 30px 20px 20px; background-color: var(--darker-bg); text-align: left;">
            <!-- About Nox section -->
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
</body>
</html>