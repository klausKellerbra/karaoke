<?php
include 'conexao.php';

$busca = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'layout_topo.php'; ?>
    <title>Resultados</title>

    <style>
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background: #00b894;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #00a085;
        }

        .item {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: grid;
            grid-template-columns: minmax(80px, 80px) 1fr minmax(100px, auto);
            gap: 15px;
            background: white;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
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
        }

        @media (max-width: 480px) {
            form {
                margin: 10px;
                padding: 10px;
            }

            input[type="text"], button {
                font-size: 14px;
                padding: 8px;
            }

            .item {
                padding: 6px;
                font-size: 11px;
                grid-template-columns: minmax(60px, 60px) 1fr minmax(70px, auto);
                gap: 8px;
            }

            .codigo, .artista {
                padding-right: 5px;
            }

            .musica {
                padding-left: 5px;
            }
        }
    </style>
</head>

<body>





<br>

<!-- ✅ BUSCA DE NOVO -->
<form action="buscar.php" method="get">
    <input type="text" name="q" value="<?php echo htmlspecialchars($busca); ?>">
    <button type="submit">Buscar</button>
</form>

<hr>

<h3>Resultados para: <?php echo htmlspecialchars($busca); ?></h3>

<?php

$sql = "
SELECT codigo, musica, artista
FROM musicas_karaoke
WHERE musica ILIKE $1 OR artista ILIKE $1
ORDER BY artista
LIMIT 50;
";

$result = pg_query_params($conn, $sql, ["%$busca%"]);

while ($row = pg_fetch_assoc($result)) {

    echo "<div class='item'
        onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

</div>
</body>
</html>