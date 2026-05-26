<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        .busca-artista {
            max-width: 400px;
            margin: 0 auto 20px;
            padding: 10px;
        }

        .busca-artista input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .busca-artista input:focus {
            outline: none;
            border-color: #00b894;
            box-shadow: 0 0 5px rgba(0, 184, 148, 0.3);
        }

        .item-artista {
            padding: 4px 8px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            background: white;
            margin: 0;
            border-radius: 3px;
            transition: background 0.3s;
            display: block;
            font-size: 13px;
            min-height: 22px;
            line-height: 22px;
        }

        .item-artista:nth-child(even) {
            background: #f5f5f5;
        }

        .item-artista:nth-child(odd) {
            background: #ffffff;
        }

        .item-artista:hover {
            background: #f1f2f6;
        }

        .item-artista.hidden {
            display: none;
        }

        .busca-resultado {
            text-align: center;
            font-size: 13px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>

 <?php include 'layout_topo.php'; ?>

<h2>👤 Artistas</h2>

<div class="busca-artista">
    <input type="text" id="buscaArtista" placeholder="Digite o nome do artista...">
    <div class="busca-resultado" id="resultadoBusca"></div>
</div>

<?php
$sql = "SELECT DISTINCT artista_normalizado FROM musicas_karaoke WHERE artista_normalizado IS NOT NULL AND artista_normalizado != ''and   idioma= 'en'  ORDER BY artista_normalizado";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {

    $artista = urlencode($row['artista_normalizado']);

    echo "<div class='item-artista'
            onclick=\"window.location='buscar_artista.php?art=$artista'\">";

    echo htmlspecialchars($row['artista_normalizado']);

    echo "</div>";
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputBusca = document.getElementById('buscaArtista');
    const artistas = document.querySelectorAll('.item-artista');
    const resultadoBusca = document.getElementById('resultadoBusca');

    inputBusca.addEventListener('input', function() {
        const termo = this.value.toLowerCase().trim();
        let visibles = 0;

        artistas.forEach(artista => {
            const nome = artista.textContent.toLowerCase();
            
            if (nome.includes(termo) || termo === '') {
                artista.classList.remove('hidden');
                visibles++;
            } else {
                artista.classList.add('hidden');
            }
        });

        // Mostrar mensagem de resultado
        if (termo !== '') {
            resultadoBusca.textContent = visibles + ' artista(s) encontrado(s)';
        } else {
            resultadoBusca.textContent = '';
        }
    });
});
</script>

</div>
</body>
</html>
