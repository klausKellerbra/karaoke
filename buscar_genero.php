<?php
include 'conexao.php';

$genero = $_GET['gen'] ?? '';
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

<h2>Gênero: <?php echo htmlspecialchars($genero); ?></h2>

<div class="item-header with-fav">
    <span class="header-fav" aria-hidden="true">★</span>
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php
$sql = "
SELECT codigo, musica, artista
FROM musicas_karaoke
WHERE genero = $1
ORDER BY artista, musica
LIMIT 200;
";

$result = pg_query_params($conn, $sql, [$genero]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item with-fav' onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";
    echo "<button type='button' class='fav-btn' data-codigo='{$row['codigo']}' title='Favoritar' aria-label='Adicionar aos favoritos' aria-pressed='false'></button>";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

</body>
</html>