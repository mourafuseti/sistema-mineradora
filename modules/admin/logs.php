<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador']);

// Busca logs
$sql = "SELECT l.*, f.nome as usuario 
        FROM logs_sistema l 
        LEFT JOIN funcionarios f ON l.usuario_id = f.id 
        ORDER BY l.data_hora DESC LIMIT 100";
$logs = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logs de Auditoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <h3>🛡️ Logs do Sistema</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Data/Hora</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($logs as $log): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($log['data_hora'])); ?></td>
                    <td><?php echo $log['usuario'] ?? 'Sistema'; ?></td>
                    <td><?php echo $log['acao']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>