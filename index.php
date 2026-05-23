<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karaokê 🎤</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

 <?php include 'layout_topo.php'; ?>


<form action="buscar.php" method="get">
    <input type="text" name="q" placeholder="Digite música ou artista">
    <button type="submit">Buscar</button>
</form>


</body>
</html>