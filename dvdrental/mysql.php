<?php
/* =========================
   CONFIGURAÇÃO DO BANCO
========================= */
$host = "localhost";
$db   = "portal_db";
$user = "klaus";
$pass = "031177";

/* =========================
   CONEXÃO PDO
========================= */
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erro ao conectar ao banco");
}

/* =========================
   ENTRADAS (FILTROS/BUSCA)
========================= */
$filtroBusca  = $_GET['search'] ?? '';
$filtroRole   = $_GET['role']   ?? '';
$filtroStatus = $_GET['status'] ?? '';

$where  = [];
$params = [];

/* Busca por nome ou email */
if ($filtroBusca !== '') {
    $where[] = "(name LIKE :search OR email LIKE :search)";
    $params[':search'] = '%' . $filtroBusca . '%';
}

/* Filtro por perfil */
if ($filtroRole !== '') {
    $where[] = "role = :role";
    $params[':role'] = $filtroRole;
}

/* Filtro por status */
if ($filtroStatus !== '') {
    $where[] = "active = :active";
    $params[':active'] = $filtroStatus;
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

/* =========================
   PAGINAÇÃO
========================= */
$porPagina = 10;
$paginaAtual = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0)
    ? (int) $_GET['page']
    : 1;

$offset = ($paginaAtual - 1) * $porPagina;

/* =========================
   TOTAL DE REGISTROS
========================= */
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM users $whereSql");
$stmtTotal->execute($params);
$totalUsuarios = (int) $stmtTotal->fetchColumn();
$totalPaginas  = max(1, ceil($totalUsuarios / $porPagina));

/* =========================
   CONSULTA PRINCIPAL
========================= */
$sql = "
    SELECT id, name, email, role, active, created_at
    FROM users
    $whereSql
    ORDER BY id
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}

$stmt->bindValue(':limit',  $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$users = $stmt->fetchAll();

/* =========================
   FUNÇÕES AUXILIARES
========================= */
function buildQuery(array $extra): string {
    return http_build_query(array_merge($_GET, $extra));
}

function linkPagina(int $pagina, string $texto): string {
    $url = '?' . buildQuery(['page' => $pagina]);
    return '<a href="' . htmlspecialchars($url) . '">' . $texto . '</a>';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Usuários</title>
<style>
body { font-family: Arial, sans-serif; margin: 40px; }
table { border-collapse: collapse; width: 100%; margin-top: 15px; }
th, td { border: 1px solid #ccc; padding: 8px; }
th { background: #f2f2f2; }
.ativo { color: green; font-weight: bold; }
.inativo { color: red; font-weight: bold; }
.filters { margin-bottom: 15px; }
.pagination { margin-top: 20px; text-align: center; }
.pagination a, .pagination span {
    margin: 0 4px;
    padding: 6px 10px;
    border: 1px solid #ccc;
    text-decoration: none;
}
.pagination .current {
    background: #333;
    color: #fff;
    font-weight: bold;
}
</style>
</head>

<body>

<h1>Usuários</h1>

<!-- FILTROS / BUSCA -->
<form method="get" class="filters">
    Buscar:
    <input type="text" name="search" value="<?php echo htmlspecialchars($filtroBusca); ?>" placeholder="Nome ou email">

    Perfil:
    <select name="role">
        <option value="">Todos</option>
        <option value="admin" <?php if ($filtroRole==='admin') echo 'selected'; ?>>Admin</option>
        <option value="user"  <?php if ($filtroRole==='user')  echo 'selected'; ?>>User</option>
    </select>

    Status:
    <select name="status">
        <option value="">Todos</option>
        <option value="1" <?php if ($filtroStatus==='1') echo 'selected'; ?>>Ativo</option>
        <option value="0" <?php if ($filtroStatus==='0') echo 'selected'; ?>>Inativo</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<p>Total de usuários: <b><?php echo $totalUsuarios; ?></b></p>

<table>
<thead>
<tr>
    <th>Ações</th>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Perfil</th>
    <th>Status</th>
    <th>Criado em</th>
</tr>
</thead>
<tbody>

<?php if (!$users): ?>
<tr><td colspan="6">Nenhum registro encontrado</td></tr>
<?php else: ?>
<?php foreach ($users as $u): ?>
<tr>
<td>
<?php
    // Link Editar
    echo '<ser_edit.php?id=Editar</a> | ';

    // Link Ativar / Desativar
    if ($u['active'] == 1) {
        echo '<a href="user_toggle.php?id=' . $u['id'] . '&status=0">Desativar</a>';
    } else {
        echo '<a href="user_toggle.php?id=' . $u['id'] . '&status=1">Ativar</a>';
    }
?>
</td>

    <td><?php echo $u['id']; ?></td>
    <td><?php echo htmlspecialchars($u['name']); ?></td>
    <td><?php echo htmlspecialchars($u['email']); ?></td>
    <td><?php echo $u['role']; ?></td>
    <td class="<?php echo $u['active'] ? 'ativo' : 'inativo'; ?>">
        <?php echo $u['active'] ? 'Ativo' : 'Inativo'; ?>
    </td>
    <td><?php echo $u['created_at']; ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

</tbody>
</table>

<!-- PAGINAÇÃO -->
<div class="pagination">
<?php
if ($paginaAtual > 1) {
    echo linkPagina($paginaAtual - 1, '« Anterior');
}

for ($i = 1; $i <= $totalPaginas; $i++) {
    if ($i === $paginaAtual) {
        echo '<span class="current">' . $i . '</span>';
    } else {
        echo linkPagina($i, (string)$i);
    }
}

if ($paginaAtual < $totalPaginas) {
    echo linkPagina($paginaAtual + 1, 'Próxima »');
}
?>
</div>

</body>
</html>