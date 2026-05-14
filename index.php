<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karaokê 🎤</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f6fa;
        }

        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
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

        /* Responsividade */
        @media (max-width: 480px) {
            form {
                margin: 20px;
                padding: 15px;
            }

            input[type="text"], button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

 <?php include 'layout_topo.php'; ?>


<form action="buscar.php" method="get">
    <input type="text" name="q" placeholder="Digite música ou artista">
    <button type="submit">Buscar</button>
</form>


</body>
</html>