<?php
include 'conexao.php';

// página atual
$pagina = intval($_GET['p'] ?? 1);
if ($pagina < 1) $pagina = 1;

// limite de itens por página
$opcoes_limite = [10, 20, 50];
$limite = intval($_GET['limite'] ?? 20);
if (!in_array($limite, $opcoes_limite)) {
    $limite = 20;
}

$offset = ($pagina - 1) * $limite;

// total de registros
$total_query = pg_query($conn, "SELECT COUNT(*) FROM musicas_karaoke");
$total = pg_fetch_result($total_query, 0, 0);

// total de páginas
$total_paginas = ceil($total / $limite);

// garantir que página não ultrapasse o máximo
if ($pagina > $total_paginas && $total_paginas > 0) {
    $pagina = $total_paginas;
    $offset = ($pagina - 1) * $limite;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lista completa de músicas de karaokê">
    <title>Todas as músicas - Karaokê</title>

    <style>
        body { font-family: Arial; background:#f5f6fa; margin:0; }
        header { background:#2f3640; color:white; padding:15px; text-align:center; }

        .container {
            max-width:800px;
            margin:30px auto;
            background:white;
            padding:20px;
        }

        .menu a {
            margin-right:10px;
            padding:8px;
            background:#00b894;
            color:white;
            text-decoration:none;
        }

        .item {
            padding:10px;
            border-bottom:1px solid #eee;
            cursor:pointer;
            display: grid;
            grid-template-columns: minmax(80px, 80px) 1fr minmax(100px, auto);
            gap: 15px;
            align-items: center;
            font-size: 13px;
        }

        .item:hover {
            background:#f1f2f6;
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

        .paginacao {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin: 20px 0;
            padding: 15px;
            background: #f5f6fa;
            border-radius: 8px;
        }

        .paginacao a, .paginacao span, .paginacao button {
            text-decoration: none;
            padding: 8px 12px;
            background: #dfe6e9;
            border: 1px solid #b2bec3;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .paginacao a:hover, .paginacao button:hover {
            background: #b2bec3;
            color: white;
        }

        .paginacao .ativo {
            background: #00b894;
            color: white;
            border-color: #00b894;
            font-weight: bold;
        }

        .paginacao .separador {
            background: none;
            border: none;
            cursor: default;
            padding: 0 3px;
        }

        .paginacao .separador:hover {
            background: none;
        }

        .info-paginacao {
            text-align: center;
            margin: 10px 0;
            font-size: 13px;
            color: #2f3640;
            font-weight: 600;
        }

        .controls-paginacao {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .controls-paginacao label {
            font-weight: 600;
            color: #2f3640;
        }

        .controls-paginacao select {
            padding: 8px 12px;
            border: 1px solid #b2bec3;
            border-radius: 5px;
            background: white;
            cursor: pointer;
            font-size: 14px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 15px;
            }

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
            header {
                padding: 10px;
                font-size: 18px;
            }

            .container {
                margin: 10px;
                padding: 10px;
            }

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

            .paginacao a, .paginacao span, .paginacao button {
                padding: 6px 8px;
                font-size: 12px;
                margin: 2px;
            }

            .controls-paginacao {
                flex-direction: column;
                gap: 10px;
            }

            .controls-paginacao label {
                display: block;
                margin-bottom: 5px;
            }

            .controls-paginacao select {
                width: 100%;
            }

            .info-paginacao {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>



 <?php include 'layout_topo.php'; ?>

<h2>Todas as músicas</h2>

<div class="item-header">
    <span class="header-codigo">Código</span>
    <span class="header-artista">Artista</span>
    <span class="header-musica">Música</span>
</div>

<?php

$sql = "
SELECT codigo, musica, artista
FROM musicas_karaoke
ORDER BY artista, musica
LIMIT $limite OFFSET $offset;
";

$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {
    echo "<div class='item'
        onclick=\"window.open('telao.php?codigo={$row['codigo']}', '_blank')\">";

    echo "<span class='codigo'>{$row['codigo']}</span>";
    echo "<span class='artista'>{$row['artista']}</span>";
    echo "<span class='musica'>{$row['musica']}</span>";

    echo "</div>";
}
?>

<hr>

<!-- Controles de paginação -->
<div class="controls-paginacao">
    <form method="get" style="display: inline;">
        <label for="limite">Itens por página:</label>
        <select id="limite" name="limite" onchange="this.form.submit()">
            <option value="10" <?php echo $limite == 10 ? 'selected' : ''; ?>>10 itens</option>
            <option value="20" <?php echo $limite == 20 ? 'selected' : ''; ?>>20 itens</option>
            <option value="50" <?php echo $limite == 50 ? 'selected' : ''; ?>>50 itens</option>
        </select>
        <input type="hidden" name="p" value="1">
    </form>
</div>

<!-- Informação de página -->
<div class="info-paginacao">
    <?php 
    $inicio = ($pagina - 1) * $limite + 1;
    $fim = min($pagina * $limite, $total);
    echo "Exibindo $inicio a $fim de $total músicas | Página $pagina de $total_paginas";
    ?>
</div>

<!-- Paginação -->
<div class="paginacao">
    <?php
    // Botão anterior
    if ($pagina > 1) {
        echo "<a href='todas.php?p=" . ($pagina - 1) . "&limite=$limite'>← Anterior</a>";
    } else {
        echo "<span style='color: #95a5a6;'>← Anterior</span>";
    }

    // Intervalo de páginas
    $intervalo = 5;
    $inicio_intervalo = max(1, $pagina - $intervalo);
    $fim_intervalo = min($total_paginas, $pagina + $intervalo);

    // Primeiro bloco se começar depois de 1
    if ($inicio_intervalo > 1) {
        echo "<a href='todas.php?p=1&limite=$limite'>1</a>";
        if ($inicio_intervalo > 2) {
            echo "<span class='separador'>...</span>";
        }
    }

    // Páginas do intervalo
    for ($i = $inicio_intervalo; $i <= $fim_intervalo; $i++) {
        if ($i == $pagina) {
            echo "<span class='ativo'>$i</span>";
        } else {
            echo "<a href='todas.php?p=$i&limite=$limite'>$i</a>";
        }
    }

    // Último bloco se terminar antes de $total_paginas
    if ($fim_intervalo < $total_paginas) {
        if ($fim_intervalo < $total_paginas - 1) {
            echo "<span class='separador'>...</span>";
        }
        echo "<a href='todas.php?p=$total_paginas&limite=$limite'>$total_paginas</a>";
    }

    // Botão próximo
    if ($pagina < $total_paginas) {
        echo "<a href='todas.php?p=" . ($pagina + 1) . "&limite=$limite'>Próximo →</a>";
    } else {
        echo "<span style='color: #95a5a6;'>Próximo →</span>";
    }
    ?>
</div>

</div>
</body>
</html>