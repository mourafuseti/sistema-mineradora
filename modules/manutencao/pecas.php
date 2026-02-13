<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Mecanico', 'Gerente']);

$msg = "";
$erro = "";
$peca_em_edicao = null;

// 1. Excluir
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $uso = $pdo->query("SELECT count(*) FROM manutencao_pecas WHERE peca_id = $id")->fetchColumn();
    
    if ($uso > 0) {
        $erro = "Não é possível excluir: Esta peça já foi usada em $uso Ordens de Serviço.";
    } else {
        $pdo->query("DELETE FROM pecas WHERE id = $id");
        $msg = "Peça removida com sucesso!";
    }
}

// 2. Preparar Edição
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM pecas WHERE id = ?");
    $stmt->execute([$id]);
    $peca_em_edicao = $stmt->fetch();
}

// 3. Salvar (Inserir ou Atualizar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['codigo']; // Novo campo
    $nome = $_POST['nome'];
    $qtd = $_POST['qtd'];
    $minimo = $_POST['minimo'];
    $id_atual = $_POST['id_atual'];

    try {
        if ($id_atual) {
            // ATUALIZAR
            $sql = "UPDATE pecas SET codigo = ?, nome = ?, quantidade = ?, minimo = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$codigo, $nome, $qtd, $minimo, $id_atual]);
            $msg = "Peça atualizada com sucesso!";
        } else {
            // CADASTRAR NOVO
            $sql = "INSERT INTO pecas (codigo, nome, quantidade, minimo) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$codigo, $nome, $qtd, $minimo]);
            $msg = "Peça cadastrada!";
        }
        $peca_em_edicao = null; // Limpa formulário
    } catch (PDOException $e) {
        $erro = "Erro ao salvar: " . $e->getMessage();
    }
}

// Busca lista atualizada
$pecas = $pdo->query("SELECT * FROM pecas ORDER BY nome");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Estoque de Peças</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>📦 Estoque da Oficina</h3>
            <a href="index.php" class="btn btn-secondary">Voltar para O.S.</a>
        </div>

        <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <?php if($erro) echo "<div class='alert alert-danger'>$erro</div>"; ?>

        <div class="card p-3 mb-4 shadow-sm border-<?php echo $peca_em_edicao ? 'warning' : 'primary'; ?>">
            <h5 class="card-title text-<?php echo $peca_em_edicao ? 'warning' : 'primary'; ?>">
                <?php echo $peca_em_edicao ? '✏️ Editando Peça' : '➕ Nova Peça'; ?>
            </h5>
            
            <form method="POST" class="row g-3">
                <input type="hidden" name="id_atual" value="<?php echo $peca_em_edicao['id'] ?? ''; ?>">

                <div class="col-md-3">
                    <label>Código (Part Number)</label>
                    <input type="text" name="codigo" class="form-control" placeholder="Ex: 1R-0716" 
                           value="<?php echo $peca_em_edicao['codigo'] ?? ''; ?>" required>
                </div>

                <div class="col-md-4">
                    <label>Nome da Peça</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Filtro de Óleo" 
                           value="<?php echo $peca_em_edicao['nome'] ?? ''; ?>" required>
                </div>
                
                <div class="col-md-2">
                    <label>Qtd Estoque</label>
                    <input type="number" name="qtd" class="form-control" placeholder="0" 
                           value="<?php echo $peca_em_edicao['quantidade'] ?? ''; ?>" required>
                </div>
                
                <div class="col-md-2">
                    <label>Mínimo</label>
                    <input type="number" name="minimo" class="form-control" placeholder="5" 
                           value="<?php echo $peca_em_edicao['minimo'] ?? '5'; ?>" required>
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-<?php echo $peca_em_edicao ? 'warning' : 'success'; ?> w-100">
                        <i class="bi bi-check-lg"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nome da Peça</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pecas as $p): ?>
                        <tr>
                            <td><code><?php echo $p['codigo']; ?></code></td>
                            <td><?php echo $p['nome']; ?></td>
                            <td>
                                <strong><?php echo $p['quantidade']; ?></strong>
                            </td>
                            <td>
                                <?php if($p['quantidade'] <= $p['minimo']): ?>
                                    <span class="badge bg-danger">Baixo</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Ok</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="pecas.php?editar=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning text-dark">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="pecas.php?excluir=<?php echo $p['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Excluir peça: <?php echo $p['nome']; ?>?');">
                                    <i class="bi bi-trash"></i>
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