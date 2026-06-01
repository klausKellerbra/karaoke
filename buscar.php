<?php
include 'conexao.php';

$busca = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header-fav {
            text-align: center;
            color: #d63031;
        }
    </style>
</head>

<body>
    <?php include 'layout_topo.php'; ?>





<br>

<!-- ✅ BUSCA DE NOVO -->
<form action="buscar.php" method="get">
    <input type="text" name="q" value="<?php echo htmlspecialchars($busca); ?>">
    <button type="submit">Buscar</button>
</form>

<hr>

<h3>Resultados para: <?php echo htmlspecialchars($busca); ?></h3>

<div class="item-header with-fav">
    <span class="header-fav" aria-hidden="true">★</span>
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php

$sql = "
SELECT codigo, musica, artista,artista_normalizado
FROM musicas_karaoke
WHERE musica ILIKE $1 OR artista ILIKE $1
ORDER BY artista
LIMIT 50;
";

$result = pg_query_params($conn, $sql, ["%$busca%"]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item with-fav' onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";
    echo "<button type='button' class='fav-btn' data-codigo='{$row['codigo']}' title='Favoritar' aria-label='Adicionar aos favoritos' aria-pressed='false'></button>";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista_normalizado']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

</div>
</body>
</html>