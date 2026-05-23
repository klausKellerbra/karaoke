<?php
include 'conexao.php';

$idioma = $_GET['idioma'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Busca por Idioma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'layout_topo.php'; ?>

<h2>Idioma: <?php echo htmlspecialchars($idioma); ?></h2>

<div class="item-header">
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php
$sql = "
SELECT codigo, musica,artista_normalizado, artista
FROM musicas_karaoke
WHERE idioma = $1
ORDER BY artista, musica
LIMIT 100;
";

$result = pg_query_params($conn, $sql, [$idioma]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item'
            onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista_normalizado']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

</body>
</html>