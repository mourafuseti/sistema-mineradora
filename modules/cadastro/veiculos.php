<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

// Salvar novo veículo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $placa = strtoupper($_POST['placa']);
    $modelo = $_POST['modelo'];
    $uuid = uniqid('V-'); 

    $stmt = $pdo->prepare("INSERT INTO veiculos (uuid, placa, modelo) VALUES (:uuid, :placa, :modelo)");
    $stmt->execute(['uuid' => $uuid, 'placa' => $placa, 'modelo' => $modelo]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frota e QR Codes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function imprimirQR(url, placa) {
            var win = window.open('', '', 'height=500,width=500');
            win.document.write('<html><head><title>Imprimir QR</title></head>');
            win.document.write('<body style="text-align:center;">');
            win.document.write('<h1>' + placa + '</h1>');
            win.document.write('<img src="' + url + '" style="width:300px;">');
            win.document.write('<br><br><button onclick="window.print()">IMPRIMIR</button>');
            win.document.write('</body></html>');
            win.document.close();
        }
    </script>
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Novo Veículo</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Placa</label>
                            <input type="text" name="placa" class="form-control" placeholder="ABC-1234" required>
                        </div>
                        <div class="mb-3">
                            <label>Modelo</label>
                            <input type="text" name="modelo" class="form-control" placeholder="Ex: Scania R450" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <h4>Frota Cadastrada</h4>
                <table class="table table-bordered table-striped">
                    <thead><tr><th>Veículo</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php
                        $veiculos = $pdo->query("SELECT * FROM veiculos ORDER BY id DESC");
                        foreach ($veiculos as $v) {
                            // Link do QR Code
                            $qr_link = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $v['uuid'];
                            echo "<tr>
                                    <td>
                                        <b>{$v['placa']}</b><br>
                                        <small>{$v['modelo']}</small>
                                    </td>
                                    <td>
                                        <button onclick=\"imprimirQR('$qr_link', '{$v['placa']}')\" class='btn btn-warning btn-sm'>
                                            🖨️ Imprimir QR
                                        </button>
                                        <button onclick=\"alert('ID copiado: {$v['uuid']}');\" class='btn btn-light btn-sm'>
                                            📋 Ver ID
                                        </button>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>