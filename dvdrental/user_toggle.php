<?php
$host = "localhost";
$db   = "portal_db";
$user = "klaus";
$pass = "031177";

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$id     = $_GET['id']     ?? null;
$status = $_GET['status'] ?? null;

if (!is_numeric($id) || !in_array($status, ['0','1'])) {
    die("Parâmetros inválidos");
}

$stmt = $pdo->prepare("
    UPDATE users SET active = :active WHERE id = :id
");

$stmt->execute([
    ':active' => $status,
    ':id'     => $id
]);

header("Location: mysql.php");
exit;