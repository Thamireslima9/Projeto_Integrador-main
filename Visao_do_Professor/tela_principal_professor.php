<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Dashboard';
$page_icon = 'fas fa-home';
$breadcrumb = 'Portal do Professor > Dashboard';

?>

<div class="welcome-banner">
    <h3>Bem-vinda, Professora Ana</h3>
    <p>Hoje é <span id="current-date"><?php echo date('d \d\e F \d\e Y'); ?></span> | Próxima atividade: Hora do conto - 10:00 (Berçário II)</p>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card calendar-card">
      <h4><i class="fas fa-calendar-day"></i> Agenda do Dia</h4>
      <div class="calendar-events">
        <div class="event"><span class="event-time">08:00 - 09:00</span> <span class="event-title">Chegada e acolhida - Berçário II</span></div>
        <div class="event"><span class="event-time">10:00 - 10:30</span> <span class="event-title">Hora do conto - Berçário II</span></div>
        <div class="event"><span class="event-time">14:00 - 15:00</span> <span class="event-title">Atividade motora - Maternal I</span></div>
      </div>
      <a href="plano_atividades.php" class="view-all">Ver agenda completa</a>
    </div>

    <div class="dashboard-card alerts-card">
      <h4><i class="fas fa-bell"></i> Alertas Importantes</h4>
      <div class="alert-list">
        <div class="alert"><i class="fas fa-exclamation-circle"></i> <span>2 crianças com alergias alimentares hoje</span></div>
        <div class="alert"><i class="fas fa-info-circle"></i> <span>Reunião pedagógica amanhã às 14h</span></div>
        <div class="alert"><i class="fas fa-child"></i> <span>Lucas completou 2 anos hoje!</span></div>
      </div>
      <a href="comunicados_professor.php" class="view-all">Ver todos os alertas</a>
    </div>
</div>

<div class="shortcut-grid">
    <a href="minhas_turmas.php" class="shortcut-card"><i class="fas fa-child"></i><span>Minhas Turmas</span></a>
    <a href="desenvolvimento_aluno.php" class="shortcut-card"><i class="fas fa-chart-line"></i><span>Acompanhamento</span></a>
    <a href="diario_bordo.php" class="shortcut-card"><i class="fas fa-book"></i><span>Diário de Bordo</span></a>
    <a href="ocorrencias_professor.php" class="shortcut-card"><i class="fas fa-exclamation-triangle"></i><span>Ocorrências</span></a>
</div>

<?php
require_once 'templates/footer_professor.php';
?>