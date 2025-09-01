<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Novo Comunicado';
$page_icon = 'fas fa-bullhorn';
$breadcrumb = 'Portal do Professor > Comunicados > Novo Comunicado';


// Simulação de busca de turmas e usuários para destinatários
$turmas = [['id' => 1, 'nome' => 'Turma Berçário I'], ['id' => 2, 'nome' => 'Turma Maternal I']];
$outros_destinatarios = [['id' => 10, 'nome' => 'Coordenadora Pedagógica']];
?>

<div class="card">
  <div class="card-header"><h3>Detalhes do Comunicado</h3></div>
  <div class="card-body">
    <form id="novo-comunicado-form" method="POST" action="processa_comunicado.php">
      <div class="form-row">
        <div class="form-group">
          <label for="data-envio">Data de Envio</label>
          <input type="date" id="data-envio" name="data_envio" value="<?php echo date('Y-m-d'); ?>" required readonly>
        </div>
        <div class="form-group">
          <label for="remetente">Remetente</label>
          <input type="text" id="remetente" value="<?php echo htmlspecialchars($nome_professor_logado); ?>" readonly>
          <input type="hidden" name="remetente_id" value="<?php echo $professor_logado_id; ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="assunto">Assunto do Comunicado</label>
        <input type="text" id="assunto" name="assunto" placeholder="Ex: Reunião de pais, evento escolar..." required>
      </div>
      <div class="form-group">
          <label for="destinatario">Destinatário:</label>
          <select id="destinatario" name="destinatario">
            <option value="">Selecione o destinatário</option>
            <option value="todos">Todos os Pais/Responsáveis</option>
            <?php foreach($turmas as $turma): ?>
              <option value="turma_<?php echo $turma['id']; ?>">Pais/Responsáveis - <?php echo htmlspecialchars($turma['nome']); ?></option>
            <?php endforeach; ?>
             <?php foreach($outros_destinatarios as $dest): ?>
              <option value="usuario_<?php echo $dest['id']; ?>"><?php echo htmlspecialchars($dest['nome']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      <div class="form-group">
        <label for="mensagem">Mensagem</label>
        <textarea id="mensagem" name="mensagem" rows="8" placeholder="Escreva o conteúdo do comunicado aqui..." required></textarea>
      </div>
      <div class="form-actions">
        <a href="comunicados_professor.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar Comunicado</button>
      </div>
    </form>
  </div>
</div>

<?php
require_once 'templates/footer_professor.php';
?>