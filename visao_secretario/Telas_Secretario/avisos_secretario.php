<?php
$page_title = 'Gerenciamento de Avisos';
$page_icon = 'fas fa-bell';
require_once '../templates/header_secretario.php';

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id'])) {
    $id_aviso = $_GET['delete_id'];
    $stmt = $conexao->prepare("DELETE FROM avisos WHERE id_avisos = ?");
    $stmt->bind_param("i", $id_aviso);
    if ($stmt->execute()) {
        header("Location: avisos_secretario.php?sucesso=Aviso excluído com sucesso!");
    } else {
        header("Location: avisos_secretario.php?erro=Erro ao excluir o aviso.");
    }
    $stmt->close();
    exit();
}

// --- LÓGICA DE CONSULTA ---
$sql = "SELECT id_avisos, titulo, data, urgencia, decricao FROM avisos ORDER BY data DESC";
$resultado = $conexao->query($sql);
?>

<div class="content-container">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title">Todos os Avisos</h3>
            <a href="Cadastro_Aviso.php" class="btn-cadastrar-aviso">
                <i class="fas fa-plus"></i> Novo Aviso
            </a>
        </div>
        <div class="card-body">
            <?php if(isset($_GET['sucesso'])): ?>
                <div class="alert success"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
            <?php endif; ?>

            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($aviso = $resultado->fetch_assoc()): ?>
                    <div class="notice-card">
                        <h4 class="card-title"><i class="fas fa-bullhorn"></i> <?php echo htmlspecialchars($aviso['titulo']); ?></h4>
                        <p class="card-meta">
                            <span><i class="far fa-clock"></i> <?php echo date("d/m/Y", strtotime($aviso['data'])); ?></span>
                            <span><i class="fas fa-tag"></i> Categoria: <?php echo htmlspecialchars($aviso['urgencia']); ?></span>
                        </p>
                        <p class="card-text"><?php echo htmlspecialchars($aviso['decricao']); ?></p>
                        <div class="card-actions">
                             <a href="Cadastro_Aviso.php?id=<?php echo $aviso['id_avisos']; ?>" class="btn btn-secondary"><i class="fas fa-edit"></i> Editar</a>
                             <a href="avisos_secretario.php?delete_id=<?php echo $aviso['id_avisos']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este aviso?');"><i class="fas fa-trash"></i> Excluir</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum aviso cadastrado no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>