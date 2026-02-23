<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);

$msg = "";
$erro = "";
$func_em_edicao = null;

// 1. Preparar Edição (Busca os dados do funcionário ao clicar no lápis)
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = ?");
    $stmt->execute([$id]);
    $func_em_edicao = $stmt->fetch();
}

// 2. Processar Formulário (Inserir Novo ou Atualizar Existente)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atual = $_POST['id_atual'] ?? '';
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'] ?: null;
    
    // Tratamento da Câmera (Foto 3x4)
    $foto_nome = null;
    $atualizar_foto = false;
    
    if (!empty($_POST['imagem_base64'])) {
        $pasta_destino = '../../assets/img/funcionarios/';
        if (!is_dir($pasta_destino)) mkdir($pasta_destino, 0777, true);

        $imagem_parts = explode(";base64,", $_POST['imagem_base64']);
        $imagem_base64 = base64_decode($imagem_parts[1]);
        
        // Se for edição, reaproveita o UUID antigo para o nome do arquivo
        if ($id_atual) {
            $uuid_existente = $pdo->query("SELECT uuid_cracha FROM funcionarios WHERE id = $id_atual")->fetchColumn();
            $foto_nome = 'foto_' . $uuid_existente . '.jpg';
        } else {
            $uuid_novo = uniqid('FUNC-');
            $foto_nome = 'foto_' . $uuid_novo . '.jpg';
        }
        
        file_put_contents($pasta_destino . $foto_nome, $imagem_base64);
        $atualizar_foto = true;
    }

    try {
        if ($id_atual) {
            // ---> ATUALIZAR FUNCIONÁRIO EXISTENTE
            if ($atualizar_foto) {
                // Atualiza tudo + foto nova
                $sql = "UPDATE funcionarios SET nome=?, cpf=?, cargo=?, email=?, foto=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cpf, $cargo, $email, $foto_nome, $id_atual]);
            } else {
                // Atualiza apenas os dados em texto (mantém foto antiga)
                $sql = "UPDATE funcionarios SET nome=?, cpf=?, cargo=?, email=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cpf, $cargo, $email, $id_atual]);
            }
            $msg = "Dados atualizados com sucesso!";
            if(function_exists('registrarLog')) registrarLog($pdo, "Atualizou o cadastro de: $nome");
            
            $func_em_edicao = null; // Limpa o formulário após salvar

        } else {
            // ---> CADASTRAR NOVO FUNCIONÁRIO
            $senha_hash = $email ? password_hash('123456', PASSWORD_DEFAULT) : null;
            $uuid = $uuid_novo ?? uniqid('FUNC-'); // Usa o UUID gerado na foto ou cria um agora
            
            $sql = "INSERT INTO funcionarios (nome, cpf, cargo, email, senha_hash, uuid_cracha, foto) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $cpf, $cargo, $email, $senha_hash, $uuid, $foto_nome]);
            
            $msg = "Novo funcionário cadastrado com sucesso!";
            if(function_exists('registrarLog')) registrarLog($pdo, "Cadastrou funcionário: $nome ($cargo)");
        }
    } catch(PDOException $e) {
        $erro = "Erro no banco de dados: " . $e->getMessage();
    }
}

// Busca a lista para a tabela
$lista_funcionarios = $pdo->query("SELECT * FROM funcionarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        #camera-area { width: 100%; height: 250px; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 10px; }
        video { width: 100%; height: 100%; object-fit: cover; }
        #canvas { display: none; } 
        #preview { width: 100px; height: 120px; object-fit: cover; border: 2px solid #28a745; display: none; margin-top: 10px; border-radius: 5px;}
    </style>

    <script>
        function imprimirCracha(nome, cargo, uuid, foto) {
            var urlQr = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + uuid;
            var urlFoto = foto ? '../../assets/img/funcionarios/' + foto : 'https://via.placeholder.com/150?text=Sem+Foto';

            var win = window.open('', '', 'height=600,width=400');
            win.document.write('<html><head><title>Imprimir Crachá</title>');
            win.document.write('<style>');
            win.document.write('body { font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; }');
            win.document.write('.cracha { width: 300px; height: 480px; border: 1px solid #ccc; text-align: center; border-radius: 10px; margin: 20px auto; background: white; overflow: hidden; box-shadow: 0 0 5px rgba(0,0,0,0.1); }');
            win.document.write('.header { background: #004d30; color: white; padding: 15px; font-weight: bold; letter-spacing: 2px; }');
            win.document.write('.foto-container { margin: 20px auto; width: 120px; height: 150px; background: #eee; border: 4px solid #004d30; overflow: hidden; }');
            win.document.write('.foto-container img { width: 100%; height: 100%; object-fit: cover; }');
            win.document.write('.nome { font-size: 20px; font-weight: bold; margin: 10px 0; color: #333; text-transform: uppercase;}');
            win.document.write('.cargo { font-size: 16px; color: #ffc107; font-weight: bold; margin-bottom: 20px; }');
            win.document.write('</style>');
            win.document.write('</head><body>');
            
            win.document.write('<div class="cracha">');
            win.document.write('<div class="header">MINERADORA XYZ</div>');
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
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>👥 Gestão de Colaboradores</h3>
            <a href="../admin/dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-<?php echo $func_em_edicao ? 'warning' : 'primary'; ?>">
                    <h5 class="card-title text-<?php echo $func_em_edicao ? 'warning' : 'primary'; ?> mb-3">
                        <?php echo $func_em_edicao ? '✏️ Editando: ' . $func_em_edicao['nome'] : '📸 Novo Cadastro'; ?>
                    </h5>
                    
                    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if($erro) echo "<div class='alert alert-danger'>$erro</div>"; ?>

                    <form method="POST">
                        <input type="hidden" name="id_atual" value="<?php echo $func_em_edicao['id'] ?? ''; ?>">

                        <div class="mb-3 text-center">
                            <div id="camera-area">
                                <video id="video" autoplay playsinline></video>
                            </div>
                            
                            <img id="preview" alt="Foto capturada" 
                                 src="<?php echo ($func_em_edicao && $func_em_edicao['foto']) ? '../../assets/img/funcionarios/'.$func_em_edicao['foto'] : ''; ?>" 
                                 style="display: <?php echo ($func_em_edicao && $func_em_edicao['foto']) ? 'inline-block' : 'none'; ?>;">
                            
                            <canvas id="canvas" width="300" height="400"></canvas>
                            <input type="hidden" name="imagem_base64" id="imagem_base64">
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-dark btn-sm" onclick="startCamera()">Ligar Câmera</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="takePhoto()">Tirar Foto 3x4</button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Leonardo" value="<?php echo $func_em_edicao['nome'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?php echo $func_em_edicao['cpf'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">Cargo</label>
                            <select name="cargo" class="form-select">
                                <?php $cargo_atual = $func_em_edicao['cargo'] ?? ''; ?>
                                <option value="Motorista" <?php echo $cargo_atual == 'Motorista' ? 'selected' : ''; ?>>Motorista</option>
                                <option value="Frentista" <?php echo $cargo_atual == 'Frentista' ? 'selected' : ''; ?>>Frentista</option>
                                <option value="Mecanico" <?php echo $cargo_atual == 'Mecanico' ? 'selected' : ''; ?>>Mecânico</option>
                                <option value="Balanca" <?php echo $cargo_atual == 'Balanca' ? 'selected' : ''; ?>>Op. Balança</option>
                                <option value="Gerente" <?php echo $cargo_atual == 'Gerente' ? 'selected' : ''; ?>>Gerente</option>
                                <option value="Administrador" <?php echo $cargo_atual == 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="small fw-bold">Email Corporativo</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $func_em_edicao['email'] ?? ''; ?>">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-<?php echo $func_em_edicao ? 'warning' : 'primary'; ?> w-100 fw-bold">
                                <?php echo $func_em_edicao ? 'Salvar Alterações' : 'Salvar Funcionário'; ?>
                            </button>
                            
                            <?php if($func_em_edicao): ?>
                                <a href="funcionarios.php" class="btn btn-outline-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="mb-3">Equipe Cadastrada</h5>
                    <div style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Colaborador</th>
                                    <th>E-mail</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista_funcionarios as $f): 
                                    $foto = $f['foto'] ? "../../assets/img/funcionarios/" . $f['foto'] : "https://via.placeholder.com/50?text=S/F";
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $foto; ?>" width="45" height="45" class="rounded-circle border" style="object-fit:cover;">
                                    </td>
                                    <td>
                                        <strong><?php echo $f['nome']; ?></strong><br>
                                        <small class="text-muted"><?php echo $f['cargo']; ?></small>
                                    </td>
                                    <td><small><?php echo $f['email'] ?: '-'; ?></small></td>
                                    <td class="text-end">
                                        
                                        <a href="funcionarios.php?editar=<?php echo $f['id']; ?>" class="btn btn-sm btn-warning text-dark" title="Editar Funcionário">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button onclick="imprimirCracha('<?php echo $f['nome']; ?>', '<?php echo $f['cargo']; ?>', '<?php echo $f['uuid_cracha']; ?>', '<?php echo $f['foto']; ?>')" class="btn btn-sm btn-outline-dark" title="Imprimir Crachá">
                                            🪪
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

        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => { video.srcObject = stream; })
                .catch(err => { alert("Erro ao acessar câmera: " + err); });
        }

        function takePhoto() {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 300, 400);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8); // 0.8 comprime um pouco para não pesar no banco
            
            preview.src = dataUrl;
            preview.style.display = 'inline-block';
            inputBase64.value = dataUrl;
        }
    </script>
</body>
</html>