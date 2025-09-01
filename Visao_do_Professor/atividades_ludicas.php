<?php
// PRIMEIRO: Defina a constante. 
// Isso cria a "palavra" no dicionário.
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto

// SEGUNDO: Agora que a constante existe, inclua o cabeçalho.
// O cabeçalho agora pode "procurar a palavra" e vai encontrá-la.
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Atividades Lúdicas';
$page_icon = 'fas fa-puzzle-piece';
$breadcrumb = 'Portal do Professor > Planejamento > Atividades Lúdicas';


// --- LÓGICA DO BANCO DE DADOS (Simulação) ---
// No futuro, viria da tabela `plano_atividades` ou uma tabela específica de atividades modelo.
$atividades_ludicas = [
    ['id' => 101, 'title' => "Caça ao Tesouro Colorido", 'category' => "Cognitiva", 'duration' => "45 minutos", 'objectives' => "Identificar e nomear cores, seguir instruções.", 'materials' => "Objetos coloridos, pistas, cesta."],
    ['id' => 102, 'title' => "Dança das Cadeiras Musical", 'category' => "Motora", 'duration' => "30 minutos", 'objectives' => "Desenvolver coordenação motora e agilidade.", 'materials' => "Cadeiras, aparelho de som."]
];
// --- FIM DA LÓGICA ---
?>

<div class="card">
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="openTab(event, 'listar-atividades')">
            <i class="fas fa-list"></i> Listar Atividades
        </button>
        <button class="tab-btn" onclick="openTab(event, 'cadastrar-atividade')">
            <i class="fas fa-plus-circle"></i> Cadastrar Nova
        </button>
    </div>

    <div id="listar-atividades" class="tab-content active">
        <div class="card-header"><h3 class="section-title">Biblioteca de Atividades Lúdicas</h3></div>
        <div class="card-body">
            <div id="activities-list" class="activities-container">
                <?php if (empty($atividades_ludicas)): ?>
                    <p>Nenhuma atividade lúdica cadastrada.</p>
                <?php else: ?>
                    <?php foreach ($atividades_ludicas as $activity): ?>
                        <div class="activity-card">
                            <div class="activity-header"><h3><?php echo htmlspecialchars($activity['title']); ?></h3></div>
                            <div class="activity-body">
                                <div class="activity-info"><i class="fas fa-tags"></i><span>Categoria: <?php echo htmlspecialchars($activity['category']); ?></span></div>
                                <div class="activity-info"><i class="fas fa-clock"></i><span>Duração: <?php echo htmlspecialchars($activity['duration']); ?></span></div>
                                <div class="activity-info"><i class="fas fa-bullseye"></i><span>Objetivo: <?php echo htmlspecialchars($activity['objectives']); ?></span></div>
                                <div class="activity-info"><i class="fas fa-tools"></i><span>Materiais: <?php echo htmlspecialchars($activity['materials']); ?></span></div>
                            </div>
                            <div class="activity-footer">
                                <button class="btn btn-primary" onclick="alert('Funcionalidade ainda não implementada.')">
                                    <i class="fas fa-calendar-plus"></i> Adicionar ao Plano
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="cadastrar-atividade" class="tab-content">
        <div class="card-header"><h3 class="section-title">Cadastrar Nova Atividade Lúdica</h3></div>
        <div class="card-body">
            <form id="form-ludica" method="POST" action="processa_atividade_ludica.php">
                <div class="form-group">
                    <label for="new-activity-title">Título da Atividade*</label>
                    <input type="text" id="new-activity-title" name="titulo" placeholder="Ex: Corrida do Saco" required>
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="reset"><i class="fas fa-times"></i> Limpar</button>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Cadastrar Atividade</button>
                </div>
            </form>
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
        openTab({currentTarget: document.querySelector(".tab-btn")}, "listar-atividades");
    });
</script>';
require_once 'templates/footer_professor.php';
?>