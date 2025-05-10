<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Método não permitido']));
}

// Sanitização (usando a função do conexao.php)
$nome = limparInput($_POST['nome'] ?? '');
$email = limparInput($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';
$tipo = limparInput($_POST['tipo'] ?? '');

// Validações básicas
if (empty($nome) || empty($email) || empty($senha) || empty($tipo)) {
    die(json_encode(['status' => 'error', 'message' => 'Preencha todos os campos']));
}

if ($senha !== $confirmar_senha) {
    die(json_encode(['status' => 'error', 'message' => 'As senhas não coincidem']));
}

if (strlen($senha) < 6) {
    die(json_encode(['status' => 'error', 'message' => 'Senha deve ter 6+ caracteres']));
}

// Validações específicas por tipo
$tipos_permitidos = ['aluno', 'professor', 'admin']; // Adicione outros se necessário
if (!in_array($tipo, $tipos_permitidos)) {
    die(json_encode(['status' => 'error', 'message' => 'Tipo de usuário inválido']));
}

// Dados adicionais por tipo
$dados_extras = [];
if ($tipo === 'aluno') {
    $rm = limparInput($_POST['rm'] ?? '');
    $etec = limparInput($_POST['etec'] ?? '');
    
    if (empty($rm) || empty($etec)) {
        die(json_encode(['status' => 'error', 'message' => 'RM e ETEC são obrigatórios']));
    }
    $dados_extras = ['rm' => $rm, 'id_etec' => $etec];
} 
elseif ($tipo === 'professor') {
    $email_institucional = limparInput($_POST['email_institucional'] ?? '');
    if (empty($email_institucional)) {
        die(json_encode(['status' => 'error', 'message' => 'E-mail institucional obrigatório']));
    }
    $dados_extras = ['email_institucional' => $email_institucional];
}

// Verificar se email existe
try {
    $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        die(json_encode(['status' => 'error', 'message' => 'E-mail já cadastrado']));
    }
} catch (Exception $e) {
    die(json_encode(['status' => 'error', 'message' => 'Erro ao verificar e-mail']));
}

// Registrar usuário
try {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $campos = ['nome', 'email', 'senha', 'tipo'];
    $valores = [$nome, $email, $senha_hash, $tipo];
    $tipos = "ssss"; // string para bind_param

    // Adiciona campos extras
    foreach ($dados_extras as $campo => $valor) {
        $campos[] = $campo;
        $valores[] = $valor;
        $tipos .= is_int($valor) ? "i" : "s";
    }

    $query = "INSERT INTO usuarios (" . implode(", ", $campos) . ") 
              VALUES (" . str_repeat("?,", count($campos)-1) . "?)";
    
    $stmt = $conexao->prepare($query);
    $stmt->bind_param($tipos, ...$valores);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Registro realizado! Faça login'
        ]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Erro no registro: ' . $e->getMessage()
    ]));
}
?>