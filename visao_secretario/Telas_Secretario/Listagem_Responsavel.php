<?php
// Define a constante que aponta para a pasta raiz do projeto
define('PROJECT_ROOT', dirname(dirname(__DIR__)));

// Define o título e o ícone da página
$page_title = 'Cadastro de Responsáveis';
$page_icon = 'fas fa-user-tie';

// Inclui o cabeçalho do template
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';


// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $id_para_deletar = $_GET['delete_id'];
    
    $conexao->begin_transaction();
    try {
        $stmt1 = $conexao->prepare("DELETE FROM Alunos_Responsaveis WHERE Id_usuario = ?");
        $stmt1->bind_param("i", $id_para_deletar);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conexao->prepare("DELETE FROM endereco WHERE usuario_id = ?");
        $stmt2->bind_param("i", $id_para_deletar);
        $stmt2->execute();
        $stmt2->close();
        
        $stmt3 = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt3->bind_param("i", $id_para_deletar);
        $stmt3->execute();
        $stmt3->close();

        $conexao->commit();
        header("Location: Listagem_Responsavel.php?sucesso=Responsável excluído com sucesso!");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: Listagem_Responsavel.php?erro=Erro ao excluir responsável.");
    }
    exit();
}

// --- LÓGICA DE CONSULTA CORRIGIDA ---
// Assumindo que o tipo 'responsavel' tem o idtipo = 5
$sql = "SELECT id, nome, cpf, email, telefone FROM usuario WHERE tipo_idtipo = 5 ORDER BY nome ASC";
$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro na consulta SQL: " . $conexao->error);
}
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
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($responsavel = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($responsavel['nome']); ?></td>
                        <td><?php echo htmlspecialchars($responsavel['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($responsavel['email']); ?></td>
                        <td><?php echo htmlspecialchars($responsavel['telefone'] ?? 'N/D'); ?></td>
                        <td class="action-buttons">
                            <a href="Cadastro_Responsavel.php?id=<?php echo $responsavel['id']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="Listagem_Responsavel.php?delete_id=<?php echo $responsavel['id']; ?>" class="btn-icon" title="Excluir" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum responsável cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php'; ?>