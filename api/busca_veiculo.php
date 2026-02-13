<?php
require '../config/conexao.php';
header('Content-Type: application/json');

$uuid = $_GET['uuid'] ?? '';

if ($uuid) {
    // Busca veículo e sua tara (se já tiver sido cadastrada em algum lugar)
    // Aqui assumimos que a tara pode ser um campo fixo no cadastro do veículo
    $stmt = $pdo->prepare("SELECT id, placa, modelo, tipo FROM veiculos WHERE uuid = :uuid");
    $stmt->execute(['uuid' => $uuid]);
    $veiculo = $stmt->fetch();
    
    if ($veiculo) {
        echo json_encode(['status' => 'sucesso', 'dados' => $veiculo]);
    } else {
        echo json_encode(['status' => 'erro', 'msg' => 'Veículo não encontrado']);
    }
}
?>