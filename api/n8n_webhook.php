<?php
// Este arquivo recebe um POST JSON do n8n
header('Content-Type: application/json');
require '../config/conexao.php';

// Recebe o corpo da requisição
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Exemplo: n8n manda {"placa": "ABC-1234", "latitude": -20.00, "longitude": -44.00}
if (isset($data['placa']) && isset($data['latitude'])) {
    
    // Atualiza a posição do veículo no banco
    $sql = "UPDATE veiculos SET latitude = :lat, longitude = :lon WHERE placa = :placa";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'lat' => $data['latitude'],
        'lon' => $data['longitude'],
        'placa' => $data['placa']
    ]);

    echo json_encode(["status" => "sucesso", "mensagem" => "Localização atualizada"]);
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos"]);
}
?>