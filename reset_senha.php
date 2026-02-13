<?php
// Arquivo: reset_senha.php
require 'config/conexao.php';

// 1. Define a nova senha
$nova_senha = '123456';

// 2. Criptografa ela usando o padrão do SEU servidor
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

try {
    // 3. Atualiza o usuário Admin no banco
    $sql = "UPDATE funcionarios 
            SET senha_hash = :senha 
            WHERE email = 'admin@mineradora.com'";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['senha' => $senha_hash]);

    echo "<h1>Sucesso!</h1>";
    echo "<p>A senha do admin@mineradora.com foi redefinida para: <strong>123456</strong></p>";
    echo "<p>O hash gerado foi: $senha_hash</p>";
    echo "<br><a href='index.php'>Clique aqui para fazer Login</a>";

} catch (PDOException $e) {
    echo "Erro ao atualizar: " . $e->getMessage();
}
?>