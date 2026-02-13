<?php
require '../../config/auth.php';
require '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aqui você salvaria em uma tabela 'checklists' (precisa criar no banco)
    echo "<script>alert('Checklist salvo com sucesso!'); window.location='../admin/dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checklist Diário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <h3>Checklist de Pré-Uso</h3>
        <form method="POST" class="card p-4">
            <div class="mb-3">
                <label>Placa do Veículo</label>
                <input type="text" name="placa" class="form-control" placeholder="Digite a placa ou leia QR" required>
            </div>
            
            <h5>Itens de Segurança</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="oleo" id="oleo">
                <label class="form-check-label" for="oleo">Nível de Óleo do Motor</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="pneus" id="pneus">
                <label class="form-check-label" for="pneus">Calibragem dos Pneus</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="freios" id="freios">
                <label class="form-check-label" for="freios">Teste de Freios</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="luzes" id="luzes">
                <label class="form-check-label" for="luzes">Faróis e Sinalização</label>
            </div>
            
            <div class="mb-3 mt-3">
                <label>Observações</label>
                <textarea name="obs" class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar Checklist</button>
        </form>
    </div>
</body>
</html>