<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gênero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

 <?php include 'layout_topo.php'; ?>

<h2>🎸 Gêneros</h2>

<?php
$sql = "SELECT DISTINCT genero FROM musicas_karaoke ORDER BY genero";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $gen = urlencode($row['genero']);

    echo "<div class='item'
            onclick=\"window.location='buscar_genero.php?gen=$gen'\">";

    echo $row['genero'];

    echo "</div>";
}
?>

</div>
</body>
</html>