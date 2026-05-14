<?php
/**
 * Script de Setup para YouTube Integration
 * Execute uma vez em: http://localhost/setup_youtube.php
 */

include 'conexao.php';

echo "<h1>🎬 Configurando YouTube Integration</h1>";

// Adicionar coluna youtube_id
$result = pg_query($conn, "
    ALTER TABLE musicas_karaoke 
    ADD COLUMN IF NOT EXISTS youtube_id VARCHAR(255)
");

if ($result) {
    echo "✅ Coluna youtube_id criada<br>";
} else {
    echo "❌ Erro ao criar youtube_id: " . pg_last_error() . "<br>";
}

// Adicionar coluna youtube_titulo
$result = pg_query($conn, "
    ALTER TABLE musicas_karaoke 
    ADD COLUMN IF NOT EXISTS youtube_titulo VARCHAR(255)
");

if ($result) {
    echo "✅ Coluna youtube_titulo criada<br>";
} else {
    echo "❌ Erro ao criar youtube_titulo: " . pg_last_error() . "<br>";
}

// Adicionar coluna youtube_thumbnail
$result = pg_query($conn, "
    ALTER TABLE musicas_karaoke 
    ADD COLUMN IF NOT EXISTS youtube_thumbnail VARCHAR(500)
");

if ($result) {
    echo "✅ Coluna youtube_thumbnail criada<br>";
} else {
    echo "❌ Erro ao criar youtube_thumbnail: " . pg_last_error() . "<br>";
}

// Adicionar coluna youtube_canal
$result = pg_query($conn, "
    ALTER TABLE musicas_karaoke 
    ADD COLUMN IF NOT EXISTS youtube_canal VARCHAR(255)
");

if ($result) {
    echo "✅ Coluna youtube_canal criada<br>";
} else {
    echo "❌ Erro ao criar youtube_canal: " . pg_last_error() . "<br>";
}

echo "<hr>";
echo "✅ <strong>Setup completo!</strong>";
echo "<br><br>";
echo "Próximo passo: Acesse <a href='telao.php?codigo=0001'>telao.php?codigo=0001</a>";
echo "<br>";
echo "<small>Obs: A primeira música pode demorar alguns segundos pois vai buscar no YouTube</small>";
?>
