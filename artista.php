<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

 <?php include 'layout_topo.php'; ?>

<h2>👤 Artistas</h2>

<?php
$sql = "SELECT DISTINCT artista_normalizado FROM musicas_karaoke WHERE artista_normalizado IS NOT NULL AND artista_normalizado != '' ORDER BY artista_normalizado";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $artista = urlencode($row['artista_normalizado']);

    echo "<div class='item'
            onclick=\"window.location='buscar_artista.php?art=$artista'\">";

    echo htmlspecialchars($row['artista_normalizado']);

    echo "</div>";
}
?>

</div>
</body>
</html>
