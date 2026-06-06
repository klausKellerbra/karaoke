<?php
include 'conexao.php';
include 'paginacao.php';

$genero = $_GET['gen'] ?? '';
$pagina = getCurrentPage();
$limite = getLimit();
$offset = ($pagina - 1) * $limite;

$totalQuery = pg_query_params($conn, "SELECT COUNT(*) FROM musicas_karaoke WHERE genero = $1", [$genero]);
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
LIMIT $limite OFFSET $offset;
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

renderLimitControl($limite);
renderPaginationInfo($pagina, $limite, $total);
renderPagination($pagina, $total_paginas);
?>

</body>
</html>