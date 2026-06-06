<?php
include 'conexao.php';
include 'paginacao.php';

$pagina = getCurrentPage();
$limite = getLimit();
$offset = ($pagina - 1) * $limite;

$totalQuery = pg_query($conn, "SELECT COUNT(DISTINCT genero) FROM musicas_karaoke");
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

<h2>🎸 Gêneros</h2>

<?php
$sql = "SELECT DISTINCT genero FROM musicas_karaoke ORDER BY genero LIMIT $limite OFFSET $offset";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $gen = urlencode($row['genero']);

    echo "<div class='item'
            onclick=\"window.location='buscar_genero.php?gen=$gen'\">";

    echo $row['genero'];

    echo "</div>";
}

renderLimitControl($limite);
renderPaginationInfo($pagina, $limite, $total);
renderPagination($pagina, $total_paginas);
?>

</div>
</body>
</html>