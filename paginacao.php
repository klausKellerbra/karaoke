<?php

function getCurrentPage(): int {
    $pagina = intval($_GET['p'] ?? 1);
    return $pagina < 1 ? 1 : $pagina;
}

function getLimit(array $allowed = [10, 20, 50], int $default = 20): int {
    $limite = intval($_GET['limite'] ?? $default);
    return in_array($limite, $allowed, true) ? $limite : $default;
}

function buildQuery(array $params = []): string {
    $query = $_GET;

    foreach ($params as $key => $value) {
        $query[$key] = $value;
    }

    return http_build_query($query);
}

function renderLimitControl(int $limite, array $options = [10, 20, 50]): void {
    if (!$options || count($options) === 0) {
        return;
    }

    echo '<div class="controls-paginacao">';
    echo '<form method="get" style="display:inline-flex; align-items:center; gap:12px; flex-wrap:wrap;">';
    echo '<label for="limite">Itens por página:</label>';
    echo '<select id="limite" name="limite" onchange="this.form.submit()">';

    foreach ($options as $opcao) {
        $selected = $opcao === $limite ? 'selected' : '';
        echo "<option value=\"$opcao\" $selected>$opcao itens</option>";
    }

    echo '</select>';
    echo '<input type="hidden" name="p" value="1">';

    foreach ($_GET as $key => $value) {
        if ($key === 'p' || $key === 'limite') {
            continue;
        }
        $escapedKey = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
        $escapedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        echo "<input type=\"hidden\" name=\"$escapedKey\" value=\"$escapedValue\">";
    }

    echo '</form>';
    echo '</div>';
}

function renderPagination(int $pagina, int $totalPaginas): void {
    if ($totalPaginas <= 1) {
        return;
    }

    echo '<div class="paginacao">';

    if ($pagina > 1) {
        echo '<a href="?' . buildQuery(['p' => $pagina - 1]) . '">← Anterior</a>';
    } else {
        echo '<span>← Anterior</span>';
    }

    $intervalo = 5;
    $inicioIntervalo = max(1, $pagina - $intervalo);
    $fimIntervalo = min($totalPaginas, $pagina + $intervalo);

    if ($inicioIntervalo > 1) {
        echo '<a href="?' . buildQuery(['p' => 1]) . '">1</a>';
        if ($inicioIntervalo > 2) {
            echo '<span class="separador">...</span>';
        }
    }

    for ($i = $inicioIntervalo; $i <= $fimIntervalo; $i++) {
        if ($i === $pagina) {
            echo '<span class="ativo">' . $i . '</span>';
        } else {
            echo '<a href="?' . buildQuery(['p' => $i]) . '">' . $i . '</a>';
        }
    }

    if ($fimIntervalo < $totalPaginas) {
        if ($fimIntervalo < $totalPaginas - 1) {
            echo '<span class="separador">...</span>';
        }
        echo '<a href="?' . buildQuery(['p' => $totalPaginas]) . '">' . $totalPaginas . '</a>';
    }

    if ($pagina < $totalPaginas) {
        echo '<a href="?' . buildQuery(['p' => $pagina + 1]) . '">Próximo →</a>';
    } else {
        echo '<span>Próximo →</span>';
    }

    echo '</div>';
}

function renderPaginationInfo(int $pagina, int $limite, int $total): void {
    if ($total <= $limite) {
        return;
    }

    $inicio = ($pagina - 1) * $limite + 1;
    $fim = min($pagina * $limite, $total);
    $totalPaginas = max(1, (int) ceil($total / $limite));

    echo '<div class="info-paginacao">';
    echo "Exibindo $inicio a $fim de $total itens | Página $pagina de $totalPaginas";
    echo '</div>';
}
