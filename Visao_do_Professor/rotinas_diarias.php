<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Rotinas Diárias';
$page_icon = 'fas fa-clipboard-list';
$breadcrumb = 'Portal do Professor > Planejamento > Rotinas Diárias';


// Simulação de busca das turmas no BD
$turmas = [
    ['id' => 1, 'nome' => 'Berçário II'],
    ['id' => 2, 'nome' => 'Maternal I']
];
?>

<div class="welcome-banner">
  <h3>Rotinas Diárias</h3>
  <p>Gerencie os horários e atividades padrão para cada turma.</p>
</div>

<div class="dashboard-card">
    <h4><i class="fas fa-calendar-day"></i> Exemplo de Rotina: Berçário II - Segunda-feira</h4>
    <div class="calendar-events">
        <div class="event"><span class="event-time">07:30 - 08:30</span> <span class="event-title">Chegada e acolhida</span></div>
        <div class="event"><span class="event-time">08:30 - 09:00</span> <span class="event-title">Lanche da manhã</span></div>
        <div class="event"><span class="event-time">09:00 - 10:00</span> <span class="event-title">Atividade lúdica dirigida</span></div>
        <div class="event"><span class="event-time">10:00 - 11:30</span> <span class="event-title">Sono/descanso</span></div>
        <div class="event"><span class="event-time">11:30 - 12:00</span> <span class="event-title">Almoço</span></div>
        </div>
</div>

<div class="form-container" style="margin-top: 20px;">
    <h4><i class="fas fa-edit"></i> Cadastrar ou Editar Rotina</h4>
    <form method="POST" action="processa_rotina.php">
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
                <label>Dia da semana</label>
                <select name="dia_semana">
                    <option value="Segunda-feira">Segunda-feira</option>
                    <option value="Terça-feira">Terça-feira</option>
                    <option value="Quarta-feira">Quarta-feira</option>
                    <option value="Quinta-feira">Quinta-feira</option>
                    <option value="Sexta-feira">Sexta-feira</option>
                </select>
            </div>
        </div>
        
        <div id="routine-items">
            <div class="routine-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                <input type="time" name="horario_inicio[]" style="flex: 1;">
                <span>até</span>
                <input type="time" name="horario_fim[]" style="flex: 1;">
                <input type="text" name="atividade_descricao[]" placeholder="Descrição da atividade" style="flex: 3;">
                <button type="button" class="btn-icon" onclick="this.parentElement.remove();"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary" style="margin-top: 10px;" onclick="adicionarItemRotina()">
          <i class="fas fa-plus"></i> Adicionar Item
        </button>
        
        <div class="form-actions">
          <button class="btn btn-secondary" type="reset">Cancelar</button>
          <button class="btn btn-primary" type="submit">Salvar Rotina</button>
        </div>
    </form>
</div>

<?php
$extra_js = '<script>
function adicionarItemRotina() {
    const container = document.getElementById("routine-items");
    const novoItem = document.createElement("div");
    novoItem.className = "routine-item";
    novoItem.style = "display: flex; gap: 10px; margin-bottom: 10px; align-items: center;";
    novoItem.innerHTML = `
        <input type="time" name="horario_inicio[]" style="flex: 1;">
        <span>até</span>
        <input type="time" name="horario_fim[]" style="flex: 1;">
        <input type="text" name="atividade_descricao[]" placeholder="Descrição da atividade" style="flex: 3;">
        <button type="button" class="btn-icon" onclick="this.parentElement.remove();"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(novoItem);
}
</script>';
require_once 'templates/footer_professor.php';
?>