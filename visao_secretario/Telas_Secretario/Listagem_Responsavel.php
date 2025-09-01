<?php
$page_title = 'Cadastro de Responsáveis';
$page_icon = 'fas fa-user-tie';
require_once '../templates/header_secretario.php';

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id'])) {
    $id_para_deletar = $_GET['delete_id'];
    
    // ATENÇÃO: Antes de deletar um usuário, precisamos remover as associações dele
    // na tabela Alunos_Responsaveis para evitar erros de chave estrangeira.
    $stmt_assoc = $conexao->prepare("DELETE FROM Alunos_Responsaveis WHERE Id_usuario = ?");
    $stmt_assoc->bind_param("i", $id_para_deletar);
    $stmt_assoc->execute();
    $stmt_assoc->close();

    // Agora podemos deletar o usuário
    $stmt_delete = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt_delete->bind_param("i", $id_para_deletar);
    if ($stmt_delete->execute()) {
        header("Location: Listagem_Responsavel.php?sucesso=Responsável excluído com sucesso!");
    } else {
        header("Location: Listagem_Responsavel.php?erro=Erro ao excluir responsável.");
    }
    $stmt_delete->close();
    exit();
}

// --- LÓGICA DE CONSULTA ---
// Assumindo que o tipo 'responsavel' tem o idtipo = 5
$sql = "SELECT id, nome, cpf, email FROM usuario WHERE tipo_idtipo = 5 ORDER BY nome ASC";
$resultado = $conexao->query($sql);
?>

<div class="table-container">
    <div class="table-settings">
        <a href="Cadastro_Responsavel.php" class="btn-cadastrar"><i class="fas fa-plus"></i> Cadastrar Novo Responsável</a>
    </div>

    <?php if(isset($_GET['sucesso'])): ?>
        <div class="alert success" style="margin-top: 15px;"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['erro'])): ?>
        <div class="alert error" style="margin-top: 15px;"><?php echo htmlspecialchars($_GET['erro']); ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($responsavel = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($responsavel['nome']); ?></td>
                        <td><?php echo htmlspecialchars($responsavel['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($responsavel['email']); ?></td>
                        <td class="action-buttons">
                            <a href="Cadastro_Responsavel.php?id=<?php echo $responsavel['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="Listagem_Responsavel.php?delete_id=<?php echo $responsavel['id']; ?>" class="btn-icon" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este responsável? A ação não pode ser desfeita.');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Nenhum responsável cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>