<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Idioma</title>
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