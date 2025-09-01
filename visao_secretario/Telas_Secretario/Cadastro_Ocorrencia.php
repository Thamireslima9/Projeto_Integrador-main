<?php
// Define a constante PROJECT_ROOT e inclui o cabeçalho
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
$page_title = 'Registrar Ocorrência';
$page_icon = 'fas fa-exclamation-triangle';
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa variáveis
$ocorrencia = ['ID_Ocorrencia' => null, 'Alunos_ID_Aluno' => '', 'Data_Ocorrencia' => date('Y-m-d'), 'Tipo' => '', 'Descricao' => ''];
$turma_do_aluno_selecionado = null;
$is_edit_mode = false;

// --- MODO EDIÇÃO ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Ocorrência';
    $id_ocorrencia = $_GET['id'];
    
    // Busca a ocorrência e a turma do aluno associado
    $stmt = $conexao->prepare(
        "SELECT o.*, a.Turmas_ID_Turma 
         FROM Ocorrencias o
         JOIN Alunos a ON o.Alunos_ID_Aluno = a.ID_Aluno
         WHERE o.ID_Ocorrencia = ?"
    );
    $stmt->bind_param("i", $id_ocorrencia);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $ocorrencia = $result->fetch_assoc();
        $turma_do_aluno_selecionado = $ocorrencia['Turmas_ID_Turma'];
    }
    $stmt->close();
}

// --- PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ocorrencia = $_POST['id_ocorrencia'] ?: null;
    $id_aluno = $_POST['id_aluno'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];

    if ($id_ocorrencia) {
        $stmt = $conexao->prepare("UPDATE Ocorrencias SET Alunos_ID_Aluno = ?, Data_Ocorrencia = ?, Tipo = ?, Descricao = ? WHERE ID_Ocorrencia = ?");
        $stmt->bind_param("isssi", $id_aluno, $data, $tipo, $descricao, $id_ocorrencia);
    } else {
        $stmt = $conexao->prepare("INSERT INTO Ocorrencias (Alunos_ID_Aluno, Data_Ocorrencia, Tipo, Descricao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_aluno, $data, $tipo, $descricao);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Ocorrencia.php?sucesso=Ocorrência salva com sucesso!");
    } else {
        header("Location: Cadastro_Ocorrencia.php?erro=Erro ao salvar ocorrência.");
    }
    $stmt->close();
    exit();
}

// --- BUSCA DE DADOS PARA DROPDOWNS ---
$turmas_result = $conexao->query("SELECT ID_Turma, Nome_Turma FROM Turmas ORDER BY Nome_Turma");

$alunos_result = $conexao->query("SELECT ID_Aluno, Nome, Turmas_ID_Turma FROM Alunos ORDER BY Nome");
$alunos_por_turma = [];
if ($alunos_result->num_rows > 0) {
    while($aluno_item = $alunos_result->fetch_assoc()) {
        $alunos_por_turma[$aluno_item['Turmas_ID_Turma']][] = $aluno_item;
    }
}
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Ocorrencia.php<?php echo $is_edit_mode ? '?id=' . htmlspecialchars($ocorrencia['ID_Ocorrencia'] ?? '') : ''; ?>">
        <input type="hidden" name="id_ocorrencia" value="<?php echo htmlspecialchars($ocorrencia['ID_Ocorrencia'] ?? ''); ?>">
        
        <h3 class="section-title">Dados da Ocorrência</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="turma_select">Filtrar por Turma*</label>
                <select id="turma_select">
                    <option value="">Selecione uma turma primeiro</option>
                    <?php if ($turmas_result->num_rows > 0) {
                        $turmas_result->data_seek(0);
                        while($turma = $turmas_result->fetch_assoc()): ?>
                            <option value="<?php echo $turma['ID_Turma']; ?>" <?php echo ($turma_do_aluno_selecionado == $turma['ID_Turma']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($turma['Nome_Turma']); ?>
                            </option>
                        <?php endwhile; } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_aluno">Aluno*</label>
                <select id="id_aluno" name="id_aluno" required <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                    <option value="">Selecione a turma para ver os alunos</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data">Data da Ocorrência*</label>
                <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($ocorrencia['Data_Ocorrencia'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Ocorrência*</label>
                <select id="tipo" name="tipo" required>
                    <option value="Saúde" <?php echo (($ocorrencia['Tipo'] ?? '') == 'Saúde') ? 'selected' : ''; ?>>Saúde</option>
                    <option value="Comportamento" <?php echo (($ocorrencia['Tipo'] ?? '') == 'Comportamento') ? 'selected' : ''; ?>>Comportamento</option>
                    <option value="Incidente" <?php echo (($ocorrencia['Tipo'] ?? '') == 'Incidente') ? 'selected' : ''; ?>>Incidente</option>
                    <option value="Pedagógico" <?php echo (($ocorrencia['Tipo'] ?? '') == 'Pedagógico') ? 'selected' : ''; ?>>Pedagógico</option>
                    <option value="Outro" <?php echo (($ocorrencia['Tipo'] ?? '') == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição*</label>
            <textarea id="descricao" name="descricao" placeholder="Descreva a ocorrência detalhadamente" required><?php echo htmlspecialchars($ocorrencia['Descricao'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <a href="Listagem_Ocorrencia.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Ocorrência</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alunosPorTurma = <?php echo json_encode($alunos_por_turma); ?>;
    const turmaSelect = document.getElementById('turma_select');
    const alunoSelect = document.getElementById('id_aluno');
    
    function popularAlunos() {
        const turmaId = turmaSelect.value;
        alunoSelect.innerHTML = '<option value="">Selecione o aluno</option>';

        if (turmaId && alunosPorTurma[turmaId]) {
            alunoSelect.disabled = false;
            alunosPorTurma[turmaId].forEach(function(aluno) {
                const option = document.createElement('option');
                option.value = aluno.ID_Aluno;
                option.textContent = aluno.Nome;
                alunoSelect.appendChild(option);
            });
        } else {
            alunoSelect.disabled = true;
        }
    }

    turmaSelect.addEventListener('change', popularAlunos);
    
    <?php if ($is_edit_mode && $turma_do_aluno_selecionado): ?>
        popularAlunos();
        alunoSelect.value = "<?php echo htmlspecialchars($ocorrencia['Alunos_ID_Aluno'] ?? ''); ?>";
    <?php endif; ?>
});
</script>

<?php require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php'; ?>