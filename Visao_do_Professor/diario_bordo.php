<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Diário de Bordo';
$page_icon = 'fas fa-book';
$breadcrumb = 'Portal do Professor > Diário de Bordo';


// Simulação de dados
$turmas = [['id' => 1, 'nome' => 'Berçário II'], ['id' => 2, 'nome' => 'Maternal I']];
$registros_anteriores = [
    ['data' => '17/06/2025', 'turma' => 'Berçário II', 'atividade' => 'Atividade sensorial com texturas', 'obs' => 'Boa participação geral.'],
    ['data' => '16/06/2025', 'turma' => 'Maternal I', 'atividade' => 'Contação de histórias', 'obs' => 'Maria e Lucas participaram ativamente.']
];
?>

<div class="welcome-banner">
  <h3>Diário de Bordo da Turma</h3>
  <p>Registre as atividades diárias e observações gerais sobre a turma.</p>
</div>

<div class="form-container">
    <form method="POST" action="processa_diario.php">
        <div class="form-row">
            <div class="form-group">
                <label>Turma</label>
                <select name="turma_id">
                    <?php foreach ($turmas as $turma): ?>
                        <option value="<?php echo $turma['id']; ?>"><?php echo htmlspecialchars($turma['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Data</label>
                <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Atividade Principal Realizada</label>
            <input type="text" name="atividade_principal" placeholder="Descreva a atividade principal do dia">
        </div>
        <div class="form-group">
            <label>Análise/Resultados da Turma</label>
            <textarea name="analise" rows="5" placeholder="Descreva como a turma reagiu, participação, resultados alcançados"></textarea>
        </div>
        <div class="form-group">
            <label>Observações Gerais Importantes</label>
            <textarea name="observacoes" rows="3" placeholder="Comportamentos relevantes, dificuldades, destaques do grupo"></textarea>
        </div>
        <div class="form-actions">
            <button class="btn btn-secondary" type="reset">Cancelar</button>
            <button class="btn btn-primary" type="submit">Salvar Registro</button>
        </div>
    </form>
</div>

<div class="table-container" style="margin-top: 30px;">
    <h4><i class="fas fa-history"></i> Registros Anteriores</h4>
    <table class="table">
      <thead>
        <tr><th>Data</th><th>Turma</th><th>Atividade</th><th>Observações</th><th>Ações</th></tr>
      </thead>
      <tbody>
        <?php foreach ($registros_anteriores as $reg): ?>
        <tr>
          <td><?php echo htmlspecialchars($reg['data']); ?></td>
          <td><?php echo htmlspecialchars($reg['turma']); ?></td>
          <td><?php echo htmlspecialchars($reg['atividade']); ?></td>
          <td><?php echo htmlspecialchars($reg['obs']); ?></td>
          <td>
            <button class="btn-icon" title="Editar"><i class="fas fa-edit"></i></button>
            <button class="btn-icon" title="Visualizar"><i class="fas fa-eye"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</div>

<?php
require_once 'templates/footer_professor.php';
?>