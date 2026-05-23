<?php
include 'conexao.php';
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

<h2>📂 Categorias</h2>

<?php
$sql = "SELECT DISTINCT categoria FROM musicas_karaoke ORDER BY categoria";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $cat = urlencode($row['categoria']);

    echo "<div class='item'
            onclick=\"window.location='buscar_categoria.php?cat=$cat'\">";

    echo $row['categoria'];

    echo "</div>";
}
?>

</div>
</body>
</html>