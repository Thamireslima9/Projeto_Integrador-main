<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Relatórios';
$page_icon = 'fas fa-file-alt';
$breadcrumb = 'Portal do Professor > Acompanhamento > Relatórios';


// Simulação de busca de turmas e alunos
$turmas = [
    ['id' => 1, 'nome' => 'Berçário II'],
    ['id' => 2, 'nome' => 'Maternal I']
];
$alunos = [
    ['id' => 101, 'nome' => 'João Pedro Silva', 'turma_id' => 1],
    ['id' => 102, 'nome' => 'Maria Clara Oliveira', 'turma_id' => 1],
    ['id' => 201, 'nome' => 'Lucas Mendes', 'turma_id' => 2]
];
?>

<div class="welcome-banner">
  <h3>Geração de Relatórios</h3>
  <p>Exporte relatórios sobre o desenvolvimento e atividades das crianças.</p>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card">
      <h4><i class="fas fa-child"></i> Relatório Individual de Desenvolvimento</h4>
      <form method="POST" action="gerar_relatorio_individual.php" target="_blank">
          <div class="form-group" style="margin-top: 15px;">
            <label>Selecione a turma:</label>
            <select name="turma_id" onchange="/* JS para filtrar alunos */">
                <?php foreach ($turmas as $turma): ?>
                    <option value="<?php echo $turma['id']; ?>"><?php echo htmlspecialchars($turma['nome']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="margin-top: 15px;">
            <label>Selecione a criança:</label>
            <select name="aluno_id">
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?php echo $aluno['id']; ?>"><?php echo htmlspecialchars($aluno['nome']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Período:</label>
            <div style="display: flex; gap: 10px;">
              <input type="date" name="data_inicio" style="flex: 1;">
              <span>até</span>
              <input type="date" name="data_fim" style="flex: 1;">
            </div>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
            <i class="fas fa-download"></i> Gerar Relatório
          </button>
      </form>
    </div>

    <div class="dashboard-card">
      <h4><i class="fas fa-users"></i> Relatório Geral da Turma</h4>
      <form method="POST" action="gerar_relatorio_turma.php" target="_blank">
          <div class="form-group" style="margin-top: 15px;">
            <label>Selecione a turma:</label>
            <select name="turma_id">
                <?php foreach ($turmas as $turma): ?>
                    <option value="<?php echo $turma['id']; ?>"><?php echo htmlspecialchars($turma['nome']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Período:</label>
            <div style="display: flex; gap: 10px;">
              <input type="date" name="data_inicio" style="flex: 1;">
              <span>até</span>
              <input type="date" name="data_fim" style="flex: 1;">
            </div>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
            <i class="fas fa-download"></i> Gerar Relatório
          </button>
      </form>
    </div>
</div>

<?php
require_once 'templates/footer_professor.php';
?>