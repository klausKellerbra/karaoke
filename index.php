<?php
include 'conexao.php';

$totalMusicas = 0;
$totalArtistas = 0;
$totalCategorias = 0;

$result = pg_query($conn, 'SELECT COUNT(*) FROM musicas_karaoke');
if ($result) {
    $totalMusicas = (int) pg_fetch_result($result, 0, 0);
}

$result = pg_query($conn, "SELECT COUNT(DISTINCT artista_normalizado) FROM musicas_karaoke WHERE artista_normalizado IS NOT NULL AND artista_normalizado != ''");
if ($result) {
    $totalArtistas = (int) pg_fetch_result($result, 0, 0);
}

$result = pg_query($conn, "SELECT COUNT(DISTINCT categoria) FROM musicas_karaoke WHERE categoria IS NOT NULL AND categoria != ''");
if ($result) {
    $totalCategorias = (int) pg_fetch_result($result, 0, 0);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karaokê 🎤</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'layout_topo.php'; ?>

    <main class="container home-panel">
        <section class="hero-box">
            <div>
                <p class="eyebrow">Sistema de karaokê</p>
                <h1>Organize, encontre e exiba suas músicas com facilidade.</h1>
                <p class="hero-text">Busque por cantores, títulos, categorias e acesse rapidamente o telão para apresentação.</p>
            </div>

            <form action="buscar.php" method="get" class="search-home">
                <label for="q" class="sr-only">Buscar música ou artista</label>
                <input id="q" type="text" name="q" placeholder="Digite música ou artista...">
                <button type="submit">Buscar</button>
            </form>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <span class="stat-label">Músicas</span>
                <strong><?php echo number_format($totalMusicas, 0, ',', '.'); ?></strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Artistas</span>
                <strong><?php echo number_format($totalArtistas, 0, ',', '.'); ?></strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Categorias</span>
                <strong><?php echo number_format($totalCategorias, 0, ',', '.'); ?></strong>
            </article>
        </section>

        <section class="shortcut-grid">
            <a class="shortcut-card" href="todas.php">
                <span>🎵</span>
                <strong>Todas as músicas</strong>
                <small>Lista completa com paginação.</small>
            </a>
            <a class="shortcut-card" href="artista.php">
                <span>👤</span>
                <strong>Artistas</strong>
                <small>Pesquisa rápida por artista.</small>
            </a>
            <a class="shortcut-card" href="favoritos.php">
                <span>⭐</span>
                <strong>Favoritos</strong>
                <small>Sua seleção em um clique.</small>
            </a>
        </section>
    </main>
</body>
</html>
