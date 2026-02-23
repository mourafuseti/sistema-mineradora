<?php
require '../../config/auth.php';
require '../../config/conexao.php';
// Módulo ultra sensível: Somente Administrador
checkPermissao(['Administrador']);

$msg = "";
$erro = "";
$pasta_backups = '../../backups/';

// Caminhos do XAMPP (Ajuste se o seu MySQL estiver em outro lugar)
$caminho_mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
$caminho_mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';
$db_user = 'root';
$db_pass = ''; // No XAMPP o padrão é vazio
$db_name = 'sistema_mineracao';

// 1. GERAR BACKUP MANUAL
if (isset($_GET['acao']) && $_GET['acao'] == 'gerar') {
    $nome_arquivo = 'backup_manual_' . date('Y-m-d_H-i-s') . '.sql';
    $caminho_completo = $pasta_backups . $nome_arquivo;
    
    // Comando para o Windows exportar o banco
    $comando = "\"$caminho_mysqldump\" -u $db_user $db_name > \"$caminho_completo\"";
    exec($comando, $output, $resultado);
    
    if ($resultado === 0) {
        $msg = "Backup gerado com sucesso: <b>$nome_arquivo</b>";
        if(function_exists('registrarLog')) registrarLog($pdo, "Gerou backup manual do banco de dados");
    } else {
        $erro = "Erro ao gerar backup. Verifique se o caminho do mysqldump está correto.";
    }
}

// 2. EXCLUIR BACKUP
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['arquivo'])) {
    $arquivo = basename($_GET['arquivo']);
    if (file_exists($pasta_backups . $arquivo)) {
        unlink($pasta_backups . $arquivo);
        $msg = "Arquivo de backup excluído.";
    }
}

// 3. RESTAURAR BACKUP
if (isset($_GET['acao']) && $_GET['acao'] == 'restaurar' && isset($_GET['arquivo'])) {
    $arquivo = basename($_GET['arquivo']);
    $caminho_completo = $pasta_backups . $arquivo;
    
    if (file_exists($caminho_completo)) {
        // Comando para o Windows importar o banco
        $comando = "\"$caminho_mysql\" -u $db_user $db_name < \"$caminho_completo\"";
        exec($comando, $output, $resultado);
        
        if ($resultado === 0) {
            $msg = "Banco de dados RESTAURADO com sucesso a partir do arquivo <b>$arquivo</b>!";
            if(function_exists('registrarLog')) registrarLog($pdo, "Restaurou o banco de dados ($arquivo)");
        } else {
            $erro = "Erro crítico ao restaurar o banco de dados.";
        }
    }
}

// LER ARQUIVOS DA PASTA
$arquivos = [];
if (is_dir($pasta_backups)) {
    $arquivos = array_diff(scandir($pasta_backups), array('.', '..'));
    arsort($arquivos); // Ordena do mais novo para o mais velho
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Backup e Restauração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    <div class="container mt-4 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="bi bi-database-fill-gear text-danger"></i> Segurança de Dados (Backup)</h3>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
        </div>

        <?php if($msg) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
        <?php if($erro) echo "<div class='alert alert-danger shadow-sm'>$erro</div>"; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white fw-bold">
                        Ações do Sistema
                    </div>
                    <div class="card-body text-center p-4">
                        <i class="bi bi-hdd-network display-1 text-secondary mb-3"></i>
                        <p class="text-muted small">Faça cópias de segurança regulares para evitar perda de informações operacionais.</p>
                        <a href="backup.php?acao=gerar" class="btn btn-success w-100 btn-lg fw-bold shadow-sm">
                            <i class="bi bi-cloud-download"></i> GERAR BACKUP AGORA
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        Arquivos de Backup Disponíveis
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome do Arquivo</th>
                                    <th>Tamanho</th>
                                    <th>Data / Hora</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($arquivos)): ?>
                                    <tr><td colspan="4" class="text-center text-muted p-4">Nenhum backup encontrado na pasta.</td></tr>
                                <?php else: ?>
                                    <?php foreach($arquivos as $arq): 
                                        $caminho = $pasta_backups . $arq;
                                        $tamanho = round(filesize($caminho) / 1024, 2) . ' KB';
                                        $data = date("d/m/Y H:i:s", filemtime($caminho));
                                    ?>
                                    <tr>
                                        <td><i class="bi bi-filetype-sql text-primary"></i> <b><?php echo $arq; ?></b></td>
                                        <td><?php echo $tamanho; ?></td>
                                        <td><?php echo $data; ?></td>
                                        <td class="text-end">
                                            <a href="<?php echo $caminho; ?>" download class="btn btn-sm btn-outline-primary" title="Baixar">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="backup.php?acao=restaurar&arquivo=<?php echo urlencode($arq); ?>" 
                                               class="btn btn-sm btn-warning" 
                                               onclick="return confirm('ATENÇÃO: Isso vai substituir todo o banco de dados atual pelas informações deste arquivo. Tem certeza absoluta?');"
                                               title="Restaurar este Backup">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                            <a href="backup.php?acao=excluir&arquivo=<?php echo urlencode($arq); ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Apagar este arquivo de backup?');"
                                               title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>