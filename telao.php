<?php
include 'conexao.php';
include 'youtube_helper.php';

$codigo = $_POST['codigo'] ?? $_GET['codigo'] ?? '';

if (!$codigo) {
    die("Código não fornecido");
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    if ($_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Erro no upload do arquivo. Tente novamente.';
    } else {
        $arquivoTmp = $_FILES['audio']['tmp_name'];
        $nomeArquivo = $_FILES['audio']['name'];
        $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

        if ($extensao !== 'mp3') {
            $error = 'Apenas arquivos MP3 são permitidos.';
        } else {
            $audioConteudo = file_get_contents($arquivoTmp);
            $audioEscaped = pg_escape_bytea($conn, $audioConteudo);

            $updateSql = "UPDATE musicas_karaoke SET audio = $1 WHERE codigo = $2";
            $updateResult = pg_query_params($conn, $updateSql, [$audioEscaped, $codigo]);

            if ($updateResult) {
                $message = 'Áudio carregado com sucesso.';
            } else {
                $error = 'Falha ao salvar o áudio na tabela.';
            }
        }
    }
}

$sql = "SELECT * FROM musicas_karaoke WHERE codigo = $1 LIMIT 1";
$result = pg_query_params($conn, $sql, [$codigo]);

if (!$result) {
    die("Erro ao buscar música");
}

$row = pg_fetch_assoc($result);

if (!$row) {
    die("Música não encontrada");
}

// Buscar no YouTube (com cache) - com tratamento de erro
try {
    $youtube = buscarYouTubeComCache($conn, $codigo, $row['artista'], $row['musica']);
    if ($youtube && is_array($youtube)) {
        $row = array_merge($row, $youtube);
    }
} catch (Exception $e) {
    // Se houver erro, continua sem YouTube
    error_log("YouTube Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Telão de Karaoke - Exibição de músicas para karaokê">
    <meta name="og:title" content="Telão Karaokê - <?php echo htmlspecialchars($row['musica']); ?>">
    <meta name="og:description" content="Artista: <?php echo htmlspecialchars($row['artista']); ?>">
    <meta name="og:type" content="website">
    <meta name="theme-color" content="#000000">
    <title>Telão 🎤 - <?php echo htmlspecialchars($row['musica']); ?></title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            background: black;
            color: white;
            text-align: center;
            font-family: Arial;
            margin: 0;
            padding: 0;
        }

        .codigo {
            font-size: 150px;
            font-weight: bold;
            margin-top: 100px;
        }

        .musica {
            font-size: 40px;
            margin-top: 20px;
        }

        .artista {
            font-size: 30px;
            color: #ccc;
            margin-top: 20px;
        }

        .artista a {
            color: #00b894;
            text-decoration: none;
            cursor: pointer;
        }

        .artista a:hover {
            text-decoration: underline;
            color: #00ff00;
        }

        .biografia {
            font-size: 18px;
            margin-top: 40px;
        }

        .biografia a {
            color: #ffd700;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #ffd700;
            border-radius: 5px;
            display: inline-block;
            margin: 5px;
        }

        .biografia a:hover {
            background-color: #ffd700;
            color: black;
            cursor: pointer;
        }

        .youtube-link {
            font-size: 18px;
            margin-top: 20px;
        }

        .youtube-link a {
            color: #ff0000;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #ff0000;
            border-radius: 5px;
            display: inline-block;
            margin: 5px;
            background-color: rgba(255, 0, 0, 0.1);
        }

        .youtube-link a:hover {
            background-color: #ff0000;
            color: white;
            cursor: pointer;
        }

        .upload-form {
            margin-top: 30px;
            padding: 20px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            text-align: left;
            max-width: 420px;
        }

        .upload-form label {
            display: block;
            margin-bottom: 10px;
            color: #ffffff;
            font-size: 16px;
        }

        .upload-form input[type="file"] {
            display: block;
            width: 100%;
            margin-bottom: 12px;
        }

        .upload-form button {
            padding: 10px 18px;
            background: #00b894;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .upload-form button:hover {
            background: #00a085;
        }

        .upload-status {
            margin-top: 15px;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            font-size: 14px;
        }

        .upload-status.success {
            background: rgba(46, 204, 113, 0.18);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .upload-status.error {
            background: rgba(231, 76, 60, 0.18);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .audio-player {
            margin-top: 20px;
        }

        .audio-player audio {
            width: 100%;
            max-width: 420px;
        }

        .youtube-video {
            margin-top: 50px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .youtube-video iframe {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .youtube-info {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
            text-align: center;
        }

        /* Responsividade para dispositivos móveis */
        @media (max-width: 768px) {
            .codigo {
                font-size: 80px;
                margin-top: 50px;
            }

            .musica {
                font-size: 24px;
                margin-top: 15px;
            }

            .artista {
                font-size: 20px;
                margin-top: 15px;
            }

            .biografia {
                font-size: 16px;
                margin-top: 30px;
            }

            .biografia a {
                padding: 8px 16px;
                font-size: 14px;
            }

            .youtube-link {
                font-size: 16px;
                margin-top: 15px;
            }

            .youtube-link a {
                padding: 8px 16px;
                font-size: 12px;
            }

            .youtube-video {
                margin-top: 30px;
            }

            .youtube-video iframe {
                max-width: 100%;
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .codigo {
                font-size: 60px;
                margin-top: 30px;
            }

            .musica {
                font-size: 18px;
                margin-top: 10px;
            }

            .artista {
                font-size: 16px;
                margin-top: 10px;
            }

            .biografia {
                font-size: 14px;
                margin-top: 20px;
            }

            .biografia a {
                padding: 6px 12px;
                font-size: 12px;
            }

            .youtube-link {
                font-size: 14px;
                margin-top: 10px;
            }

            .youtube-link a {
                padding: 6px 12px;
                font-size: 11px;
            }

            .youtube-video {
                margin-top: 20px;
            }

            .youtube-video iframe {
                max-width: 100%;
                height: 200px;
            }

            .youtube-info {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<div class="codigo"><?php echo $row['codigo_exibicao']; ?></div>
<div class="musica"><?php echo $row['musica']; ?></div>
<div class="artista">
    <a href="buscar.php?q=<?php echo urlencode($row['artista']); ?>" target="_blank">
        <?php echo $row['artista']; ?>
    </a>
</div>

<div class="biografia">
    <a href="https://pt.wikipedia.org/w/index.php?title=Especial:Pesquisar&search=<?php echo urlencode($row['artista']); ?>&go=Ir" target="_blank">
        📖 Ver Biografia & Discografia
    </a>
</div>

<div class="youtube-link">
    <a href="https://www.youtube.com/results?search_query=<?php echo urlencode($row['artista'] . ' ' . $row['musica']); ?>" target="_blank">
        ▶️ Ver no YouTube
    </a>
</div>

<div style="margin-top:15px;">
    <button type="button" class="fav-btn" data-codigo="<?php echo htmlspecialchars($row['codigo']); ?>" aria-label="Adicionar aos favoritos" aria-pressed="false"></button>
</div>

<?php if (!empty($message)): ?>
    <div class="upload-status success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="upload-status error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="upload-form">
    <form method="post" enctype="multipart/form-data" action="telao.php?codigo=<?php echo urlencode($codigo); ?>">
        <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($codigo); ?>">
        <label for="audio">Enviar MP3 da música selecionada:</label>
        <input type="file" id="audio" name="audio" accept="audio/mpeg,.mp3" required>
        <button type="submit">Carregar MP3</button>
    </form>
</div>

<?php if (!empty($row['audio'])): ?>
    <div class="audio-player">
        <audio controls>
            <source src="audio.php?codigo=<?php echo urlencode($codigo); ?>" type="audio/mpeg">
            Seu navegador não suporta reprodução de áudio.
        </audio>
    </div>
<?php endif; ?>

<?php if ($row['youtube_id']): ?>
<div class="youtube-video">
    <?php echo getYouTubeEmbed($row['youtube_id']); ?>
    <div class="youtube-info">
        <strong><?php echo htmlspecialchars($row['youtube_titulo']); ?></strong>
        <br>
        Canal: <?php echo htmlspecialchars($row['youtube_canal']); ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>