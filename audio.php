<?php
include 'conexao.php';

$codigo = $_GET['codigo'] ?? '';
if (!$codigo) {
    http_response_code(400);
    echo 'Código não fornecido.';
    exit;
}

$sql = "SELECT audio FROM musicas_karaoke WHERE codigo = $1 LIMIT 1";
$result = pg_query_params($conn, $sql, [$codigo]);
if (!$result) {
    http_response_code(500);
    echo 'Erro ao buscar áudio.';
    exit;
}

$row = pg_fetch_assoc($result);
if (!$row || empty($row['audio'])) {
    http_response_code(404);
    echo 'Áudio não encontrado.';
    exit;
}

$audio = pg_unescape_bytea($row['audio']);
header('Content-Type: audio/mpeg');
header('Content-Length: ' . strlen($audio));
header('Cache-Control: no-cache, no-store, must-revalidate');
echo $audio;
exit;
