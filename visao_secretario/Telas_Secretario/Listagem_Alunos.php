<?php
// Define a constante que aponta para a pasta raiz do projeto
define('PROJECT_ROOT', dirname(dirname(__DIR__)));

// Define o título e o ícone da página
$page_title = 'Cadastro de Alunos';
$page_icon = 'fas fa-user-graduate';

// Inclui o cabeçalho do template
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $id_para_deletar = $_GET['delete_id'];
    $stmt_delete = $conexao->prepare("DELETE FROM Alunos WHERE ID_Aluno = ?");
    $stmt_delete->bind_param("i", $id_para_deletar);
    if ($stmt_delete->execute()) {
        header("Location: Listagem_Alunos.php?sucesso=Aluno excluído com sucesso!");
    } else {
        header("Location: Listagem_Alunos.php?erro=Erro ao excluir o aluno.");
    }
    $stmt_delete->close();
    exit();
}

// --- LÓGICA DE CONSULTA CORRIGIDA ---
// Seleciona apenas as colunas que existem na sua tabela
$sql = "SELECT a.ID_Aluno, a.Nome, a.CPF, a.Contato_responsavel, t.Nome_Turma 
        FROM Alunos a 
        LEFT JOIN Turmas t ON a.Turmas_ID_Turma = t.ID_Turma 
        ORDER BY a.Nome ASC";
$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro na consulta SQL: " . $conexao->error);
}
?>

<div class="table-container">
    <div class="table-settings">
        <a href="Cadastro_Alunos.php" class="btn-cadastrar">
            <i class="fas fa-plus"></i> Cadastrar Novo Aluno
        </a>
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
                <th>Nome do Aluno</th>
                <th>CPF</th>
                <th>Contato do Responsável</th>
                <th>Turma</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($aluno = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aluno['Nome']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['CPF'] ?? 'Não informado'); ?></td>
                        <td><?php echo htmlspecialchars($aluno['Contato_responsavel']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['Nome_Turma'] ?? 'Sem turma'); ?></td>
                        <td class="action-buttons">
                            <a href="Cadastro_Alunos.php?id=<?php echo $aluno['ID_Aluno']; ?>" class="btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="Listagem_Alunos.php?delete_id=<?php echo $aluno['ID_Aluno']; ?>" class="btn-icon" title="Excluir" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum aluno cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php';
?>