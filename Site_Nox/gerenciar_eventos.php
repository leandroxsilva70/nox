<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Método não permitido']));
}

// Verifica se é aluno
if ($_SESSION['tipo'] !== 'aluno') {
    die(json_encode(['status' => 'error', 'message' => 'Apenas alunos podem se inscrever']));
}

$evento_id = intval($_POST['evento_id'] ?? 0);

try {
    // Verifica se já está inscrito
    $stmt = $conexao->prepare("SELECT id FROM inscricoes 
                              WHERE evento_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $evento_id, $_SESSION['usuario_id']);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        die(json_encode(['status' => 'error', 'message' => 'Você já está inscrito neste evento']));
    }

    // Faz a inscrição
    $stmt = $conexao->prepare("INSERT INTO inscricoes (evento_id, usuario_id, data_inscricao) 
                              VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $evento_id, $_SESSION['usuario_id']);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Inscrição realizada com sucesso!'
    ]);

} catch (Exception $e) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Erro ao processar inscrição: ' . $e->getMessage()
    ]));
}
?>