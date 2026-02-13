<?php
require '../../config/auth.php';
checkPermissao(['Administrador', 'Gerente', 'Frentista']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lançar Abastecimento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        #reader { width: 100%; border-radius: 10px; overflow: hidden; }
        .modo-manual { display: none; }
    </style>
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    
    <div class="container mt-4" style="max-width: 600px;">
        <h3 class="text-center mb-3">⛽ Novo Abastecimento</h3>
        
        <div class="d-flex justify-content-center mb-3 gap-2">
            <button onclick="ativarCamera()" class="btn btn-outline-primary active" id="btnCam">📷 Câmera</button>
            <button onclick="ativarManual()" class="btn btn-outline-secondary" id="btnMan">⌨️ Digitar</button>
        </div>

        <div id="area-camera">
            <div id="reader"></div>
            <p class="text-center text-muted mt-2">Aponte para o QR Code do veículo</p>
        </div>

        <div id="form-abastecimento" class="modo-manual card p-4 shadow-sm">
            <form action="salvar.php" method="POST">
                
                <div class="mb-3">
                    <label class="fw-bold">Veículo (QR Code ou ID)</label>
                    <input type="text" id="uuid_veiculo" name="uuid" class="form-control bg-light" placeholder="Ex: V-65c3..." required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Tipo de Combustível</label>
                    <select name="tipo_combustivel" class="form-select form-select-lg border-primary" required>
                        <option value="Diesel" selected>Diesel (S-10 / S-500)</option>
                        <option value="Gasolina">Gasolina</option>
                        <option value="Alcool">Álcool (Etanol)</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <label class="fw-bold">Litros</label>
                        <input type="number" step="0.01" name="litros" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold">Horímetro / KM</label>
                        <input type="number" name="horimetro" class="form-control" placeholder="12345" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success mt-4 w-100 btn-lg">✅ Confirmar</button>
            </form>
        </div>
    </div>

    <script>
        var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        
        function onScanSuccess(decodedText) {
            document.getElementById('uuid_veiculo').value = decodedText;
            ativarManual();
            // alert("Veículo Identificado!"); // Opcional
            html5QrcodeScanner.clear();
        }

        function ativarCamera() {
            document.getElementById('area-camera').style.display = 'block';
            document.getElementById('form-abastecimento').style.display = 'none';
            html5QrcodeScanner.render(onScanSuccess);
        }

        function ativarManual() {
            document.getElementById('area-camera').style.display = 'none';
            document.getElementById('form-abastecimento').style.display = 'block';
            try { html5QrcodeScanner.clear(); } catch(e) {}
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>