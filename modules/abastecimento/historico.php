<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

// Busca abastecimentos com detalhes do veículo
$sql = "SELECT a.*, v.placa, v.modelo 
        FROM abastecimentos a 
        JOIN veiculos v ON a.veiculo_id = v.id 
        ORDER BY a.data_registro DESC LIMIT 100";
$abastecimentos = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Histórico de Combustível</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            table { width: 100%; border: 1px solid #000; font-size: 12px; }
            th, td { border: 1px solid #000; padding: 5px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <?php include '../../templates/header.php'; ?>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>⛽ Relatório de Combustível</h3>
            <div class="no-print">
                <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimir Lista</button>
                <a href="index.php" class="btn btn-primary">Novo Lançamento</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Data</th>
                            <th>Veículo</th>
                            <th>Tipo</th> <th>Litros</th>
                            <th>Horímetro</th>
                            <th class="no-print text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($abastecimentos as $a): ?>
                        <tr>
                            <td><?php echo date('d/m/y H:i', strtotime($a['data_registro'])); ?></td>
                            <td>
                                <b><?php echo $a['placa']; ?></b><br>
                                <small class="text-muted"><?php echo $a['modelo']; ?></small>
                            </td>
                            <td>
                                <?php 
                                // Cores dos Combustíveis
                                $badge = match($a['tipo_combustivel']) {
                                    'Diesel' => 'dark',     // Preto
                                    'Gasolina' => 'primary', // Azul
                                    'Alcool' => 'success',   // Verde
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>">
                                    <?php echo $a['tipo_combustivel']; ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo number_format($a['litros'], 2, ',', '.'); ?> L</strong>
                            </td>
                            <td><?php echo $a['horimetro']; ?></td>
                            <td class="no-print text-center">
                                <a href="ticket.php?id=<?php echo $a['id']; ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-dark">
                                   🧾 Ticket
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