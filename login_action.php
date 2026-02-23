<?php
session_start();
require 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha_hash'])) {
        
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['cargo'] = $user['cargo'];

        // Grava o Log de Entrada
        try {
            if(function_exists('registrarLog')) {
                registrarLog($pdo, "Fez login no portal");
            } else {
                $agora = date('Y-m-d H:i:s');
                $pdo->prepare("INSERT INTO logs_sistema (usuario_id, acao, data_hora) VALUES (?, ?, ?)")->execute([$user['id'], "Fez login no portal", $agora]);
            }
        } catch (Exception $e) {}

        // 🚀 REDIRECIONAMENTO INTELIGENTE POR CARGO
        if ($user['cargo'] == 'Frentista') {
            header("Location: modules/abastecimento/index.php");
        } elseif ($user['cargo'] == 'Balanca') {
            header("Location: modules/balanca/pesagem.php");
        } elseif ($user['cargo'] == 'Mecanico') {
            header("Location: modules/manutencao/index.php");
        } else {
            // Administrador e Gerente vão para o Painel Completo
            header("Location: modules/admin/dashboard.php");
        }
        exit;
        
    } else {
        $_SESSION['erro_login'] = "Credenciais inválidas. Verifique seu e-mail e senha.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>