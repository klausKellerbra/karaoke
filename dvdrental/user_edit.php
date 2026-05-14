<?php
$host = "localhost";
$db   = "portal_db";
$user = "klaus";
$pass = "031177";

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID inválido");
}

// Atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = $_POST['name']  ?? '';
    $email = $_POST['email'] ?? '';
    $role  = $_POST['role']  ?? 'user';

    $stmt = $pdo->prepare("
        UPDATE users
        SET name = :name, email = :email, role = :role
        WHERE id = :id
    ");
    $stmt->execute([
        ':name'  => $nome,
        ':email' => $email,
        ':role'  => $role,
        ':id'    => $id
    ]);

    header("Location: mysql.php");
    exit;
}

// Carregar usuário
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$u = $stmt->fetch();

if (!$u) {
    die("Usuário não encontrado");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Usuário</title>
</head>
<body>

<h1>Editar Usuário</h1>

<form method="post">
    Nome:<br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>"><br><br>

    Email:<br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>"><br><br>

    Perfil:<br>
    <select name="role">
        <option value="admin" <?php if ($u['role']==='admin') echo 'selected'; ?>>Admin</option>
        <option value="user"  <?php if ($u['role']==='user')  echo 'selected'; ?>>User</option>
    </select><br><br>

    <button type="submit">Salvar</button>
    <a href="mysql.php">Cancelar</a>
</form>

</body>
</html>
