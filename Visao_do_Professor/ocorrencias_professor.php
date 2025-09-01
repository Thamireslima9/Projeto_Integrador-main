<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Ocorrências';
$page_icon = 'fas fa-exclamation-triangle';
$breadcrumb = 'Portal do Professor > Ocorrências';


// Simulação de dados
$turmas = [['id' => 1, 'nome' => 'Berçário II'], ['id' => 2, 'nome' => 'Maternal I']];
$ocorrencias = [
    ['data' => '17/06/2025', 'crianca' => 'João Pedro Silva', 'turma' => 'Berçário II', 'tipo' => 'Saúde', 'desc' => 'Febre de 38.5°C', 'status' => 'Resolvido'],
    ['data' => '15/06/2025', 'crianca' => 'Maria Clara', 'turma' => 'Berçário II', 'tipo' => 'Comportamental', 'desc' => 'Dificuldade em compartilhar', 'status' => 'Pendente']
];
?>

<div class="welcome-banner">
  <h3>Registro de Ocorrências</h3>
  <p>Registre eventos relevantes que necessitem de atenção especial.</p>
</div>

<div class="form-container">
    <h4><i class="fas fa-plus-circle"></i> Nova Ocorrência</h4>
    <form method="POST" action="processa_ocorrencia.php">
        <div class="form-row">
            <div class="form-group">
                <label>Turma</label>
                <select name="turma_id"></select>
            </div>
            <div class="form-group">
                <label>Criança</label>
                <select name="aluno_id"></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Data e Hora</label>
                <input type="datetime-local" name="data_ocorrencia">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo">
                    <option value="Comportamental">Comportamental</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Acidente">Acidente</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="4" placeholder="Descreva detalhadamente a ocorrência"></textarea>
        </div>
        <div class="form-group">
            <label>Ações Tomadas</label>
            <textarea name="acoes" rows="3" placeholder="Descreva as ações realizadas"></textarea>
        </div>
        <div class="form-actions">
            <button class="btn btn-secondary" type="reset">Cancelar</button>
            <button class="btn btn-primary" type="submit">Registrar Ocorrência</button>
        </div>
    </form>
</div>

<div class="table-container" style="margin-top: 30px;">
    <h4><i class="fas fa-history"></i> Ocorrências Registradas</h4>
    <table class="table">
      <thead>
        <tr><th>Data</th><th>Criança</th><th>Tipo</th><th>Descrição</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
        <?php foreach ($ocorrencias as $oc): ?>
        <tr>
          <td><?php echo htmlspecialchars($oc['data']); ?></td>
          <td><?php echo htmlspecialchars($oc['crianca']); ?></td>
          <td><?php echo htmlspecialchars($oc['tipo']); ?></td>
          <td><?php echo htmlspecialchars($oc['desc']); ?></td>
          <td><span class="status-badge <?php echo strtolower($oc['status']) === 'resolvido' ? 'resolved' : 'pending'; ?>"><?php echo htmlspecialchars($oc['status']); ?></span></td>
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