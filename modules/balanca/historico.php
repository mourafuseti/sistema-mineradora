<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente', 'Balanca']);

// Busca as últimas 50 pesagens
$sql = "SELECT p.*, v.placa, v.modelo 
        FROM pesagens p 
        JOIN veiculos v ON p.veiculo_id = v.id 
        ORDER BY p.data_pesagem DESC LIMIT 50";
$pesagens = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Histórico de Balança</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>⚖️ Histórico de Pesagem</h3>
            <a href="pesagem.php" class="btn btn-warning">Nova Pesagem</a>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Ticket</th>
                            <th>Data/Hora</th>
                            <th>Veículo</th>
                            <th>Produto</th>
                            <th>Peso Líquido</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pesagens as $p): ?>
                        <tr>
                            <td>#<?php echo str_pad($p['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($p['data_pesagem'])); ?></td>
                            <td>
                                <b><?php echo $p['placa']; ?></b><br>
                                <small class="text-muted"><?php echo $p['modelo']; ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo $p['material']; ?></span></td>
                            <td>
                                <b><?php echo number_format($p['peso_bruto'] - $p['tara'], 0, ',', '.'); ?> Kg</b>
                            </td>
                            <td>
                                <a href="ticket.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm btn-outline-dark" title="Reimprimir Ticket">
                                    🖨️ Ticket
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>