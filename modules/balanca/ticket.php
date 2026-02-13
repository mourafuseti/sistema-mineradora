<?php
require '../../config/auth.php';
require '../../config/conexao.php';
// Sem checkPermissao estrito para facilitar impressão, ou apenas Balança

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT p.*, v.placa, v.modelo FROM pesagens p JOIN veiculos v ON p.veiculo_id = v.id WHERE p.id = ?");
$stmt->execute([$id]);
$dado = $stmt->fetch();

if(!$dado) die("Ticket não encontrado.");
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Courier New', monospace; width: 300px; font-size: 12px; }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h3>MINERADORA XYZ</h3>
        <p>Comprovante de Pesagem</p>
    </div>
    <div class="line"></div>
    <p><b>Ticket:</b> #<?php echo str_pad($dado['id'], 6, '0', STR_PAD_LEFT); ?></p>
    <p><b>Data:</b> <?php echo date('d/m/Y H:i', strtotime($dado['data_pesagem'])); ?></p>
    <div class="line"></div>
    <p><b>Veículo:</b> <?php echo $dado['placa']; ?></p>
    <p><b>Produto:</b> <?php echo $dado['material']; ?></p>
    <div class="line"></div>
    <p>Peso Bruto: <?php echo number_format($dado['peso_bruto'], 0); ?> Kg</p>
    <p>Tara:       <?php echo number_format($dado['tara'], 0); ?> Kg</p>
    <p><b>Líquido:    <?php echo number_format($dado['peso_liquido'], 0); ?> Kg</b></p>
    <div class="line"></div>
    <br><br>
    <div class="center">_______________________<br>Assinatura Balanceiro</div>
    
    <button class="no-print" onclick="window.print()">Imprimir</button>
</body>
</html>