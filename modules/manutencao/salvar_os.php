<?php
require '../../config/auth.php';
require '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uuid = $_POST['uuid'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];

    // Busca ID do Veículo
    $stmt = $pdo->prepare("SELECT id FROM veiculos WHERE uuid = :uuid");
    $stmt->execute(['uuid' => $uuid]);
    $veiculo = $stmt->fetch();

    if ($veiculo) {
        // Atualiza status do veículo para 'Manutencao'
        $pdo->prepare("UPDATE veiculos SET status = 'Manutencao' WHERE id = :id")->execute(['id' => $veiculo['id']]);

        // Cria a OS (Você precisa criar essa tabela no SQL se ainda não criou)
        $sql = "INSERT INTO manutencoes (veiculo_id, tipo, descricao, prioridade, status) 
                VALUES (:vid, :tipo, :desc, :prio, 'Aberta')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['vid'=>$veiculo['id'], 'tipo'=>$tipo, 'desc'=>$descricao, 'prio'=>$prioridade]);

        echo "<script>alert('OS Aberta com Sucesso!'); window.location='ordem_servico.php';</script>";
    } else {
        echo "Erro: Veículo não encontrado.";
    }
}
?>