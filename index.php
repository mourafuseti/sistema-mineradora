<?php
session_start();
// Se já estiver logado, redireciona para o dashboard correto
if (isset($_SESSION['usuario_id'])) {
    header("Location: modules/admin/dashboard.php"); // Redireciona para Admin por padrão
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Mineradora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #2c3e50; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { width: 400px; padding: 30px; border-radius: 15px; }
    </style>
</head>
<body>
    <div class="card card-login bg-white shadow-lg">
        <h3 class="text-center mb-4">Acesso Seguro</h3>
        <form action="login_action.php" method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>