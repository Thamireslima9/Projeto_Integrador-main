<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Comunicados';
$page_icon = 'fas fa-comment-dots';
$breadcrumb = 'Portal do Professor > Comunicados';


// Simulação de busca de comunicados no BD
$comunicados = [
    ['data' => '20/06/2025', 'assunto' => 'Reunião de Pais - Berçário II', 'remetente' => 'Secretaria', 'status' => 'Lido'],
    ['data' => '18/06/2025', 'assunto' => 'Calendário de Férias', 'remetente' => 'Diretoria', 'status' => 'Lido'],
    ['data' => '15/06/2025', 'assunto' => 'Lembrete: Documentação pendente', 'remetente' => 'Secretaria', 'status' => 'Não lido']
];
?>

<div class="card">
  <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h3>Lista de Comunicados</h3>
    <a href="criar_comunicado.php" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Comunicado</a>
  </div>
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th>Data</th>
          <th>Assunto</th>
          <th>Remetente</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($comunicados as $comunicado): ?>
        <tr>
          <td><?php echo htmlspecialchars($comunicado['data']); ?></td>
          <td><?php echo htmlspecialchars($comunicado['assunto']); ?></td>
          <td><?php echo htmlspecialchars($comunicado['remetente']); ?></td>
          <td>
            <span class="status-badge <?php echo strtolower($comunicado['status']) === 'lido' ? 'active' : 'inactive'; ?>">
              <?php echo htmlspecialchars($comunicado['status']); ?>
            </span>
          </td>
          <td>
            <button class="btn-icon" title="Visualizar"><i class="fas fa-eye"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
require_once 'templates/footer_professor.php';
?>