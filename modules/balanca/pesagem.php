<?php
require '../../config/auth.php';
// Apenas Balança e Admin
checkPermissao(['Administrador', 'Balanca']); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Balança Rodoviária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">Leitura do Caminhão</div>
                    <div class="card-body">
                        <div id="reader" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">Dados da Pesagem</div>
                    <div class="card-body">
                        <form action="salvar_pesagem.php" method="POST">
                            <div class="mb-3">
                                <label>ID Veículo</label>
                                <input type="text" id="uuid_veiculo" name="uuid" class="form-control" readonly required>
                            </div>
                            <div class="mb-3">
                                <label>Material</label>
                                <select name="material" class="form-select">
                                    <option value="Minerio de Ferro">Minério de Ferro</option>
                                    <option value="Esteril">Estéril</option>
                                    <option value="Brita">Brita</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Peso Bruto (Kg)</label>
                                <input type="number" name="peso_bruto" id="peso_bruto" class="form-control" placeholder="Ex: 45000" required>
                            </div>
                            <div class="mb-3">
                                <label>Tara (Kg - Automático se cadastrado)</label>
                                <input type="number" name="tara" class="form-control" placeholder="Ex: 15000">
                            </div>
                            <button type="submit" class="btn btn-warning w-100 btn-lg">Registrar Peso</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText) {
            document.getElementById('uuid_veiculo').value = decodedText;
            alert("Veículo " + decodedText + " identificado!");
            // Aqui você poderia fazer uma chamada AJAX para buscar a Tara do banco automaticamente
        }
        var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>