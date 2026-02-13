<?php
require '../config/conexao.php';
header('Content-Type: application/json');

// Query Otimizada para Power BI
$sql = "SELECT 
            v.placa, 
            v.modelo, 
            a.litros, 
            a.horimetro, 
            a.data_registro as data
        FROM abastecimentos a
        JOIN veiculos v ON a.veiculo_id = v.id
        ORDER BY a.data_registro DESC";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>