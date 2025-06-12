<?php
session_start();
require_once __DIR__ . '/php/conexao.php';

// Verifica se o usuário está logado e é professor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'professor') {
    header('Location: login.php');
    exit();
}

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificação da conexão
if (!isset($conexao) || !($conexao instanceof mysqli) || $conexao->connect_error) {
    die("Erro na conexão com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Inicializa variáveis
$eventos = array();
$eventoEdicao = null;

// Fetch events from database
$query = "SELECT * FROM eventos ORDER BY data_inicio DESC";
$result = $conexao->query($query);

if ($result) {
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'Erro ao carregar eventos: ' . $conexao->error
    ];
}

// Handle event deletion
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conexao->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = [
            'tipo' => 'success',
            'texto' => 'Evento removido com sucesso!'
        ];
    } else {
        $_SESSION['mensagem'] = [
            'tipo' => 'danger',
            'texto' => 'Erro ao remover evento: ' . $stmt->error
        ];
    }
    $stmt->close();
    
    header('Location: gerenciar_eventos.php');
    exit();
}

// Check if we're editing an event
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conexao->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $eventoEdicao = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $titulo = $conexao->real_escape_string($_POST['titulo']);
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $local = $conexao->real_escape_string($_POST['local'] ?? '');
    $data_inicio = $conexao->real_escape_string($_POST['data_inicio']);
    $hora_inicio = $conexao->real_escape_string($_POST['hora_inicio']);
    $data_fim = $conexao->real_escape_string($_POST['data_fim'] ?? null);
    $hora_fim = $conexao->real_escape_string($_POST['hora_fim'] ?? null);
    
    // Handle file upload
    $imagem = isset($eventoEdicao['imagem']) ? $eventoEdicao['imagem'] : null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/eventos/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetPath)) {
            // Remove a imagem antiga se existir
            if ($imagem && file_exists($imagem)) {
                unlink($imagem);
            }
            $imagem = $targetPath;
        }
    }
    
    if ($id) {
        // Update existing event
        if ($imagem) {
            $stmt = $conexao->prepare("UPDATE eventos SET titulo=?, descricao=?, local=?, data_inicio=?, hora_inicio=?, data_fim=?, hora_fim=?, imagem=? WHERE id=?");
            $stmt->bind_param("ssssssssi", $titulo, $descricao, $local, $data_inicio, $hora_inicio, $data_fim, $hora_fim, $imagem, $id);
        } else {
            $stmt = $conexao->prepare("UPDATE eventos SET titulo=?, descricao=?, local=?, data_inicio=?, hora_inicio=?, data_fim=?, hora_fim=? WHERE id=?");
            $stmt->bind_param("sssssssi", $titulo, $descricao, $local, $data_inicio, $hora_inicio, $data_fim, $hora_fim, $id);
        }
    } else {
        // Insert new event
        $stmt = $conexao->prepare("INSERT INTO eventos (titulo, descricao, local, data_inicio, hora_inicio, data_fim, hora_fim, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $titulo, $descricao, $local, $data_inicio, $hora_inicio, $data_fim, $hora_fim, $imagem);
    }
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = [
            'tipo' => 'success',
            'texto' => $id ? 'Evento atualizado com sucesso!' : 'Evento adicionado com sucesso!'
        ];
    } else {
        $_SESSION['mensagem'] = [
            'tipo' => 'danger',
            'texto' => 'Erro ao salvar evento: ' . $stmt->error
        ];
    }
    $stmt->close();
    
    header('Location: gerenciar_eventos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eventos - Nox</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagem_logotipo/favicon.ico">
    <style>
        :root {
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --card-bg: #2a3a5e;
            --text-light: #f8f9fa;
            --text-muted: #9ca3af;
            --primary-blue: #3b82f6;
            --accent-yellow:rgb(255, 255, 255);
            --hover-blue: #2563eb;
            --danger-red: #d9534f;
            --success-green: #28a745;
            --border-color: #374151;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            color: var(--accent-yellow);
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .card-title {
            color: var(--primary-blue);
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--darker-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--hover-blue);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-red);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c12e2a;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .event-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        .event-table th {
            background-color: var(--darker-bg);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }

        .event-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-light);
        }

        .event-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .event-table tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--text-light);
            border-color: var(--success-green);
        }

        .alert-danger {
            background-color: rgba(217, 83, 79, 0.2);
            color: var(--text-light);
            border-color: var(--danger-red);
        }

        .image-preview-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .remove-image {
            color: var(--danger-red);
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .remove-image:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn-group {
                flex-direction: column;
            }
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

            <nav class="nav-container">
                <ul class="nav-links">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="historia.php">Sobre Nós</a></li>
                    <li><a href="eventos.php">Eventos</a></li>
                    <li><a href="gerenciar_eventos.php" class="active">Gerenciar Eventos</a></li>
                </ul>
            </nav>
            
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

    <main class="container">
        <h1 class="page-title">Gerenciar Eventos</h1>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['mensagem']['tipo'] ?>">
                <?= $_SESSION['mensagem']['texto'] ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="card-title">Eventos Cadastrados</h2>
            
            <?php if (count($eventos) > 0): ?>
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Data e Hora</th>
                            <th>Local</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td><?= htmlspecialchars($evento['titulo']) ?></td>
                                <td>
                                    <?= date('d/m/Y', strtotime($evento['data_inicio'])) ?>
                                    <?= (!empty($evento['hora_inicio'])) ? ' às ' . substr($evento['hora_inicio'], 0, 5) : '' ?>
                                </td>
                                <td><?= !empty($evento['local']) ? htmlspecialchars($evento['local']) : '--' ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="gerenciar_eventos.php?editar=<?= $evento['id'] ?>" class="btn btn-primary">Editar</a>
                                        <a href="gerenciar_eventos.php?excluir=<?= $evento['id'] ?>" class="btn btn-danger" 
                                           onclick="return confirm('Tem certeza que deseja remover este evento?')">Remover</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum evento cadastrado.</p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2 class="card-title"><?= isset($eventoEdicao) ? 'Editar Evento' : 'Adicionar Novo Evento' ?></h2>
            
            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <?php if (isset($eventoEdicao)): ?>
                    <input type="hidden" name="id" value="<?= $eventoEdicao['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group full-width">
                    <label for="titulo">Título do Evento*</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" 
                           value="<?= isset($eventoEdicao) ? htmlspecialchars($eventoEdicao['titulo']) : '' ?>" required>
                </div>
                
                <div class="form-group full-width">
                    <label for="descricao">Descrição*</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="5" required><?= isset($eventoEdicao) ? htmlspecialchars($eventoEdicao['descricao']) : '' ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="local">Local</label>
                    <input type="text" id="local" name="local" class="form-control" 
                           value="<?= isset($eventoEdicao) ? htmlspecialchars($eventoEdicao['local']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="data_inicio">Data de Início*</label>
                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" 
                           value="<?= isset($eventoEdicao) ? htmlspecialchars(date('Y-m-d', strtotime($eventoEdicao['data_inicio']))) : '' ?>" required>
                </div>
                
                <div class="form-group">
    <label for="hora_inicio">Hora de Início*</label>
    <input type="time" id="hora_inicio" name="hora_inicio" class="form-control"
           value="<?= !empty($evento['hora_inicio']) ? htmlspecialchars(substr($evento['hora_inicio'], 0, 5)) : '' ?>">
</div>

<div class="form-group">
    <label for="data_fim">Data de Término</label>
    <input type="date" id="data_fim" name="data_fim" class="form-control"
           value="<?= isset($eventoEdicao) && !empty($eventoEdicao['data_fim']) ? htmlspecialchars(date('Y-m-d', strtotime($eventoEdicao['data_fim']))) : '' ?>">
</div>

<div class="form-group">
    <label for="hora_fim">Hora de Término</label>
    <input type="time" id="hora_fim" name="hora_fim" class="form-control"
           value="<?= !empty($eventoEdicao['hora_fim']) ? htmlspecialchars(substr($eventoEdicao['hora_fim'], 0, 5)) : '' ?>">
</div>

                
                <div class="form-group full-width">
                    <label for="imagem">Imagem do Evento</label>
                    <?php if (isset($eventoEdicao) && $eventoEdicao['imagem']): ?>
                        <div class="image-preview-container">
                            <img src="<?= htmlspecialchars($eventoEdicao['imagem']) ?>" alt="Imagem atual do evento" class="image-preview">
                            <span class="remove-image" onclick="document.getElementById('remove-imagem').value = '1'; this.previousElementSibling.style.display = 'none'; this.style.display = 'none';">
                                Remover imagem
                            </span>
                            <input type="hidden" id="remove-imagem" name="remove-imagem" value="0">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                </div>
                
                <div class="form-group full-width btn-group">
                    <button type="submit" class="btn btn-primary">
                        <?= isset($eventoEdicao) ? 'Atualizar Evento' : 'Adicionar Evento' ?>
                    </button>
                    
                    <?php if (isset($eventoEdicao)): ?>
                        <a href="gerenciar_eventos.php" class="btn btn-danger">Cancelar Edição</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </main>

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
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const titulo = document.getElementById('titulo').value.trim();
                    const descricao = document.getElementById('descricao').value.trim();
                    const dataInicio = document.getElementById('data_inicio').value;
                    
                    if (!titulo || !descricao || !dataInicio) {
                        e.preventDefault();
                        alert('Por favor, preencha todos os campos obrigatórios (marcados com *).');
                        return false;
                    }
                    
                    const dataFim = document.getElementById('data_fim').value;
                    if (dataFim && new Date(dataFim) < new Date(dataInicio)) {
                        e.preventDefault();
                        alert('A data de término não pode ser anterior à data de início.');
                        return false;
                    }
                });
            }
            
            // Verifica se há um evento em edição e rola até o formulário
            <?php if (isset($eventoEdicao)): ?>
                document.querySelector('.card:last-child').scrollIntoView({
                    behavior: 'smooth'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>