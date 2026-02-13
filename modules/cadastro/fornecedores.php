<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo']; // Oficina, Posto, Peças
    $contato = $_POST['contato'];

    $sql = "INSERT INTO fornecedores (nome, tipo, contato) VALUES (?, ?, ?)";
    $pdo->prepare($sql)->execute([$nome, $tipo, $contato]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fornecedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <h3>Fornecedores e Parceiros</h3>
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="nome" class="form-control" placeholder="Nome da Empresa" required>
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="Oficina">Oficina Mecânica</option>
                    <option value="Posto">Posto de Combustível</option>
                    <option value="Pecas">Vendedor de Peças</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="contato" class="form-control" placeholder="Telefone/Email">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
    </div>
</body>
</html>