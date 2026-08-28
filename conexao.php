<?php
function getConnection()
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME') ?: 'KARAOKE';
    $user = getenv('DB_USER') ?: 'postgres';
    $password = getenv('DB_PASSWORD') ?: '031177';

    $conn = pg_connect(
        sprintf(
            'host=%s port=%s dbname=%s user=%s password=%s',
            $host,
            $port,
            $dbname,
            $user,
            $password
        )
    );

    if (!$conn) {
        return null;
    }

    return $conn;
}

try {
    $conn = getConnection();
    if (!$conn) {
        throw new RuntimeException('Erro ao conectar com o PostgreSQL. Verifique as credenciais e o banco KARAOKE.');
    }
} catch (Throwable $e) {
    http_response_code(500);
    die('<div style="font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #fff3f3; border: 1px solid #f8c4c4; color: #7a1f1f; border-radius: 10px;">' . htmlspecialchars($e->getMessage()) . '</div>');
}
?>