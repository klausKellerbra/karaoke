<?php
/**
 * YouTube Helper - Integração com YouTube Data API v3
 * Busca e cacheia informações de vídeos do YouTube
 * Versão sem CURL (usando file_get_contents)
 */

define('YOUTUBE_API_KEY', 'AIzaSyAOb5TVS9VquqNwX4F4bevPRwLh0RIXAxY');

/**
 * Busca uma música no YouTube
 * 
 * @param string $artista Nome do artista
 * @param string $musica Nome da música
 * @return array|null Array com id, titulo, thumbnail, canal ou null se não encontrar
 */
function buscarNoYouTube($artista, $musica) {
    try {
        // Montar query de busca
        $query = urlencode("$artista $musica karaoke");
        
        // URL da API
        $url = "https://www.googleapis.com/youtube/v3/search";
        $params = [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'maxResults' => 5,
            'order' => 'relevance',
            'key' => YOUTUBE_API_KEY
        ];
        
        $url = $url . '?' . http_build_query($params);
        
        // Fazer requisição com file_get_contents
        $context = stream_context_create([
            'http' => [
                'timeout' => 10
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            error_log("YouTube API Error: Could not fetch URL");
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("YouTube JSON Error: " . json_last_error_msg());
            return null;
        }
        
        // Verificar se encontrou resultados
        if (!empty($data['items'])) {
            $video = $data['items'][0];
            
            return [
                'id' => $video['id']['videoId'],
                'titulo' => $video['snippet']['title'],
                'thumbnail' => $video['snippet']['thumbnails']['medium']['url'],
                'thumbnail_high' => $video['snippet']['thumbnails']['high']['url'] ?? $video['snippet']['thumbnails']['medium']['url'],
                'canal' => $video['snippet']['channelTitle'],
                'descricao' => $video['snippet']['description'],
                'publicado_em' => $video['snippet']['publishedAt']
            ];
        }
        
        return null;
        
    } catch (Exception $e) {
        error_log("YouTube API Exception: " . $e->getMessage());
        return null;
    }
}

/**
 * Buscar e cachear no banco de dados
 * Se já existir no banco, retorna o valor em cache
 * Se não existir, busca na API e atualiza o banco
 * 
 * @param object $conn Conexão PostgreSQL
 * @param string $codigo Código da música
 * @param string $artista Nome do artista
 * @param string $musica Nome da música
 * @return array|null Dados do YouTube
 */
function buscarYouTubeComCache($conn, $codigo, $artista, $musica) {
    // Verificar se as colunas existem (fallback seguro)
    $result = @pg_query_params($conn, 
        "SELECT youtube_id, youtube_titulo, youtube_thumbnail, youtube_canal FROM musicas_karaoke WHERE codigo = $1 LIMIT 1",
        [$codigo]
    );
    
    if ($result) {
        $row = pg_fetch_assoc($result);
        if ($row && !empty($row['youtube_id'])) {
            // Já existe em cache
            return [
                'id' => $row['youtube_id'],
                'titulo' => $row['youtube_titulo'],
                'thumbnail' => $row['youtube_thumbnail'],
                'canal' => $row['youtube_canal'],
                'cached' => true
            ];
        }
    } else {
        // Colunas não existem ainda, retorna null sem erro
        return null;
    }
    
    // Se não tiver, busca na API
    $youtube = buscarNoYouTube($artista, $musica);
    
    if ($youtube) {
        // Tentar armazenar no banco (cache)
        // Usar @ para suprimir erro se colunas não existem
        @pg_query_params($conn,
            "UPDATE musicas_karaoke SET youtube_id = $1, youtube_titulo = $2, youtube_thumbnail = $3, youtube_canal = $4 WHERE codigo = $5",
            [$youtube['id'], $youtube['titulo'], $youtube['thumbnail'], $youtube['canal'], $codigo]
        );
        
        $youtube['cached'] = false;
    }
    
    return $youtube;
}

/**
 * Obter URL do vídeo
 * @param string $videoId ID do vídeo
 * @return string URL do vídeo no YouTube
 */
function getYouTubeURL($videoId) {
    return "https://www.youtube.com/watch?v=" . $videoId;
}

/**
 * Obter URL do iframe embedado
 * @param string $videoId ID do vídeo
 * @return string Código iframe
 */
function getYouTubeEmbed($videoId, $width = "100%", $height = "400") {
    return '<iframe width="' . $width . '" height="' . $height . '" 
            src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" 
            title="YouTube video player" frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
            </iframe>';
}
?>

