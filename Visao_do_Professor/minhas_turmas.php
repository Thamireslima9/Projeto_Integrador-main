<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Minhas Turmas';
$page_icon = 'fas fa-baby-carriage';
$breadcrumb = 'Portal do Professor > Turmas > Minhas Turmas';


// --- LÓGICA DO BANCO DE DADOS (Simulação) ---
// No futuro, buscaria turmas associadas ao $professor_logado_id
$turmas = [
    [
      'id' => 1, 'nome' => 'Berçário II', 'faixa_etaria' => '0-1 ano', 'num_criancas' => 12, 'responsavel' => 'Prof. Ana Silva', 'periodo' => 'Manhã (07:30 - 12:00)', 'sala' => 'Berçário 2', 'presenca_media' => 82
    ],
    [
      'id' => 2, 'nome' => 'Maternal I', 'faixa_etaria' => '1-2 anos', 'num_criancas' => 15, 'responsavel' => 'Prof. Ana Silva', 'periodo' => 'Tarde (13:00 - 17:30)', 'sala' => 'Maternal 1', 'presenca_media' => 88
    ]
];
// --- FIM DA LÓGICA ---
?>

<div class="welcome-banner">
  <h3>Minhas Turmas</h3>
  <p>Gerencie as turmas sob sua responsabilidade</p>
</div>

<div class="class-grid">
    <?php foreach ($turmas as $turma): ?>
    <div class="class-card">
      <div class="class-header">
        <h3><?php echo htmlspecialchars($turma['nome']); ?></h3>
        <span class="class-period"><?php echo htmlspecialchars($turma['faixa_etaria']); ?></span>
      </div>
      <div class="class-body">
        <div class="class-info"><i class="fas fa-users"></i><span><?php echo $turma['num_criancas']; ?> crianças matriculadas</span></div>
        <div class="class-info"><i class="fas fa-user-tie"></i><span>Responsável: <?php echo htmlspecialchars($turma['responsavel']); ?></span></div>
        <div class="class-info"><i class="fas fa-clock"></i><span>Período: <?php echo htmlspecialchars($turma['periodo']); ?></span></div>
        <div class="class-info"><i class="fas fa-door-open"></i><span>Sala: <?php echo htmlspecialchars($turma['sala']); ?></span></div>
      </div>
      <div class="class-footer">
        <a href="#" class="btn btn-secondary">Detalhes</a>
        <a href="gerenciar_turma.php?id=<?php echo $turma['id']; ?>" class="btn btn-primary">Gerenciar</a>
      </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="dashboard-card" style="margin-top: 20px;">
  <h4><i class="fas fa-user-check"></i> Presença Média</h4>
  <div class="attendance-summary">
    <?php foreach ($turmas as $turma): ?>
    <div class="attendance-item">
      <span class="attendance-class"><?php echo htmlspecialchars($turma['nome']); ?></span>
      <div class="attendance-bar">
        <div class="attendance-fill" style="width: <?php echo $turma['presenca_media']; ?>%"></div>
      </div>
      <span class="attendance-value"><?php echo $turma['presenca_media']; ?>%</span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php
require_once 'templates/footer_professor.php';
?>