<?php
include 'conexao.php';

$genero = $_GET['gen'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gênero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .item {
            padding: 10px;
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

        .codigo {
            font-weight: bold;
            color: #2f3640;
        }

        h2 {
            text-align: center;
            color: #2f3640;
        }

        .item-header {
            padding:10px;
            border-bottom:2px solid #2f3640;
            display: grid;
            grid-template-columns: minmax(80px, 80px) 1fr minmax(100px, auto);
            gap: 15px;
            font-weight: 700;
            background: #ecf0f1;
            font-size: 13px;
        }

        .header-codigo {
            text-align: left;
            border-right: 1px solid #bdc3c7;
            padding-right: 10px;
        }

        .header-artista {
            text-align: left;
            border-right: 1px solid #bdc3c7;
            padding-right: 12px;
        }

        .header-musica {
            text-align: left;
            padding-left: 12px;
        }

        .item {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            background: white;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
            display: grid;
            grid-template-columns: minmax(80px, 80px) 1fr minmax(100px, auto);
            gap: 15px;
            align-items: center;
            font-size: 13px;
        }

        .item:hover {
            background: #f1f2f6;
        }

        .codigo {
            color: #0984e3;
            font-weight: 700;
            text-align: left;
            border-right: 1px solid #ddd;
            padding-right: 10px;
        }

        .artista {
            color: #1aaf4f;
            font-weight: 700;
            text-align: left;
            border-right: 1px solid #ddd;
            padding-right: 12px;
        }

        .musica {
            font-weight: 500;
            text-align: left;
            padding-left: 12px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .item {
                padding: 8px;
                font-size: 12px;
                grid-template-columns: minmax(70px, 70px) 1fr minmax(80px, auto);
            }

            .item-header {
                font-size: 12px;
                grid-template-columns: minmax(70px, 70px) 1fr minmax(80px, auto);
            }
        }

        @media (max-width: 480px) {
            .item {
                padding: 6px;
                font-size: 11px;
                grid-template-columns: minmax(60px, 60px) 1fr minmax(70px, auto);
                gap: 8px;
            }

            .item-header {
                font-size: 11px;
                grid-template-columns: minmax(60px, 60px) 1fr minmax(70px, auto);
                gap: 8px;
            }

            .codigo, .header-codigo {
                padding-right: 5px;
            }

            .artista, .header-artista {
                padding-right: 5px;
            }

            .musica, .header-musica {
                padding-left: 5px;
            }
        }
    </style>
</head>


<body>

<?php include 'layout_topo.php'; ?>

<h2>Gênero: <?php echo htmlspecialchars($genero); ?></h2>

<div class="item-header">
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

    echo "<div class='item'
            onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

</body>
</html>