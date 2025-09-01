<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Crianças por Turma';
$page_icon = 'fas fa-user-friends';
$breadcrumb = 'Portal do Professor > Turmas > Crianças por Turma';


// --- LÓGICA DO BANCO DE DADOS (Simulação) ---
// $alunos = $conexao->query("SELECT a.*, t.nome as nome_turma FROM alunos a JOIN turmas t ON a.turma_id = t.id")->fetch_all(MYSQLI_ASSOC);
$alunos = [
    ['nome_completo' => 'João Pedro Silva', 'idade' => '1 ano', 'nome_turma' => 'Berçário II', 'responsavel' => 'Maria Silva', 'contato' => '(11) 99999-9999'],
    ['nome_completo' => 'Maria Clara Oliveira', 'idade' => '1 ano e 3 meses', 'nome_turma' => 'Berçário II', 'responsavel' => 'Ana Oliveira', 'contato' => '(11) 98888-8888'],
    ['nome_completo' => 'Lucas Mendes', 'idade' => '2 anos', 'nome_turma' => 'Maternal I', 'responsavel' => 'Carlos Mendes', 'contato' => '(11) 97777-7777']
];
// --- FIM DA LÓGICA ---
?>

<div class="welcome-banner">
    <h3>Crianças por Turma</h3>
    <p>Visualize as informações das crianças matriculadas em suas turmas.</p>
</div>

<div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Idade</th>
          <th>Turma</th>
          <th>Responsável</th>
          <th>Contato</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($alunos as $aluno): ?>
        <tr>
          <td><?php echo htmlspecialchars($aluno['nome_completo']); ?></td>
          <td><?php echo htmlspecialchars($aluno['idade']); ?></td>
          <td><?php echo htmlspecialchars($aluno['nome_turma']); ?></td>
          <td><?php echo htmlspecialchars($aluno['responsavel']); ?></td>
          <td><?php echo htmlspecialchars($aluno['contato']); ?></td>
          <td>
            <button class="btn-icon" title="Editar"><i class="fas fa-edit"></i></button>
            <button class="btn-icon" title="Ver Perfil Completo"><i class="fas fa-user"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</div>

<?php
require_once 'templates/footer_professor.php';
?>