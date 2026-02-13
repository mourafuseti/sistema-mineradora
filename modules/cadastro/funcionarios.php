<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

// 1. Processar Formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'] ?: null;
    $senha_hash = $email ? password_hash('123456', PASSWORD_DEFAULT) : null;
    $uuid = uniqid('FUNC-');
    $foto_nome = null;

    // 2. Salvar a Foto (Se foi tirada)
    if (!empty($_POST['imagem_base64'])) {
        $pasta_destino = '../../assets/img/funcionarios/';
        
        // Cria a pasta se não existir
        if (!is_dir($pasta_destino)) mkdir($pasta_destino, 0777, true);

        // Converte Base64 para Arquivo de Imagem
        $imagem_parts = explode(";base64,", $_POST['imagem_base64']);
        $imagem_base64 = base64_decode($imagem_parts[1]);
        $foto_nome = 'foto_' . $uuid . '.jpg';
        
        file_put_contents($pasta_destino . $foto_nome, $imagem_base64);
    }

    try {
        $sql = "INSERT INTO funcionarios (nome, cpf, cargo, email, senha_hash, uuid_cracha, foto) 
                VALUES (:nome, :cpf, :cargo, :email, :senha, :uuid, :foto)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nome, 'cpf' => $cpf, 'cargo' => $cargo, 
            'email' => $email, 'senha' => $senha_hash, 'uuid' => $uuid,
            'foto' => $foto_nome
        ]);
        $msg = "Funcionário cadastrado com foto!";
    } catch(PDOException $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}

$lista_funcionarios = $pdo->query("SELECT * FROM funcionarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro com Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #camera-area { width: 100%; height: 250px; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 10px; }
        video { width: 100%; height: 100%; object-fit: cover; }
        #canvas { display: none; } /* Escondido, serve só para processar */
        #preview { width: 100px; height: 120px; object-fit: cover; border: 2px solid #28a745; display: none; margin-top: 10px; border-radius: 5px;}
    </style>

    <script>
        function imprimirCracha(nome, cargo, uuid, foto) {
            var urlQr = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + uuid;
            // Caminho da foto ou placeholder se não tiver
            var urlFoto = foto ? '../../assets/img/funcionarios/' + foto : 'https://via.placeholder.com/150?text=Sem+Foto';

            var win = window.open('', '', 'height=600,width=400');
            win.document.write('<html><head><title>Imprimir Crachá</title>');
            win.document.write('<style>');
            win.document.write('body { font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; }');
            win.document.write('.cracha { width: 300px; height: 480px; border: 1px solid #ccc; text-align: center; border-radius: 10px; margin: 20px auto; background: white; overflow: hidden; box-shadow: 0 0 5px rgba(0,0,0,0.1); }');
            win.document.write('.header { background: #003366; color: white; padding: 15px; font-weight: bold; letter-spacing: 2px; }');
            win.document.write('.foto-container { margin: 20px auto; width: 120px; height: 150px; background: #eee; border: 4px solid #003366; overflow: hidden; }');
            win.document.write('.foto-container img { width: 100%; height: 100%; object-fit: cover; }');
            win.document.write('.nome { font-size: 20px; font-weight: bold; margin: 10px 0; color: #333; text-transform: uppercase;}');
            win.document.write('.cargo { font-size: 16px; color: #cc0000; font-weight: bold; margin-bottom: 20px; }');
            win.document.write('</style>');
            win.document.write('</head><body>');
            
            win.document.write('<div class="cracha">');
            win.document.write('<div class="header">MINERADORA</div>');
            win.document.write('<div class="foto-container"><img src="' + urlFoto + '"></div>');
            win.document.write('<div class="nome">' + nome + '</div>');
            win.document.write('<div class="cargo">' + cargo + '</div>');
            win.document.write('<img src="' + urlQr + '" width="100" height="100">');
            win.document.write('<p style="font-size:10px;">ID: ' + uuid + '</p>');
            win.document.write('</div>');
            
            win.document.write('<center><button onclick="window.print()">🖨️ Imprimir</button></center>');
            win.document.write('</body></html>');
            win.document.close();
        }
    </script>
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-5">
                <div class="card p-3 shadow">
                    <h4 class="mb-3">📸 Novo Cadastro</h4>
                    
                    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

                    <form method="POST">
                        <div class="mb-3 text-center">
                            <div id="camera-area">
                                <video id="video" autoplay playsinline></video>
                            </div>
                            <img id="preview" alt="Foto capturada">
                            <canvas id="canvas" width="300" height="400"></canvas>
                            
                            <input type="hidden" name="imagem_base64" id="imagem_base64">
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="startCamera()">Ligar Câmera</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="takePhoto()">Capturar Foto 3x4</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-2">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label>CPF</label>
                                <input type="text" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label>Cargo</label>
                                <select name="cargo" class="form-select">
                                    <option value="Motorista">Motorista</option>
                                    <option value="Frentista">Frentista</option>
                                    <option value="Mecanico">Mecânico</option>
                                    <option value="Balanca">Op. Balança</option>
                                    <option value="Gerente">Gerente</option>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label>Email (Opcional)</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">💾 Salvar Funcionário</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-3 shadow">
                    <h4>Funcionários</h4>
                    <div style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nome / Cargo</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista_funcionarios as $f): 
                                    $foto = $f['foto'] ? "../../assets/img/funcionarios/" . $f['foto'] : "https://via.placeholder.com/50?text=X";
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $foto; ?>" width="50" height="50" class="rounded-circle border">
                                    </td>
                                    <td>
                                        <strong><?php echo $f['nome']; ?></strong><br>
                                        <small class="text-muted"><?php echo $f['cargo']; ?></small>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="imprimirCracha('<?php echo $f['nome']; ?>', '<?php echo $f['cargo']; ?>', '<?php echo $f['uuid_cracha']; ?>', '<?php echo $f['foto']; ?>')" class="btn btn-sm btn-outline-dark">
                                            🪪 Crachá
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('preview');
        const inputBase64 = document.getElementById('imagem_base64');

        // 1. Ligar a Câmera
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => {
                    alert("Erro ao acessar câmera: " + err);
                });
        }

        // 2. Tirar Foto
        function takePhoto() {
            const context = canvas.getContext('2d');
            // Desenha o frame do vídeo no canvas
            context.drawImage(video, 0, 0, 300, 400);
            
            // Converte para Base64 (Texto)
            const dataUrl = canvas.toDataURL('image/jpeg');
            
            // Mostra prévia e salva no input hidden
            preview.src = dataUrl;
            preview.style.display = 'block';
            inputBase64.value = dataUrl;
        }
    </script>
</body>
</html>