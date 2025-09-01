<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Acompanhamento do Desenvolvimento';
$page_icon = 'fas fa-chart-line';
$breadcrumb = 'Portal do Professor > Acompanhamento > Desenvolvimento';


// --- LÓGICA DO BANCO DE DADOS (Simulação) ---
// $observacoes = $conexao->query("SELECT * FROM desenvolvimento_observacoes")->fetch_all(MYSQLI_ASSOC);
// --- FIM DA LÓGICA ---
?>

<div class="filter-section">
    </div>

<div class="development-grid">
    <div class="development-card">
      <h3><i class="fas fa-running"></i> Desenvolvimento Motor</h3>
      <a href="registrar_observacao.php" class="btn btn-primary" style="margin-top: 15px;">Registrar Observação</a>
    </div>
    <div class="development-card">
      <h3><i class="fas fa-brain"></i> Desenvolvimento Cognitivo</h3>
      <a href="registrar_observacao.php" class="btn btn-primary" style="margin-top: 15px;">Registrar Observação</a>
    </div>
</div>

<div class="table-container">
    <h4><i class="fas fa-history"></i> Observações Recentes</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Criança</th>
                <th>Área</th>
                <th>Habilidade</th>
                <th>Observação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>15/06/2025</td>
                <td>João Pedro</td>
                <td>Motor</td>
                <td>Equilíbrio</td>
                <td>Consegue ficar em um pé só por 3 segundos.</td>
                <td><button class="btn-icon" title="Editar"><i class="fas fa-edit"></i></button></td>
            </tr>
        </tbody>
    </table>
</div>

<?php
require_once 'templates/footer_professor.php';
?>