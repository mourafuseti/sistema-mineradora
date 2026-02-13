<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MinaSystem</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" href="/sistema-mineradora/modules/admin/dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sistema-mineradora/logout.php">Sair</a>
        </li>
      </ul>
      <span class="navbar-text text-white">
        Usuário: <?php echo $_SESSION['usuario_nome'] ?? 'Visitante'; ?>
      </span>
    </div>
  </div>
</nav>