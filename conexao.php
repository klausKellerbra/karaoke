<?php
$conn = pg_connect("
    host=localhost
    dbname=KARAOKE
    user=postgres
    password=031177
");

if (!$conn) {
    echo "Erro na conexão com PostgreSQL";
    exit;
}
?>