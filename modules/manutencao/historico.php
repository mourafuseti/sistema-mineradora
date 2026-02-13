<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

// Busca as últimas 50 manutenções (mais recentes primeiro)
$sql = "SELECT m.*, v.placa, v.modelo 
        FROM manutencoes m 
        JOIN veiculos v ON m.veiculo_id = v.id 
        ORDER BY m.id DESC LIMIT 50";
$manutencoes = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Histórico de Manutenção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            table { font-size: 10px; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <?php include '../../templates/header.php'; ?>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>🛠️ Histórico da Oficina</h3>
            <div class="no-print">
                <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimir Relatório</button>
                <a href="index.php" class="btn btn-primary">Gerenciar O.S. Abertas</a>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">ID</th>
                            <th>Data/Hora</th>
                            <th>Veículo</th>
                            <th>Problema / Solução</th>
                            <th>Status</th>
                            <th class="no-print text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($manutencoes as $m): ?>
                        <tr>
                            <td>#<?php echo $m['id']; ?></td>
                            <td>
                                <b>Abertura:</b> <?php echo date('d/m/y H:i', strtotime($m['data_abertura'])); ?><br>
                                <small><b>Fim:</b> <?php echo $m['data_conclusao'] ? date('d/m/y H:i', strtotime($m['data_conclusao'])) : '-'; ?></small>
                            </td>
                            <td>
                                <b><?php echo $m['placa']; ?></b><br>
                                <small class="text-muted"><?php echo $m['modelo']; ?></small>
                            </td>
                            <td>
                                <span class="text-danger">Prob:</span> <?php echo substr($m['descricao'], 0, 40); ?>...<br>
                                <?php if($m['solucao']): ?>
                                    <span class="text-success">Sol:</span> <small><?php echo substr($m['solucao'], 0, 40); ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $cor = match($m['status']) {
                                    'Aberta' => 'danger',
                                    'Em Andamento' => 'warning',
                                    'Concluida' => 'success',
                                };
                                ?>
                                <span class="badge bg-<?php echo $cor; ?>"><?php echo $m['status']; ?></span>
                            </td>
                            <td class="no-print text-center">
                                <a href="imprimir.php?id=<?php echo $m['id']; ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-dark" 
                                   title="Ver Ficha Completa">
                                   📄 Ver
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