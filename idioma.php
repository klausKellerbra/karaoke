<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Idioma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>



 <?php include 'layout_topo.php'; ?>

<h2>🌎 Idiomas</h2>

<?php
$sql = "SELECT DISTINCT idioma FROM musicas_karaoke ORDER BY idioma";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $idioma = urlencode($row['idioma']);

    echo "<div class='item'
            onclick=\"window.location='buscar_idioma.php?idioma=$idioma'\">";

    echo $row['idioma'];

    echo "</div>";
}
?>

</div>
</body>
</html>