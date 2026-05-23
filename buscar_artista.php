<?php
include 'conexao.php';

$artista = $_GET['art'] ?? '';
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

<h2>Artista: <?php echo htmlspecialchars($artista); ?></h2>

<div class="item-header">
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php
$sql = "
SELECT codigo, musica, artista, artista_normalizado
FROM musicas_karaoke
WHERE artista_normalizado = $1
ORDER BY musica
LIMIT 200;
";

$result = pg_query_params($conn, $sql, [$artista]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item'
            onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>" . htmlspecialchars($row['artista_normalizado']) . "</span>";
    echo "<span class='musica'>" . htmlspecialchars($row['musica']) . "</span>";

    echo "</div>";
}
?>

</body>
</html>
