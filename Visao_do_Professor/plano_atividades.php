<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Plano de Atividades';
$page_icon = 'fas fa-calendar-day';
$breadcrumb = 'Portal do Professor > Planejamento > Plano de Atividades';


// --- LÓGICA DO BANCO DE DADOS (Simulação) ---
// No futuro, esta consulta buscará as turmas do professor logado
// $stmt = $conexao->prepare("SELECT id, nome FROM turmas WHERE professor_principal_id = ?");
// $stmt->bind_param("i", $professor_logado_id);
// $stmt->execute();
// $turmas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Simulação:
$turmas = [
    ['id' => 1, 'nome' => 'Maternal I - A'],
    ['id' => 2, 'nome' => 'Maternal II - B'],
    ['id' => 3, 'nome' => 'Jardim I - C']
];
// --- FIM DA LÓGICA ---
?>

<div class="card">
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="openTab(event, 'cadastrar-atividade')">
            <i class="fas fa-plus-circle"></i> Cadastrar Atividade
        </button>
        <button class="tab-btn" onclick="openTab(event, 'visualizar-plano')">
            <i class="fas fa-calendar-alt"></i> Visualizar Plano
        </button>
    </div>

    <div id="cadastrar-atividade" class="tab-content active">
        <div class="card-header"><h3 class="section-title">Cadastrar Nova Atividade no Plano</h3></div>
        <div class="card-body">
            <form id="form-atividade" method="POST" action="processa_plano_atividades.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="activity-title">Título da Atividade*</label>
                        <input type="text" id="activity-title" name="titulo" placeholder="Ex: Roda de Leitura" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-class">Turma*</label>
                        <select id="activity-class" name="turma_id" required>
                            <option value="">Selecione a Turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo $turma['id']; ?>"><?php echo htmlspecialchars($turma['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="activity-date">Data*</label>
                        <input type="date" name="data" id="activity-date" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-time">Hora*</label>
                        <input type="time" name="hora" id="activity-time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="activity-description">Descrição/Objetivos*</label>
                    <textarea id="activity-description" name="descricao" placeholder="Descreva os objetivos e como a atividade será realizada" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="activity-materials">Materiais Necessários</label>
                    <input type="text" id="activity-materials" name="materiais" placeholder="Ex: Livros infantis, almofadas, projetor">
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="reset"><i class="fas fa-times"></i> Limpar</button>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Salvar Atividade</button>
                </div>
            </form>
        </div>
    </div>

    <div id="visualizar-plano" class="tab-content">
        <div class="card-header"><h3 class="section-title">Visualizar Plano de Atividades</h3></div>
        <div class="card-body">
            <p>Conteúdo da visualização do plano de atividades (calendário/lista) viria aqui...</p>
        </div>
    </div>
</div>

<?php
$extra_js = '<script>
    function openTab(evt, tabName) {
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tab-content");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
      }
      tablinks = document.getElementsByClassName("tab-btn");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
      }
      document.getElementById(tabName).classList.add("active");
      evt.currentTarget.classList.add("active");
    }
    document.addEventListener("DOMContentLoaded", function() {
        openTab({currentTarget: document.querySelector(".tab-btn")}, "cadastrar-atividade");
    });
</script>';
require_once 'templates/footer_professor.php';
?>