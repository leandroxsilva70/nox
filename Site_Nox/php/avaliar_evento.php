<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado.";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$evento_id = $_POST['evento_id'];
$nota = $_POST['nota'];

if ($nota < 1 || $nota > 5) {
    echo "Nota inválida.";
    exit();
}

// Verifica se já avaliou
$verifica = $conexao->prepare("SELECT id FROM avaliacoes_eventos WHERE evento_id = ? AND usuario_id = ?");
$verifica->bind_param("ii", $evento_id, $usuario_id);
$verifica->execute();
$verifica->store_result();

if ($verifica->num_rows > 0) {
    $atualiza = $conexao->prepare("UPDATE avaliacoes_eventos SET nota = ? WHERE evento_id = ? AND usuario_id = ?");
    $atualiza->bind_param("iii", $nota, $evento_id, $usuario_id);
    $atualiza->execute();
    echo "Avaliação atualizada!";
} else {
    $inserir = $conexao->prepare("INSERT INTO avaliacoes_eventos (evento_id, usuario_id, nota) VALUES (?, ?, ?)");
    $inserir->bind_param("iii", $evento_id, $usuario_id, $nota);
    $inserir->execute();
    echo "Avaliação registrada!";
}
