<?php
  // --- LÓGICA DO BANCO DE DADOS (SIMULAÇÃO) ---
  // Pega o ID da turma da URL. Importante validar para segurança!
  $turma_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  
  // Aqui você buscaria no banco de dados: "SELECT * FROM turmas WHERE id = $turma_id"
  // Vamos simular a busca
  $turma_info = null;
  if ($turma_id == 1) {
    $turma_info = ['id' => 1, 'nome' => 'Berçário II'];
    // Aqui você também buscaria os alunos da turma 1: "SELECT * FROM alunos WHERE turma_id = 1"
    $alunos = [['nome' => 'Ana Luiza'], ['nome' => 'Bernardo'], ['nome' => 'Clara']];
  } elseif ($turma_id == 2) {
    $turma_info = ['id' => 2, 'nome' => 'Maternal I'];
    $alunos = [['nome' => 'Davi'], ['nome' => 'Elena'], ['nome' => 'Felipe']];
  }

  // Se não encontrou a turma, pode redirecionar ou mostrar erro
  if (!$turma_info) {
    die('Turma não encontrada!');
  }
  // --- FIM DA LÓGICA ---

  // Definindo variáveis específicas para esta página
  $page_title = 'Detalhes da Turma: ' . htmlspecialchars($turma_info['nome']);
  $page_icon = 'fas fa-info-circle';
  $breadcrumb = 'Portal do Professor > Turmas > Minhas Turmas > Detalhes';

  require_once 'templates/header.php';
?>

<div class="dashboard-card">
  <h4><i class="fas fa-users"></i> Alunos Matriculados em <?php echo htmlspecialchars($turma_info['nome']); ?></h4>
  <div class="student-grid">
    <?php foreach ($alunos as $aluno): ?>
    <div class="student-card">
      <div class="student-avatar"><?php echo strtoupper(substr($aluno['nome'], 0, 2)); ?></div>
      <p><?php echo htmlspecialchars($aluno['nome']); ?></p>
      <a href="#" class="btn-profile">Ver Perfil</a>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php
  require_once 'templates/footer.php';
?>