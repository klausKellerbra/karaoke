<?php
include 'conexao.php';
include 'paginacao.php';

$categoria = $_GET['cat'] ?? '';
$pagina = getCurrentPage();
$limite = getLimit();
$offset = ($pagina - 1) * $limite;

$totalQuery = pg_query_params($conn, "SELECT COUNT(*) FROM musicas_karaoke WHERE categoria = $1", [$categoria]);
$total = intval(pg_fetch_result($totalQuery, 0, 0));
$total_paginas = max(1, (int) ceil($total / $limite));

if ($pagina > $total_paginas && $total_paginas > 0) {
    $pagina = $total_paginas;
    $offset = ($pagina - 1) * $limite;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categoria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>


<body>
<?php include 'layout_topo.php'; ?>

<h2>Categoria: <?php echo htmlspecialchars($categoria); ?></h2>

<div class="item-header">
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php
$sql = "
SELECT codigo, musica, artista, artista_normalizado
FROM musicas_karaoke
WHERE categoria = $1
ORDER BY artista, musica
LIMIT $limite OFFSET $offset;
";

$result = pg_query_params($conn, $sql, [$categoria]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item'
            onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista_normalizado']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}

renderLimitControl($limite);
renderPaginationInfo($pagina, $limite, $total);
renderPagination($pagina, $total_paginas);
?>

</body>
</html>