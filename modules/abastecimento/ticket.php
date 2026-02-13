<?php
require '../../config/auth.php';
require '../../config/conexao.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT a.*, v.placa, v.modelo 
        FROM abastecimentos a 
        JOIN veiculos v ON a.veiculo_id = v.id 
        WHERE a.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$dado = $stmt->fetch();

if(!$dado) die("Ticket não encontrado.");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticket #<?php echo $id; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; width: 300px; font-size: 14px; margin: 0; padding: 10px; }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .titulo { font-weight: bold; font-size: 16px; }
        .destaque { font-size: 18px; font-weight: bold; margin: 10px 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    
    <div class="center">
        <div class="titulo">MINERADORA XYZ</div>
        <small>Comprovante de Abastecimento</small>
    </div>
    
    <div class="line"></div>
    
    <div><b>Data:</b> <?php echo date('d/m/Y H:i', strtotime($dado['data_registro'])); ?></div>
    <div><b>Ticket:</b> #<?php echo str_pad($dado['id'], 6, '0', STR_PAD_LEFT); ?></div>
    
    <div class="line"></div>
    
    <div><b>Veículo:</b> <?php echo $dado['placa']; ?></div>
    <div><b>Modelo:</b> <?php echo $dado['modelo']; ?></div>
    
    <div class="line"></div>
    
    <div><b>Produto:</b> <?php echo strtoupper($dado['tipo_combustivel']); ?></div>
    <div class="destaque">
        VOLUME: <?php echo number_format($dado['litros'], 2, ',', '.'); ?> L
    </div>
    <div>Horímetro: <?php echo $dado['horimetro']; ?></div>
    
    <div class="line"></div>
    
    <div class="center">
        <br><br>
        _____________________________<br>
        Assinatura
    </div>

    <button class="no-print" onclick="window.print()" style="width:100%; padding:10px; margin-top:20px;">🖨️ Reimprimir</button>
</body>
</html>