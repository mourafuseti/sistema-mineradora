<?php
require '../../config/auth.php';
require '../../config/conexao.php';
checkPermissao(['Administrador', 'Gerente']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mapa da Mina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>#map { height: 600px; width: 100%; }</style>
</head>
<body>
    <?php include '../../templates/header.php'; ?>
    
    <div class="container-fluid mt-3">
        <h3>Rastreamento em Tempo Real</h3>
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Centraliza o mapa (Exemplo: Coordenadas de Minas Gerais)
        var map = L.map('map').setView([-20.0, -44.0], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; OpenStreetMap contributors'
        }).addTo(map);

        // Busca posições do banco via PHP
        var veiculos = <?php
            $sql = "SELECT placa, latitude, longitude, status FROM veiculos WHERE latitude IS NOT NULL";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll());
        ?>;

        veiculos.forEach(function(v) {
            L.marker([v.latitude, v.longitude])
                .addTo(map)
                .bindPopup("<b>" + v.placa + "</b><br>Status: " + v.status);
        });
    </script>
</body>
</html>