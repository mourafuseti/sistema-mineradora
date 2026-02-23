<?php
require '../../config/auth.php';
require '../../config/conexao.php';
// Apenas Administradores podem criar ou alterar senhas de outros usuários
checkPermissao(['Administrador']);

$msg = "";
$erro = "";
$user_em_edicao = null;

// 1. Excluir Usuário
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    if ($id == $_SESSION['usuario_id']) {
        $erro = "Ação negada: Você não pode excluir a si mesmo!";
    } else {
        $pdo->query("DELETE FROM funcionarios WHERE id = $id");
        $msg = "Usuário removido do sistema!";
        if(function_exists('registrarLog')) registrarLog($pdo, "Excluiu o usuário ID: $id");
    }
}

// 2. Preparar Edição
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = ?");
    $stmt->execute([$id]);
    $user_em_edicao = $stmt->fetch();
}

// 3. Salvar (Novo ou Edição)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atual = $_POST['id_atual'] ?? '';
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email']; // Usado como Login
    $senha = $_POST['senha']; // Senha digitada

    try {
        if ($id_atual) {
            // ---> ATUALIZAR USUÁRIO
            if (!empty($senha)) {
                // Se digitou uma senha nova, atualiza o hash
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "UPDATE funcionarios SET nome=?, cpf=?, cargo=?, email=?, senha_hash=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cpf, $cargo, $email, $senha_hash, $id_atual]);
            } else {
                // Se deixou a senha em branco, atualiza só os outros dados
                $sql = "UPDATE funcionarios SET nome=?, cpf=?, cargo=?, email=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cpf, $cargo, $email, $id_atual]);
            }
            $msg = "Dados de acesso atualizados com sucesso!";
            if(function_exists('registrarLog')) registrarLog($pdo, "Atualizou acessos do usuário: $nome");
            $user_em_edicao = null; // Limpa formulário

        } else {
            // ---> CRIAR NOVO USUÁRIO DE SISTEMA
            if (empty($senha)) {
                $erro = "A senha é obrigatória para novos usuários!";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $uuid = uniqid('USER-'); // Gera um ID interno obrigatório no banco
                
                $sql = "INSERT INTO funcionarios (nome, cpf, cargo, email, senha_hash, uuid_cracha) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cpf, $cargo, $email, $senha_hash, $uuid]);
                
                $msg = "Novo usuário criado e liberado para login!";
                if(function_exists('registrarLog')) registrarLog($pdo, "Criou usuário de sistema: $email");
            }
        }
    } catch(PDOException $e) {
        $erro = "Erro ao salvar (Verifique se o CPF ou E-mail já existem): " . $e->getMessage();
    }
}

// Busca apenas pessoas que têm e-mail cadastrado (Ou seja, que têm login no sistema)
$lista_usuarios = $pdo->query("SELECT * FROM funcionarios WHERE email IS NOT NULL AND email != '' ORDER BY nome");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Usuários do Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="bi bi-shield-lock"></i> Gestão de Acessos (Login e Senha)</h3>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-<?php echo $user_em_edicao ? 'warning' : 'primary'; ?>">
                    <h5 class="card-title text-<?php echo $user_em_edicao ? 'warning' : 'primary'; ?> mb-3">
                        <?php echo $user_em_edicao ? '✏️ Alterar Acesso' : '➕ Novo Acesso'; ?>
                    </h5>
                    
                    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if($erro) echo "<div class='alert alert-danger'>$erro</div>"; ?>

                    <form method="POST">
                        <input type="hidden" name="id_atual" value="<?php echo $user_em_edicao['id'] ?? ''; ?>">

                        <div class="mb-2">
                            <label class="small fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $user_em_edicao['nome'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?php echo $user_em_edicao['cpf'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">Nível de Acesso (Cargo)</label>
                            <select name="cargo" class="form-select border-primary" required>
                                <?php $c = $user_em_edicao['cargo'] ?? ''; ?>
                                <option value="Administrador" <?php echo $c=='Administrador'?'selected':''; ?>>Administrador (Acesso Total)</option>
                                <option value="Gerente" <?php echo $c=='Gerente'?'selected':''; ?>>Gerente (Relatórios e Edição)</option>
                                <option value="Balanca" <?php echo $c=='Balanca'?'selected':''; ?>>Operador de Balança</option>
                                <option value="Frentista" <?php echo $c=='Frentista'?'selected':''; ?>>Frentista (Abastecimento)</option>
                                <option value="Mecanico" <?php echo $c=='Mecanico'?'selected':''; ?>>Mecânico (Oficina)</option>
                            </select>
                        </div>

                        <hr>
                        <h6 class="text-secondary">Credenciais de Login</h6>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">E-mail (Login)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="<?php echo $user_em_edicao['email'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold">Senha de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" name="senha" class="form-control" placeholder="<?php echo $user_em_edicao ? 'Deixe em branco para manter a atual' : 'Digite a senha'; ?>" <?php echo $user_em_edicao ? '' : 'required'; ?>>
                            </div>
                            <?php if($user_em_edicao): ?>
                                <small class="text-muted">Só preencha se quiser mudar a senha do usuário.</small>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-<?php echo $user_em_edicao ? 'warning' : 'primary'; ?> w-100 fw-bold">
                                <?php echo $user_em_edicao ? 'Salvar Edição' : 'Criar Usuário'; ?>
                            </button>
                            <?php if($user_em_edicao): ?>
                                <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="mb-3">Contas Ativas no Sistema</h5>
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Nível / Cargo</th>
                                <th>Login (E-mail)</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lista_usuarios as $u): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $u['nome']; ?></strong><br>
                                    <small class="text-muted">CPF: <?php echo $u['cpf']; ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $cor = match($u['cargo']) {
                                        'Administrador' => 'danger',
                                        'Gerente' => 'warning text-dark',
                                        default => 'primary'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $cor; ?>"><?php echo $u['cargo']; ?></span>
                                </td>
                                <td><kbd><?php echo $u['email']; ?></kbd></td>
                                <td class="text-end">
                                    <a href="usuarios.php?editar=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning text-dark" title="Alterar Senha / Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="usuarios.php?excluir=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" title="Remover Acesso" onclick="return confirm('Tem certeza que deseja apagar este login?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>