<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /sistema-mineradora/index.php");
    exit;
}

// Função para checar permissão na página
function checkPermissao($cargos_permitidos) {
    if (!in_array($_SESSION['cargo'], $cargos_permitidos)) {
        echo "<div class='alert alert-danger'>Acesso Negado: Seu cargo ({$_SESSION['cargo']}) não tem permissão aqui.</div>";
        exit;
    }
}

// --- NOVA FUNÇÃO DE LOG ---
function registrarLog($pdo, $acao) {
    try {
        $id_usuario = $_SESSION['usuario_id'] ?? null;
        $sql = "INSERT INTO logs_sistema (usuario_id, acao) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario, $acao]);
    } catch (Exception $e) {
        // Silencioso: Se o log falhar, não trava o sistema
    }
}
?>