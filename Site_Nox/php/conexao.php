<?php
$host = 'localhost';
$dbname = 'banco_nox';
$username = 'root';
$password = '';

// Conecta ao banco e define a variável $conn
try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    error_log("Erro de banco: " . $e->getMessage());
    die("Erro ao conectar. Tente mais tarde.");
}

// Função para limpar inputs
function limparInput($dado) {
    return htmlspecialchars(trim($dado), ENT_QUOTES, 'UTF-8');
}
?>
