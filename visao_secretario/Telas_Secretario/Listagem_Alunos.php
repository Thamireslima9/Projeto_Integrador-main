<?php
// Define o título e o ícone da página
$page_title = 'Cadastro de Alunos';
$page_icon = 'fas fa-user-graduate';

// Inclui o cabeçalho do template
require_once '../templates/header_secretario.php';

// A lógica de exclusão e consulta que já fizemos antes permanece aqui
// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_id'])) {
    // ... (código de exclusão que já fizemos) ...
}

// --- LÓGICA DE CONSULTA ---
$sql = "SELECT a.ID_Aluno, a.Nome, a.CPF, a.Data_Nascimento, t.Nome_Turma 
        FROM Alunos a 
        LEFT JOIN Turmas t ON a.Turmas_ID_Turma = t.ID_Turma 
        ORDER BY a.Nome ASC";
$resultado = $conexao->query($sql);
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
                <th>Nome</th>
                <th>CPF</th>
                <th>Data Nasc.</th>
                <th>Turma</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while($aluno = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aluno['Nome']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['CPF']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($aluno['Data_Nascimento'])); ?></td>
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
// Inclui o rodapé do template
require_once '../templates/footer_secretario.php';
?>