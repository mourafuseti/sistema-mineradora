<?php
// Define a raiz do sistema para os links nunca quebrarem
$base_url = '/sistema-mineradora';
$cargo_header = $_SESSION['cargo'] ?? '';
$nome_header = explode(" ", $_SESSION['usuario_nome'] ?? 'Usuário')[0]; // Pega só o primeiro nome
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm no-print">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-warning" href="<?php echo $base_url; ?>/modules/admin/dashboard.php">
            <i class="bi bi-globe-americas me-2"></i> MINERADORA BR
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $base_url; ?>/modules/admin/dashboard.php">
                        <i class="bi bi-house-door"></i> Meu Painel
                    </a>
                </li>

                <?php if(in_array($cargo_header, ['Administrador', 'Gerente'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-folder-fill"></i> Cadastros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/modules/cadastro/veiculos.php">Veículos</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/modules/cadastro/funcionarios.php">Colaboradores</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/modules/cadastro/fornecedores.php">Fornecedores</a></li>
                            <?php if($cargo_header == 'Administrador'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="<?php echo $base_url; ?>/modules/admin/usuarios.php"><i class="bi bi-shield-lock"></i> Usuários do Sistema</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(in_array($cargo_header, ['Administrador', 'Gerente', 'Frentista'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="<?php echo $base_url; ?>/modules/abastecimento/index.php">
                            <i class="bi bi-fuel-pump"></i> Abastecimento
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(in_array($cargo_header, ['Administrador', 'Gerente', 'Balanca'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo $base_url; ?>/modules/balanca/pesagem.php">
                            <i class="bi bi-aspect-ratio"></i> Balança
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(in_array($cargo_header, ['Administrador', 'Gerente', 'Mecanico'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-tools"></i> Oficina
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/modules/manutencao/index.php">Gerenciar O.S.</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/modules/manutencao/pecas.php">Estoque de Peças</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="d-flex align-items-center">
                <span class="text-light me-3 small">
                    <i class="bi bi-person-circle"></i> Olá, <b><?php echo $nome_header; ?></b> 
                    <span class="badge bg-secondary ms-1"><?php echo $cargo_header; ?></span>
                </span>
                
                <a href="<?php echo $base_url; ?>/logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
    </div>
</nav>

<div style="margin-bottom: 20px;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>