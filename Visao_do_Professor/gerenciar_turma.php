<?php
  // --- LÓGICA DO BANCO DE DADOS (SIMULAÇÃO) ---
  $turma_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  
  // Simulação de busca no banco
  $turma_info = null;
  if ($turma_id == 1) {
    $turma_info = ['id' => 1, 'nome' => 'Berçário II'];
    // No sistema real, você buscaria os alunos e a presença deles para a data de hoje.
    $alunos = [
        ['id' => 101, 'nome' => 'Ana Luiza Souza', 'status_presenca' => null], 
        ['id' => 102, 'nome' => 'Bernardo Pereira', 'status_presenca' => null], 
        ['id' => 103, 'nome' => 'Clara Martins', 'status_presenca' => null]
    ];
  } // ... adicionar outras turmas

  if (!$turma_info) {
    die('Turma não encontrada!');
  }

  // Flag para controlar se a chamada já foi submetida
  $chamada_submetida = false;

  // Lógica para processar o formulário quando for enviado
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A chamada foi enviada. No sistema real, você salvaria os dados no banco.
    // Ex: UPDATE presencas SET status = ? WHERE aluno_id = ? AND data = CURDATE()
    
    $chamada_submetida = true;

    // Atualiza o status dos alunos com base no que foi enviado pelo formulário
    if (isset($_POST['status'])) {
      foreach ($alunos as &$aluno) { // O '&' permite modificar o array original
        if (isset($_POST['status'][$aluno['id']])) {
          $aluno['status_presenca'] = $_POST['status'][$aluno['id']];
        }
      }
    }
    // Aqui você também salvaria os dados do registro diário (alimentação, sono, etc.)
  }
  // --- FIM DA LÓGICA ---

  $page_title = 'Gerenciar Turma: ' . htmlspecialchars($turma_info['nome']);
  $page_icon = 'fas fa-tasks';
  $breadcrumb = 'Portal do Professor > Turmas > Minhas Turmas > Gerenciar';

 define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
?>

<form method="POST" action="gerenciar_turma.php?id=<?php echo $turma_id; ?>">

  <div class="dashboard-card">
    <h4><i class="fas fa-user-check"></i> Chamada do Dia (<?php echo date('d/m/Y'); ?>)</h4>
    <table class="attendance-table">
      <thead>
        <tr>
          <th>Aluno</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($alunos as $aluno): ?>
        <tr>
          <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
          <td>
            <label>
              <input type="radio" name="status[<?php echo $aluno['id']; ?>]" value="presente" <?php echo ($aluno['status_presenca'] == 'presente') ? 'checked' : ''; ?>> Presente
            </label>
            <label>
              <input type="radio" name="status[<?php echo $aluno['id']; ?>]" value="ausente" <?php echo ($aluno['status_presenca'] == 'ausente') ? 'checked' : ''; ?>> Ausente
            </label>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  
  <?php
  // ***** LÓGICA PRINCIPAL *****
  // Só exibe a seção de Registro Diário se o formulário já tiver sido enviado uma vez.
  if ($chamada_submetida):
  ?>
  <div class="dashboard-card">
      <h4><i class="fas fa-book-medical"></i> Registro Diário dos Alunos Presentes</h4>
      <table class="daily-log-table">
          <thead>
            <tr>
              <th>Aluno</th>
              <th>Alimentação</th>
              <th>Sono</th>
              <th>Higiene</th>
              <th>Observações</th>
            </tr>
          </thead>
          <tbody>
              <?php foreach($alunos as $aluno): ?>
                <?php 
                // Só cria a linha na tabela se o aluno estiver com status 'presente'
                if ($aluno['status_presenca'] == 'presente'): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                    <td><select name="alimentacao[<?php echo $aluno['id']; ?>]"><option>Comeu bem</option><option>Comeu pouco</option><option>Recusou</option></select></td>
                    <td><select name="sono[<?php echo $aluno['id']; ?>]"><option>Sim</option><option>Não</option><option>Pouco</option></select></td>
                    <td><input type="text" name="higiene[<?php echo $aluno['id']; ?>]" placeholder="Ex: 3 trocas"></td>
                    <td><textarea name="obs[<?php echo $aluno['id']; ?>]" placeholder="..."></textarea></td>
                </tr>
                <?php endif; // Fim do if de aluno presente ?>
              <?php endforeach; // Fim do loop de alunos ?>
          </tbody>
      </table>
  </div>
  <?php endif; // Fim do if de chamada submetida ?>

  <div style="text-align: right; margin-top: 20px;">
      <button type="submit" class="btn btn-primary btn-large">
        <?php echo $chamada_submetida ? '<i class="fas fa-save"></i> Salvar Alterações' : '<i class="fas fa-arrow-down"></i> Salvar Chamada e Carregar Registros'; ?>
      </button>
  </div>
</form>

<?php
  require_once '/templates/footer.php';
?>