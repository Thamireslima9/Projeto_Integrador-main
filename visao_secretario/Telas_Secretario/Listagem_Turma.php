<?php
$page_title = 'Cadastro de Turmas';
$page_icon = 'fas fa-chalkboard-teacher';
require_once '../templates/header_secretario.php';

// Lógica de Exclusão
if (isset($_GET['delete_id'])) {
    $id_turma = $_GET['delete_id'];
    $stmt = $conexao->prepare("DELETE FROM Turmas WHERE ID_Turma = ?");
    $stmt->bind_param("i", $id_turma);
    if ($stmt->execute()) {
        header("Location: Listagem_Turma.php?sucesso=Turma excluída com sucesso!");
    } else {
        header("Location: Listagem_Turma.php?erro=Erro ao excluir turma.");
    }
    $stmt->close();
    exit();
}

// Lógica para buscar as turmas no banco
$sql = "SELECT t.ID_Turma, t.Nome_Turma, t.Turno, s.Numero as Numero_Sala, u.nome as Nome_Professor
        FROM Turmas t
        LEFT JOIN Salas s ON t.ID_Sala = s.ID_Sala
        LEFT JOIN usuario u ON t.prof_id = u.id
        ORDER BY t.Nome_Turma";
$resultado = $conexao->query($sql);
?>

<div class="table-container">
    <div class="table-settings">
        <a href="Cadastro_Turma.php" class="btn-cadastrar"><i class="fas fa-plus"></i> Cadastrar Nova Turma</a>
    </div>

    <?php if(isset($_GET['sucesso'])): ?>
        <div class="alert success" style="margin-top: 15px;"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Nome da Turma</th>
                <th>Turno</th>
                <th>Sala</th>
                <th>Professor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($turma = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($turma['Nome_Turma']); ?></td>
                        <td><?php echo htmlspecialchars($turma['Turno']); ?></td>
                        <td>Sala <?php echo htmlspecialchars($turma['Numero_Sala'] ?? 'N/D'); ?></td>
                        <td><?php echo htmlspecialchars($turma['Nome_Professor'] ?? 'N/D'); ?></td>
                        <td class="action-buttons">
                            <a href="Cadastro_Turma.php?id=<?php echo $turma['ID_Turma']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="Listagem_Turma.php?delete_id=<?php echo $turma['ID_Turma']; ?>" class="btn-icon" title="Excluir" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhuma turma cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>