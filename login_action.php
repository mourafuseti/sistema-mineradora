<?php
session_start();
require 'config/conexao.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'];

// Busca usuário pelo email
$stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['senha_hash'])) {
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];
    $_SESSION['cargo'] = $user['cargo']; // Importante para o Auth

    // Redirecionamento baseado no Cargo
    if ($user['cargo'] == 'Administrador' || $user['cargo'] == 'Gerente') {
        header("Location: modules/admin/dashboard.php");
    } elseif ($user['cargo'] == 'Balanca') {
        header("Location: modules/balanca/pesagem.php");
    } else {
        echo "Seu perfil não tem acesso ao sistema web. Use o App/QR Code.";
    }
} else {
    echo "<script>alert('Login inválido!'); window.location='index.php';</script>";
}
?>