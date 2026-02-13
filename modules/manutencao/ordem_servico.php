<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Mecanico', 'Gerente']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova Ordem de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <h3><i class="bi bi-tools"></i> Abertura de Manutenção</h3>
        
        <div id="reader" style="width: 100%; max-width: 500px; margin: auto;"></div>

        <div id="form-os" style="display:none;" class="card p-4 mt-3 border-danger">
            <form action="salvar_os.php" method="POST">
                <h5 class="text-danger">Veículo Identificado: <span id="display_veiculo"></span></h5>
                <input type="hidden" id="uuid_veiculo" name="uuid">
                
                <div class="mb-3">
                    <label>Tipo de Manutenção</label>
                    <select name="tipo" class="form-select">
                        <option value="Corretiva">Corretiva (Quebrou)</option>
                        <option value="Preventiva">Preventiva (Revisão)</option>
                        <option value="Preditiva">Preditiva (Troca Antecipada)</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label>Descrição do Problema</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Prioridade</label>
                    <select name="prioridade" class="form-select">
                        <option value="Alta">Alta (Parar máquina agora)</option>
                        <option value="Media">Média</option>
                        <option value="Baixa">Baixa</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger w-100">Abrir O.S.</button>
            </form>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText) {
            document.getElementById('uuid_veiculo').value = decodedText;
            document.getElementById('display_veiculo').innerText = decodedText; // Mostra o ID
            document.getElementById('form-os').style.display = 'block';
            html5QrcodeScanner.clear();
        }
        var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>