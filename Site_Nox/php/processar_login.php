<?php
require_once 'conexao.php';

// Verifica método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro_login'] = "Método inválido";
    header("Location: ../login.php");
    exit;
}

// Sanitização
$tipo_usuario = limparInput($_POST['tipo_usuario'] ?? '');
$identificador = limparInput($_POST[$tipo_usuario === 'aluno' ? 'rm' : 'email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validações
if (empty($tipo_usuario) || empty($identificador) || empty($senha)) {
    $_SESSION['erro_login'] = "Preencha todos os campos";
    header("Location: ../login.php");
    exit;
}

try {
    // Consulta otimizada
    $sql = "SELECT id, nome, senha, ativo FROM usuarios WHERE ";
    $sql .= $tipo_usuario === 'aluno' ? "rm = ?" : "email = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $identificador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($senha, $usuario['senha'])) {
            if ($usuario['ativo'] == 1) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'tipo' => $tipo_usuario
                ];
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['erro_login'] = "Conta desativada";
            }
        } else {
            $_SESSION['erro_login'] = "Senha incorreta";
        }
    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado";
    }
    
    header("Location: ../login.php");
    exit;

} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    $_SESSION['erro_login'] = "Erro no sistema";
    header("Location: ../login.php");
    exit;
}
?>