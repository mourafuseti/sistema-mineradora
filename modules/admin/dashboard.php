<?php
require '../../config/auth.php';
// Permite que todos acessem o dashboard, mas a visão será controlada abaixo
checkPermissao(['Administrador', 'Gerente', 'Frentista', 'Balanca', 'Mecanico']);
$cargo = $_SESSION['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Painel de Controle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-dashboard { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; border-radius: 10px; }
        .card-dashboard:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
        .section-title { font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; border-bottom: 2px solid #e9ecef; padding-bottom: 5px; }
    </style>
</head>
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bi bi-speedometer2 text-primary"></i> Meu Painel</h2>
            <span class="text-muted">Bem-vindo(a), <strong><?php echo $_SESSION['usuario_nome']; ?></strong> (<?php echo $cargo; ?>)</span>
        </div>
        
        <div class="row g-4">
            
            <?php if(in_array($cargo, ['Administrador', 'Gerente'])): ?>
                <div class="col-12 mt-4">
                    <div class="section-title text-secondary"><i class="bi bi-folder-fill me-2"></i> Cadastros Gerais</div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-dashboard h-100 border-primary shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-truck display-4 text-primary"></i>
                            <h6 class="card-title mt-3 fw-bold">Veículos</h6>
                            <a href="../cadastro/veiculos.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-dashboard h-100 border-primary shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge display-4 text-primary"></i>
                            <h6 class="card-title mt-3 fw-bold">Colaboradores</h6>
                            <a href="../cadastro/funcionarios.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-dashboard h-100 border-primary shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-shop display-4 text-primary"></i>
                            <h6 class="card-title mt-3 fw-bold">Fornecedores</h6>
                            <a href="../cadastro/fornecedores.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                        </div>
                    </div>
                </div>

                <?php if($cargo == 'Administrador'): ?>
                <div class="col-md-3">
                    <div class="card card-dashboard h-100 border-danger shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-lock display-4 text-danger"></i>
                            <h6 class="card-title mt-3 text-danger fw-bold">Usuários do Sistema</h6>
                            <a href="../admin/usuarios.php" class="btn btn-sm btn-outline-danger w-100 stretched-link">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="col-12 mt-5">
                <div class="section-title text-secondary"><i class="bi bi-gear-fill me-2"></i> Operacional</div>
            </div>

            <?php if(in_array($cargo, ['Administrador', 'Gerente', 'Frentista'])): ?>
            <div class="col-md-4">
                <div class="card card-dashboard h-100 border-success shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-fuel-pump display-4 text-success"></i>
                        <h5 class="card-title mt-3 fw-bold">Abastecimento</h5>
                        <p class="card-text small text-muted mb-3">Lançar Combustível via QR</p>
                        <a href="../abastecimento/index.php" class="btn btn-success w-100 stretched-link">Lançar</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(in_array($cargo, ['Administrador', 'Gerente', 'Balanca'])): ?>
            <div class="col-md-4">
                <div class="card card-dashboard h-100 border-warning shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-aspect-ratio display-4 text-warning"></i>
                        <h5 class="card-title mt-3 fw-bold">Balança</h5>
                        <p class="card-text small text-muted mb-3">Pesagem de Caminhões</p>
                        <a href="../balanca/pesagem.php" class="btn btn-warning text-dark fw-bold w-100 stretched-link">Pesar</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(in_array($cargo, ['Administrador', 'Gerente', 'Mecanico'])): ?>
            <div class="col-md-4">
                <div class="card card-dashboard h-100 border-danger shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-tools display-4 text-danger"></i>
                        <h5 class="card-title mt-3 fw-bold">Oficina Mecânica</h5>
                        <p class="card-text small text-muted mb-3">Gestão de O.S. e Peças</p>
                        <a href="../manutencao/index.php" class="btn btn-danger w-100 stretched-link">Gerenciar</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(in_array($cargo, ['Administrador', 'Gerente'])): ?>
                <div class="col-12 mt-5">
                    <div class="section-title text-secondary"><i class="bi bi-bar-chart-fill me-2"></i> Relatórios e Segurança</div>
                </div>

                <div class="col-md-3">
                    <div class="card card-dashboard h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-droplet-half display-5 text-success mb-2"></i>
                            <h6 class="card-title fw-bold">Histórico Combustível</h6>
                            <a href="../abastecimento/historico.php" class="btn btn-sm btn-outline-secondary w-100 stretched-link mt-2">Ver Relatório</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-dashboard h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-receipt display-5 text-warning mb-2"></i>
                            <h6 class="card-title fw-bold">Histórico Balança</h6>
                            <a href="../balanca/historico.php" class="btn btn-sm btn-outline-secondary w-100 stretched-link mt-2">Ver Relatório</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-dashboard h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-journal-check display-5 text-danger mb-2"></i>
                            <h6 class="card-title fw-bold">Histórico Oficina</h6>
                            <a href="../manutencao/historico.php" class="btn btn-sm btn-outline-secondary w-100 stretched-link mt-2">Ver Relatório</a>
                        </div>
                    </div>
                </div>
                
                <?php if($cargo == 'Administrador'): ?>
                <div class="col-md-3 mt-4 mt-md-0">
                    <div class="card card-dashboard h-100 bg-dark text-white shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check display-5 text-light mb-2"></i>
                            <h6 class="card-title fw-bold">Logs de Auditoria</h6>
                            <a href="../admin/logs.php" class="btn btn-sm btn-light w-100 stretched-link mt-2">Ver Acessos</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-4">
                    <div class="card card-dashboard h-100 bg-danger text-white shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-database-fill-down display-5 text-light mb-2"></i>
                            <h6 class="card-title fw-bold">Backup do Sistema</h6>
                            <a href="../admin/backup.php" class="btn btn-sm btn-light text-danger fw-bold w-100 stretched-link mt-2">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>