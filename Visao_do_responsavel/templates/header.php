<?php
// Define a constante que aponta para a pasta raiz do projeto
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(dirname(__DIR__)));
}
require_once PROJECT_ROOT . '/conexao.php';

// Inicia a sessão para buscar os dados do responsável logado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// SIMULAÇÃO DE LOGIN: Substitua isso pela sua lógica de sessão real
// $_SESSION['id_usuario'] = 1; // ID do responsável logado
// $_SESSION['nome_usuario'] = 'Carlos Silva';

// Verifica se o responsável está logado, senão redireciona para o login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["perfil"] !== 'responsavel') {
    header("location: ../tela_login/index.php");
    exit;
}

// Função para ativar o item de menu da página atual
function is_active($page_name) {
    if (basename($_SERVER['PHP_SELF']) == $page_name) {
        return 'active';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo isset($page_title) ? $page_title . ' - Portal do Responsável' : 'Portal do Responsável'; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="CSS/Style.css"/>
</head>
<body>
  <div class="sidebar">
    <div class="logo">
      <i class="fas fa-graduation-cap"></i>
      <h1>Rede Educacional</h1>
    </div>

    <div class="menu">
      <div class="menu-section">
        <div class="menu-item <?php echo is_active('index.php'); ?>">
          <a href="index.php"><i class="fas fa-home"></i><span>Página Inicial</span></a>
        </div>
        <div class="menu-item <?php echo is_active('perfil_crianca.php'); ?>">
          <a href="perfil_crianca.php"><i class="fas fa-child"></i><span>Perfil da Criança</span></a>
        </div>
        <div class="menu-item <?php echo is_active('avisos_responsavel.php'); ?>">
          <a href="avisos_responsavel.php"><i class="fas fa-bell"></i><span>Avisos</span></a>
        </div>
        <div class="menu-item <?php echo is_active('atestados_responsavel.php'); ?>">
          <a href="atestados_responsavel.php"><i class="fas fa-notes-medical"></i><span>Atestados</span></a>
        </div>
        <div class="menu-item <?php echo is_active('ocorrencias_responsavel.php'); ?>">
          <a href="ocorrencias_responsavel.php"><i class="fas fa-exclamation-triangle"></i><span>Ocorrências</span></a>
        </div>
      </div>
      <div class="menu-item">
        <a href="../tela_login/index.php"><i class="fas fa-sign-out-alt"></i><span>Sair</span></a>
      </div>
    </div>
  </div>

  <div class="main-content">
    <div class="header">
      <div class="page-title">
        <i class="<?php echo isset($page_icon) ? $page_icon : 'fas fa-home'; ?>"></i>
        <h2><?php echo isset($page_title) ? $page_title : 'Página Inicial'; ?></h2>
      </div>
      <div class="user-info">
        <div class="user-avatar">
            <?php echo strtoupper(substr($_SESSION['nome_usuario'] ?? 'R', 0, 2)); ?>
        </div>
        <span><?php echo htmlspecialchars($_SESSION['nome_usuario'] ?? 'Responsável'); ?></span>
      </div>
    </div>

    <div class="content-container">
   