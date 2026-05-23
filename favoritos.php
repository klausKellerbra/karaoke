<?php
include 'conexao.php';

// Ler cookie de favoritos (espera um JSON com array de códigos)
$raw = $_COOKIE['karaoke_favs'] ?? '';
$favs = [];
if ($raw) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) $favs = $decoded;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Favoritos - Karaokê</title>
    <link rel="stylesheet" href="styles.css">
    <?php include 'layout_topo.php'; ?>
    <style>
        .fav-list { max-width:800px; margin:20px auto; }
        .fav-item { display:flex; align-items:center; gap:12px; padding:10px; border-bottom:1px solid #eee; background:white; }
        .fav-meta { flex:1; }
        .fav-actions { display:flex; gap:8px; }
        .clear-btn { background:#e74c3c; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; }
        .clear-btn:focus { outline:3px solid #ffb3b3; }
    </style>
</head>
<body>

<div class="container fav-list">
    <h2>Favoritos</h2>
    <div style="margin-bottom:10px;">
        <button id="clearFavorites" class="clear-btn">Limpar todos os favoritos</button>
    </div>

    <?php if (empty($favs)): ?>
        <p>Nenhum favorito encontrado. Marque músicas com o coração.</p>
    <?php else: ?>

        <?php
        // Buscar informações das músicas por código
        foreach ($favs as $code) {
            $code = trim($code);
            if ($code === '') continue;
            $res = pg_query_params($conn, "SELECT codigo, musica, artista FROM musicas_karaoke WHERE codigo = $1 LIMIT 1", [$code]);
            if (!$res) continue;
            $row = pg_fetch_assoc($res);
            if (!$row) continue;
            ?>
            <div class="fav-item" id="fav-<?php echo htmlspecialchars($row['codigo']); ?>">
                <div class="fav-actions">
                    <button type="button" class="fav-btn" data-codigo="<?php echo htmlspecialchars($row['codigo']); ?>" aria-label="Remover favorito" aria-pressed="true"></button>
                </div>
                <div class="fav-meta">
                    <strong><?php echo htmlspecialchars($row['musica']); ?></strong>
                    <div><?php echo htmlspecialchars($row['artista']); ?></div>
                    <div style="color:#666; font-size:13px;">Código: <?php echo htmlspecialchars($row['codigo']); ?></div>
                </div>
                <div>
                    <a href="telao.php?codigo=<?php echo urlencode($row['codigo']); ?>" target="_blank">Abrir Telão</a>
                </div>
            </div>
        <?php } ?>

    <?php endif; ?>

</div>

<script>
document.getElementById('clearFavorites').addEventListener('click', function(){
    // remover cookie e recarregar
    document.cookie = 'karaoke_favs=; Max-Age=0; path=/';
    location.reload();
});

// Remover item ao clicar no botão de favorito (delegação será feita por favorites.js)
document.body.addEventListener('click', function(e){
    const btn = e.target.closest('.fav-btn');
    if (!btn) return;
    const code = btn.getAttribute('data-codigo');
    // use a API pública criada em favorites.js
    if (window.KaraokeFavorites){
        window.KaraokeFavorites.toggle(code);
    }
    const el = document.getElementById('fav-' + code);
    if (el) el.remove();
});
</script>

</body>
</html>
