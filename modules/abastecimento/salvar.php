<?php
require '../../config/auth.php';
require '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uuid = $_POST['uuid'];
    $tipo = $_POST['tipo_combustivel']; // Captura Gasolina/Alcool/Diesel
    $litros = $_POST['litros'];
    $horimetro = $_POST['horimetro'];

    // 1. Busca Veículo pelo UUID
    $stmt = $pdo->prepare("SELECT id, placa, modelo FROM veiculos WHERE uuid = :uuid");
    $stmt->execute(['uuid' => $uuid]);
    $veiculo = $stmt->fetch();

    if ($veiculo) {
        try {
            // 2. Insere no Banco
            $sql = "INSERT INTO abastecimentos (veiculo_id, tipo_combustivel, litros, horimetro) 
                    VALUES (:vid, :tipo, :litros, :hor)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'vid' => $veiculo['id'],
                'tipo' => $tipo,
                'litros' => $litros,
                'hor' => $horimetro
            ]);

            // 3. Registra Log (Se a função existir no auth.php)
            if(function_exists('registrarLog')) {
                registrarLog($pdo, "Abasteceu {$veiculo['placa']}: $litros L de $tipo");
            }

            echo "<script>alert('Sucesso! Abastecimento registrado.'); window.location='index.php';</script>";
        
        } catch(PDOException $e) {
            echo "<script>alert('Erro no banco: " . $e->getMessage() . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Erro: Veículo não encontrado com este código.'); window.history.back();</script>";
    }
}
?>