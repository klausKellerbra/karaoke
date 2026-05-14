<!DOCTYPE html>
<html>
<head>
    <title>Karaokê 🎤</title>

    <style>
        body { font-family: Arial; background:#f5f6fa; margin:0; }

        header {
            background:#2f3640;
            color:white;
            padding:15px;
            text-align:center;
        }

        .container {
            max-width:800px;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:10px;
        }

        .menu a {
            margin-right:10px;
            padding:8px;
            background:#00b894;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 15px;
            }

            .menu a {
                display: block;
                margin: 5px 0;
                text-align: center;
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

            .menu a {
                padding: 6px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>🎤 Karaokê</header>

<div class="container">

<div class="menu">
    <a href="index.php">🏠 Início</a>
    <a href="categoria.php">📂 Categoria</a>
    <a href="genero.php">🎸 Gênero</a>
    <a href="idioma.php">🌎 Idioma</a>
    <a href="todas.php">🎵 Todas</a>
</div>

<hr>