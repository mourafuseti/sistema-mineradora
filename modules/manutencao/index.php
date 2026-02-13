<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente', 'Mecanico']);

// Busca OS pendentes primeiro
$sql = "SELECT m.*, v.placa, v.modelo 
        FROM manutencoes m 
        JOIN veiculos v ON m.veiculo_id = v.id 
        ORDER BY FIELD(m.status, 'Aberta', 'Em Andamento', 'Concluida'), m.prioridade ASC";
$manutencoes = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestão de Oficina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>🛠️ Oficina Mecânica</h3>
            <div>
                <a href="pecas.php" class="btn btn-outline-secondary">Estoque de Peças</a>
                <a href="ordem_servico.php" class="btn btn-danger">+ Abrir Nova O.S.</a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Veículo</th>
                            <th>Problema</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($manutencoes as $m): ?>
                        <tr class="<?php echo ($m['prioridade'] == 'Alta' && $m['status'] != 'Concluida') ? 'table-danger' : ''; ?>">
                            <td>#<?php echo $m['id']; ?></td>
                            <td>
                                <b><?php echo $m['placa']; ?></b><br>
                                <small><?php echo $m['modelo']; ?></small>
                            </td>
                            <td><?php echo substr($m['descricao'], 0, 50); ?>...</td>
                            <td><?php echo $m['prioridade']; ?></td>
                            <td>
                                <?php 
                                $badge = match($m['status']) {
                                    'Aberta' => 'danger',
                                    'Em Andamento' => 'warning',
                                    'Concluida' => 'success',
                                };
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo $m['status']; ?></span>
                            </td>
                            <td>
                                <a href="editar.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-primary">
                                    🔧 Atender
                                </a>
                                <a href="imprimir.php?id=<?php echo $m['id']; ?>" target="_blank" class="btn btn-sm btn-outline-dark">
                                    🖨️
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