<?php
$page_title = 'Listagem de Atestados';
$page_icon = 'fas fa-file-medical';
require_once '../templates/header_secretario.php';

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id'])) {
    $id_atestado = $_GET['delete_id'];
    $stmt = $conexao->prepare("DELETE FROM Atestados WHERE ID_Atestado = ?");
    $stmt->bind_param("i", $id_atestado);
    if ($stmt->execute()) {
        header("Location: Listagem_Atestado.php?sucesso=Atestado excluído com sucesso!");
    } else {
        header("Location: Listagem_Atestado.php?erro=Erro ao excluir o atestado.");
    }
    $stmt->close();
    exit();
}

// --- LÓGICA DE CONSULTA ---
$sql = "SELECT atestado.ID_Atestado, aluno.Nome as Nome_Aluno, atestado.Data_Atestado, atestado.periodoFim, atestado.Motivo
        FROM Atestados as atestado
        JOIN Alunos as aluno ON atestado.ID_Aluno = aluno.ID_Aluno
        ORDER BY atestado.Data_Atestado DESC";
$resultado = $conexao->query($sql);
?>

<div class="table-container">
    <div class="table-settings">
        <a href="Cadastro_Atestado.php" class="btn-cadastrar"><i class="fas fa-plus"></i> Cadastrar Novo Atestado</a>
    </div>

    <?php if(isset($_GET['sucesso'])): ?>
        <div class="alert success" style="margin-top: 15px;"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Motivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($atestado = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($atestado['Nome_Aluno']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($atestado['Data_Atestado'])); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($atestado['periodoFim'])); ?></td>
                        <td><?php echo htmlspecialchars($atestado['Motivo']); ?></td>
                        <td class="action-buttons">
                            <a href="Cadastro_Atestado.php?id=<?php echo $atestado['ID_Atestado']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="Listagem_Atestado.php?delete_id=<?php echo $atestado['ID_Atestado']; ?>" class="btn-icon" title="Excluir" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum atestado cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>