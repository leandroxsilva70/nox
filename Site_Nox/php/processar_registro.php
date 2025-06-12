<?php
session_start();
require_once _DIR_ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Coletar dados
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
        $senha = $_POST['senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        
        // Validações básicas
        if ($senha !== $confirmar_senha) {
            throw new Exception('As senhas não coincidem!');
        }
        
        // Preparar query baseada no tipo de usuário
        if ($tipo === 'aluno') {
            $rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
            $id_etec = filter_input(INPUT_POST, 'etec', FILTER_VALIDATE_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            
            if (!$rm || !$id_etec || !$email) {
                throw new Exception('Dados do aluno incompletos!');
            }
            
            $stmt = $conexao->prepare("INSERT INTO usuarios 
                (nome, email, senha, tipo, rm, id_etec, ativo) 
                VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([
                $nome, 
                $email, 
                password_hash($senha, PASSWORD_BCRYPT), 
                $tipo, 
                $rm, 
                $id_etec
            ]);
            
        } elseif ($tipo === 'professor') {
            $email_institucional = filter_input(INPUT_POST, 'email_institucional', FILTER_VALIDATE_EMAIL);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $id_etec = filter_input(INPUT_POST, 'etec', FILTER_VALIDATE_INT);
            
            if (!$email_institucional || !$email || !$id_etec) {
                throw new Exception('Dados do professor incompletos!');
            }
            
            $stmt = $conexao->prepare("INSERT INTO usuarios 
                (nome, email, senha, tipo, email_institucional, id_etec, ativo) 
                VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([
                $nome, 
                $email, 
                password_hash($senha, PASSWORD_BCRYPT), 
                $tipo, 
                $email_institucional, 
                $id_etec
            ]);
        } else {
            throw new Exception('Tipo de usuário inválido!');
        }
        
        $_SESSION['sucesso'] = 'Registro realizado com sucesso!';
        header('Location: login.php');
        exit();
        
    } catch (Exception $e) {
        $_SESSION['erro_registro'] = $e->getMessage();
        header('Location: registro.php');
        exit();
    }
}