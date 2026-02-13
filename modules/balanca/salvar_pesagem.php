<?php
require '../../config/auth.php';
require '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uuid = $_POST['uuid'];
    $material = $_POST['material'];
    $peso_bruto = $_POST['peso_bruto'];
    $tara = $_POST['tara'];

    // Busca Veículo
    $stmt = $pdo->prepare("SELECT id FROM veiculos WHERE uuid = :uuid");
    $stmt->execute(['uuid' => $uuid]);
    $veiculo = $stmt->fetch();

    if ($veiculo) {
        $sql = "INSERT INTO pesagens (veiculo_id, material, peso_bruto, tara) 
                VALUES (:vid, :mat, :bruto, :tara)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vid' => $veiculo['id'],
            'mat' => $material,
            'bruto' => $peso_bruto,
            'tara' => $tara
        ]);

        echo "<script>alert('Pesagem Registrada!'); window.location='pesagem.php';</script>";
    } else {
        echo "<script>alert('Erro: Veículo não reconhecido.'); window.history.back();</script>";
    }
}
?>