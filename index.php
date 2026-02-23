<?php
session_start();
// Se já estiver logado, manda direto para o dashboard inteligente
if (isset($_SESSION['usuario_id'])) {
    require 'config/conexao.php';
    // Busca o cargo atualizado para redirecionar corretamente
    $stmt = $pdo->prepare("SELECT cargo FROM funcionarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $cargo = $stmt->fetchColumn();

    if ($cargo == 'Frentista') header("Location: modules/abastecimento/index.php");
    elseif ($cargo == 'Balanca') header("Location: modules/balanca/pesagem.php");
    elseif ($cargo == 'Mecanico') header("Location: modules/manutencao/index.php");
    else header("Location: modules/admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mineração XYZ - Portal Corporativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        /* Lado Esquerdo - Imagem de Fundo */
        .bg-mining {
            background-image: url('https://images.unsplash.com/photo-1578328819058-b69f3a3b0f6b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
            height: 100vh;
        }

        .bg-mining::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(0, 51, 32, 0.85) 0%, rgba(0, 0, 0, 0.6) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 10%;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #ffc107;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero-content p {
            font-size: 1.2rem;
            font-weight: 400;
            opacity: 0.9;
        }

        /* Lado Direito - Painel de Login */
        .login-panel {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 3;
            overflow-y: auto; /* Permite rolar se a tela for pequena */
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            margin-bottom: 3rem; /* Espaço para o footer não encostar */
        }

        .logo-login {
            width: 80px;
            height: 80px;
            background-color: #004d30;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 4px 15px rgba(0, 77, 48, 0.3);
        }

        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #004d30;
            box-shadow: 0 0 0 0.2rem rgba(0, 77, 48, 0.25);
            background-color: #fff;
        }

        .btn-login {
            background-color: #004d30;
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #003320;
            color: #ffc107;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Botão do App Android */
        .btn-android {
            border: 2px solid #3ddc84; /* Cor oficial do Android */
            color: #004d30;
            padding: 0.6rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-android:hover {
            background-color: #3ddc84;
            color: #111;
        }

        .btn-android i {
            font-size: 1.3rem;
            color: #3ddc84;
            margin-right: 8px;
            transition: color 0.3s;
        }

        .btn-android:hover i {
            color: #111;
        }

        .footer-login {
            position: absolute;
            bottom: 20px;
            text-align: center;
            width: 100%;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-lg-7 col-xl-8 d-none d-lg-block bg-mining">
                <div class="hero-content h-100 d-flex flex-column justify-content-center">
                    <h1>Sustentabilidade <br>& Inovação</h1>
                    <p class="mt-3 w-75">
                        Transformando recursos naturais em prosperidade com tecnologia, segurança e respeito ao meio ambiente. Acesse o portal corporativo para gerenciar operações de logística, frota e manutenção em tempo real.
                    </p>
                    <div class="mt-5">
                        <span class="badge bg-warning text-dark p-2 me-2"><i class="bi bi-cpu"></i> Sistema Integrado</span>
                        <span class="badge bg-light text-dark p-2"><i class="bi bi-shield-check"></i> Operação Segura</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5 col-xl-4 login-panel">
                <div class="login-box">
                    
                    <div class="logo-login">
                        <i class="bi bi-globe-americas"></i>
                    </div>
                    
                    <h3 class="text-center mb-1 fw-bold" style="color: #004d30;">Portal do Colaborador</h3>
                    <p class="text-center text-muted mb-4">Insira suas credenciais para acessar</p>

                    <?php if (isset($_SESSION['erro_login'])): ?>
                        <div class="alert alert-danger text-center shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $_SESSION['erro_login']; ?>
                            <?php unset($_SESSION['erro_login']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="login_action.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">E-mail Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" placeholder="nome@mineradora.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary d-flex justify-content-between">
                                Senha
                                <a href="#" class="text-decoration-none small text-muted">Esqueceu a senha?</a>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="senha" class="form-control border-start-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-4">
                            ENTRAR NO SISTEMA <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </form>

                    <div class="position-relative text-center mb-4">
                        <hr class="text-muted">
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">Acesso Operacional</span>
                    </div>

                    <a href="app/MineradoraApp.apk" download class="btn-android w-100">
                        <i class="bi bi-android2"></i> Baixar App para Android
                    </a>

                </div>
                
                <div class="footer-login">
                    &copy; <?php echo date('Y'); ?> Gestão de Logística | Desenvolvido por MouraFuseti
                </div>
            </div>

        </div>
    </div>

</body>
</html>