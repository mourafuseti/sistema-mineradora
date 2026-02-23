<?php
session_start();

// Grava o Log de Saída ANTES de destruir a sessão
if (isset($_SESSION['usuario_id'])) {
    require 'config/conexao.php';
    
    try {
        $id_usuario = $_SESSION['usuario_id'];
        $agora = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO logs_sistema (usuario_id, acao, data_hora) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario, "Fez logout (saiu do sistema)", $agora]);
    } catch (Exception $e) {
        // Silencioso
    }
}

// Limpa as variáveis e destrói a sessão
$_SESSION = array();
session_destroy();

// Redireciona para a tela de login corporativa
header("Location: index.php");
exit;
?>