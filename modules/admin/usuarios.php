<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador']);

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $pdo->prepare("DELETE FROM funcionarios WHERE id = ?")->execute([$id]);
    header("Location: usuarios.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestão de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <h3>Usuários e Colaboradores</h3>
        <a href="../cadastro/funcionarios.php" class="btn btn-primary mb-3">Novo Usuário</a>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = $pdo->query("SELECT * FROM funcionarios ORDER BY cargo");
                foreach ($users as $u) {
                    echo "<tr>
                            <td>{$u['nome']}</td>
                            <td><span class='badge bg-info'>{$u['cargo']}</span></td>
                            <td>{$u['email']}</td>
                            <td>
                                <a href='?excluir={$u['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>