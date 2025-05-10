<?php
session_start();
require 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'aluno') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso não autorizado']);
    exit;
}

$evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);

if (!$evento_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID de evento inválido']);
    exit;
}

// Verifica se o evento existe e está ativo
$evento = $conexao->query("SELECT id FROM eventos WHERE id = $evento_id AND status = 'ativo'");
if ($evento->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Evento não encontrado ou inativo']);
    exit;
}

// Verifica se já está inscrito
$query = $conexao->prepare("SELECT id FROM inscricoes_eventos WHERE evento_id = ? AND usuario_id = ?");
$query->bind_param("ii", $evento_id, $_SESSION['usuario_id']);
$query->execute();

if ($query->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Você já está inscrito neste evento']);
    exit;
}

// Faz a inscrição
$insert = $conexao->prepare("INSERT INTO inscricoes_eventos (evento_id, usuario_id) VALUES (?, ?)");
$insert->bind_param("ii", $evento_id, $_SESSION['usuario_id']);

if ($insert->execute()) {
    // Registra o log de acesso
    $ip = $_SERVER['REMOTE_ADDR'];
    $conexao->query("INSERT INTO log_acessos (usuario_id, acao, ip) VALUES ({$_SESSION['usuario_id']}, 'Inscrição no evento $evento_id', '$ip')");
    
    echo json_encode(['status' => 'success', 'message' => 'Inscrição realizada com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao realizar inscrição: ' . $conexao->error]);
}