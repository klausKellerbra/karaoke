<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categoria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .item {
            padding: 12px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            background: white;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .item:hover {
            background: #f1f2f6;
        }

        h2 {
            text-align: center;
            color: #2f3640;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .item {
                padding: 10px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .item {
                padding: 8px;
                font-size: 14px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
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