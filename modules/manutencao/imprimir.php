<?php
require '../../config/auth.php';
require '../../config/conexao.php';

$id = $_GET['id'];

$sql_os = "SELECT m.*, v.placa, v.modelo, v.tipo FROM manutencoes m JOIN veiculos v ON m.veiculo_id = v.id WHERE m.id = $id";
$os = $pdo->query($sql_os)->fetch();

$sql_pecas = "SELECT mp.quantidade, p.nome, p.codigo FROM manutencao_pecas mp JOIN pecas p ON mp.peca_id = p.id WHERE mp.manutencao_id = $id";
$pecas = $pdo->query($sql_pecas)->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>O.S. #<?php echo $id; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .box { border: 1px solid #000; padding: 10px; margin-bottom: 10px; }
        .box-title { font-weight: bold; background: #eee; border-bottom: 1px solid #000; margin: -10px -10px 10px -10px; padding: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <button onclick="window.print()" style="padding:10px; width:100%; margin-bottom:20px;">🖨️ IMPRIMIR</button>
    
    <h2 style="text-align:center">ORDEM DE SERVIÇO</h2>
    
    <div class="box">
        <div class="box-title">DADOS</div>
        <b>Nº:</b> <?php echo str_pad($os['id'], 6, '0', STR_PAD_LEFT); ?> | 
        <b>Data:</b> <?php echo date('d/m/Y H:i', strtotime($os['data_abertura'])); ?> | 
        <b>Status:</b> <?php echo strtoupper($os['status']); ?>
    </div>

    <div class="box">
        <div class="box-title">VEÍCULO</div>
        <?php echo $os['placa']; ?> - <?php echo $os['modelo']; ?> (<?php echo $os['tipo']; ?>)
    </div>

    <div class="box">
        <div class="box-title">PROBLEMA</div>
        <?php echo nl2br($os['descricao']); ?>
    </div>

    <div class="box">
        <div class="box-title">PEÇAS UTILIZADAS</div>
        <?php if(count($pecas) > 0): ?>
            <table>
                <tr><th>Código</th><th>Peça</th><th>Qtd</th></tr>
                <?php foreach($pecas as $p): ?>
                <tr>
                    <td><?php echo $p['codigo']; ?></td>
                    <td><?php echo $p['nome']; ?></td>
                    <td><?php echo $p['quantidade']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <i>Nenhuma peça registrada.</i>
        <?php endif; ?>
    </div>

    <div class="box">
        <div class="box-title">SOLUÇÃO TÉCNICA</div>
        <?php echo nl2br($os['solucao']); ?>
        <br><br><br>
    </div>

    <div style="margin-top:50px; text-align:center; display:flex; justify-content:space-between;">
        <div style="border-top:1px solid #000; width:40%">Mecânico</div>
        <div style="border-top:1px solid #000; width:40%">Supervisor</div>
    </div>
</body>
</html>