<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente', 'Mecanico']);

$id = $_GET['id'];
$msg = "";
$erro = "";

// --- LÓGICA 1: ADICIONAR PEÇA ---
if (isset($_POST['add_peca'])) {
    $peca_id = $_POST['peca_id'];
    $qtd_uso = $_POST['qtd_uso'];

    // Verifica Estoque
    $peca = $pdo->query("SELECT nome, quantidade FROM pecas WHERE id = $peca_id")->fetch();

    if ($peca['quantidade'] >= $qtd_uso) {
        try {
            $pdo->beginTransaction();

            // Insere uso
            $stmt = $pdo->prepare("INSERT INTO manutencao_pecas (manutencao_id, peca_id, quantidade) VALUES (?, ?, ?)");
            $stmt->execute([$id, $peca_id, $qtd_uso]);

            // Baixa estoque
            $stmt = $pdo->prepare("UPDATE pecas SET quantidade = quantidade - ? WHERE id = ?");
            $stmt->execute([$qtd_uso, $peca_id]);

            $pdo->commit();
            
            // LOG AUTOMÁTICO
            registrarLog($pdo, "Adicionou $qtd_uso x {$peca['nome']} na O.S. #$id");
            
            $msg = "Peça adicionada!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro: " . $e->getMessage();
        }
    } else {
        $erro = "Estoque insuficiente (Disp: {$peca['quantidade']})";
    }
}

// --- LÓGICA 2: ATUALIZAR STATUS ---
if (isset($_POST['update_os'])) {
    $status = $_POST['status'];
    $solucao = $_POST['solucao'];
    $data_fim = ($status == 'Concluida') ? date('Y-m-d H:i:s') : null;

    $sql = "UPDATE manutencoes SET status = ?, solucao = ?, data_conclusao = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $solucao, $data_fim, $id]);
    
    // Libera veículo se concluído
    if($status == 'Concluida') {
        $v_id = $pdo->query("SELECT veiculo_id FROM manutencoes WHERE id = $id")->fetchColumn();
        $pdo->query("UPDATE veiculos SET status = 'Ativo' WHERE id = $v_id");
        // LOG DE CONCLUSÃO
        registrarLog($pdo, "Concluiu a O.S. #$id");
    } else {
        // LOG DE ATUALIZAÇÃO
        registrarLog($pdo, "Atualizou status da O.S. #$id para $status");
    }

    header("Location: editar.php?id=$id&sucesso=1");
    exit;
}

// --- BUSCAS ---
$os = $pdo->query("SELECT m.*, v.placa, v.modelo FROM manutencoes m JOIN veiculos v ON m.veiculo_id = v.id WHERE m.id = $id")->fetch();
$pecas_disponiveis = $pdo->query("SELECT * FROM pecas WHERE quantidade > 0 ORDER BY nome");
$pecas_usadas = $pdo->query("SELECT mp.*, p.nome, p.codigo FROM manutencao_pecas mp JOIN pecas p ON mp.peca_id = p.id WHERE mp.manutencao_id = $id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Atender O.S.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>🔧 O.S. #<?php echo $os['id']; ?> - <?php echo $os['placa']; ?></h3>
            <a href="index.php" class="btn btn-outline-secondary">Voltar</a>
        </div>

        <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <?php if($erro) echo "<div class='alert alert-danger'>$erro</div>"; ?>
        <?php if(isset($_GET['sucesso'])) echo "<div class='alert alert-success'>Atualizado com sucesso!</div>"; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">Serviço</div>
                    <div class="card-body">
                        <p><b>Problema:</b> <?php echo $os['descricao']; ?></p>
                        <form method="POST">
                            <input type="hidden" name="update_os" value="1">
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Aberta" <?php echo $os['status']=='Aberta'?'selected':''; ?>>Aberta</option>
                                    <option value="Em Andamento" <?php echo $os['status']=='Em Andamento'?'selected':''; ?>>Em Andamento</option>
                                    <option value="Concluida" <?php echo $os['status']=='Concluida'?'selected':''; ?>>Concluída</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Solução Técnica</label>
                                <textarea name="solucao" class="form-control" rows="5"><?php echo $os['solucao'] ?? ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">💾 Salvar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-secondary text-white">Peças Utilizadas</div>
                    <div class="card-body">
                        <form method="POST" class="row g-2 align-items-end mb-4 border-bottom pb-3">
                            <input type="hidden" name="add_peca" value="1">
                            <div class="col-md-7">
                                <select name="peca_id" class="form-select" required>
                                    <option value="">-- Selecione --</option>
                                    <?php foreach($pecas_disponiveis as $p): ?>
                                        <option value="<?php echo $p['id']; ?>">
                                            <?php echo $p['nome']; ?> (Est: <?php echo $p['quantidade']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="qtd_uso" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning w-100">➕</button>
                            </div>
                        </form>

                        <table class="table table-sm table-striped">
                            <thead><tr><th>Cód</th><th>Peça</th><th>Qtd</th></tr></thead>
                            <tbody>
                                <?php foreach($pecas_usadas as $pu): ?>
                                <tr>
                                    <td><small><?php echo $pu['codigo']; ?></small></td>
                                    <td><?php echo $pu['nome']; ?></td>
                                    <td><?php echo $pu['quantidade']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>