<?php
require '../../config/auth.php';
// Verifica se é Admin ou Gerente
checkPermissao(['Administrador', 'Gerente']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Dashboard Gerencial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-dashboard { transition: transform 0.2s; cursor: pointer; }
        .card-dashboard:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">
    <?php include '../../templates/header.php'; ?>
    
    <div class="container mt-4 mb-5">
        <h2 class="mb-3"><i class="bi bi-speedometer2"></i> Painel de Controle</h2>
        <p class="text-muted">Bem-vindo, <strong><?php echo $_SESSION['usuario_nome']; ?></strong></p>
        <hr>

        <div class="row g-4">
            <div class="col-12"><h5 class="text-secondary">📂 Cadastros Gerais</h5></div>
            
            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="bi bi-truck display-4 text-primary"></i>
                        <h6 class="card-title mt-3">Veículos</h6>
                        <a href="../cadastro/veiculos.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="bi bi-people display-4 text-primary"></i>
                        <h6 class="card-title mt-3">Funcionários</h6>
                        <a href="../cadastro/funcionarios.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="bi bi-shop display-4 text-primary"></i>
                        <h6 class="card-title mt-3">Fornecedores</h6>
                        <a href="../cadastro/fornecedores.php" class="btn btn-sm btn-outline-primary w-100 stretched-link">Acessar</a>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4"><h5 class="text-secondary">⚙️ Operacional</h5></div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-success">
                    <div class="card-body text-center">
                        <i class="bi bi-fuel-pump display-4 text-success"></i>
                        <h6 class="card-title mt-3">Abastecimento</h6>
                        <a href="../abastecimento/index.php" class="btn btn-sm btn-outline-success w-100 stretched-link">Lançar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-warning">
                    <div class="card-body text-center">
                        <i class="bi bi-aspect-ratio display-4 text-warning"></i>
                        <h6 class="card-title mt-3">Balança</h6>
                        <a href="../balanca/pesagem.php" class="btn btn-sm btn-outline-warning w-100 stretched-link">Pesar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 border-danger">
                    <div class="card-body text-center">
                        <i class="bi bi-tools display-4 text-danger"></i>
                        <h6 class="card-title mt-3">Oficina Mecânica</h6>
                        <a href="../manutencao/index.php" class="btn btn-sm btn-outline-danger w-100 stretched-link">Gerenciar</a>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4"><h5 class="text-secondary">📊 Relatórios e Históricos</h5></div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-data display-5 text-secondary"></i>
                        <h6 class="card-title mt-2">Histórico Diesel</h6>
                        <a href="../abastecimento/historico.php" class="btn btn-sm btn-secondary w-100 stretched-link">Ver Lista</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-receipt display-5 text-secondary"></i>
                        <h6 class="card-title mt-2">Histórico Balança</h6>
                        <a href="../balanca/historico.php" class="btn btn-sm btn-secondary w-100 stretched-link">Ver Lista</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard h-100 bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-check display-5 text-secondary"></i>
                        <h6 class="card-title mt-2">Histórico Oficina</h6>
                        <a href="../manutencao/historico.php" class="btn btn-sm btn-secondary w-100 stretched-link">Ver Lista</a>
                    </div>
                </div>
            </div>
            
             <div class="col-md-3">
                <div class="card card-dashboard h-100 bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-lock display-5 text-secondary"></i>
                        <h6 class="card-title mt-2">Logs do Sistema</h6>
                        <a href="../admin/logs.php" class="btn btn-sm btn-secondary w-100 stretched-link">Auditoria</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>