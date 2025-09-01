<?php
// Define a constante PROJECT_ROOT e inclui o cabeçalho
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
$page_title = 'Cadastro de Atestado';
$page_icon = 'fas fa-file-medical';
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa variáveis
$atestado = ['ID_Atestado' => null, 'ID_Aluno' => '', 'Data_Atestado' => '', 'periodoFim' => '', 'Motivo' => ''];
$turma_do_aluno_selecionado = null;
$is_edit_mode = false;

// --- MODO EDIÇÃO ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Atestado';
    $id_atestado = $_GET['id'];
    
    // Busca o atestado e a turma do aluno
    $stmt = $conexao->prepare(
        "SELECT at.*, al.Turmas_ID_Turma 
         FROM Atestados at 
         JOIN Alunos al ON at.ID_Aluno = al.ID_Aluno 
         WHERE at.ID_Atestado = ?"
    );
    $stmt->bind_param("i", $id_atestado);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $atestado = $result->fetch_assoc();
        $turma_do_aluno_selecionado = $atestado['Turmas_ID_Turma'];
    }
    $stmt->close();
}

// --- PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atestado = $_POST['id_atestado'] ?: null;
    $id_aluno = $_POST['id_aluno'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $motivo = $_POST['motivo'];
    $anexos_id_anexos = 1; // Valor placeholder para o anexo

    if ($id_atestado) {
        $stmt = $conexao->prepare("UPDATE Atestados SET ID_Aluno = ?, Data_Atestado = ?, periodoFim = ?, Motivo = ? WHERE ID_Atestado = ?");
        $stmt->bind_param("isssi", $id_aluno, $data_inicio, $data_fim, $motivo, $id_atestado);
    } else {
        $stmt = $conexao->prepare("INSERT INTO Atestados (ID_Aluno, Data_Atestado, periodoFim, Motivo, anexos_id_anexos) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $id_aluno, $data_inicio, $data_fim, $motivo, $anexos_id_anexos);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Atestado.php?sucesso=Atestado salvo com sucesso!");
    } else {
        header("Location: Cadastro_Atestado.php?erro=Erro ao salvar atestado.");
    }
    $stmt->close();
    exit();
}

// --- BUSCA DE DADOS PARA OS DROPDOWNS ---
// Busca todas as turmas
$turmas_result = $conexao->query("SELECT ID_Turma, Nome_Turma FROM Turmas ORDER BY Nome_Turma");

// Busca todos os alunos e organiza por turma para o JavaScript
$alunos_result = $conexao->query("SELECT ID_Aluno, Nome, Turmas_ID_Turma FROM Alunos ORDER BY Nome");
$alunos_por_turma = [];
if ($alunos_result->num_rows > 0) {
    while($aluno_item = $alunos_result->fetch_assoc()) {
        $alunos_por_turma[$aluno_item['Turmas_ID_Turma']][] = $aluno_item;
    }
}
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Atestado.php<?php echo $is_edit_mode ? '?id=' . htmlspecialchars($atestado['ID_Atestado'] ?? '') : ''; ?>">
        <input type="hidden" name="id_atestado" value="<?php echo htmlspecialchars($atestado['ID_Atestado'] ?? ''); ?>">
        
        <h3 class="section-title">Dados do Atestado</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="turma_select">Filtrar por Turma*</label>
                <select id="turma_select">
                    <option value="">Selecione uma turma primeiro</option>
                    <?php if ($turmas_result->num_rows > 0) {
                        $turmas_result->data_seek(0); // Reinicia o ponteiro
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
                <label for="data_inicio">Período (Início)*</label>
                <input type="date" id="data_inicio" name="data_inicio" value="<?php echo htmlspecialchars($atestado['Data_Atestado'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Período (Fim)*</label>
                <input type="date" id="data_fim" name="data_fim" value="<?php echo htmlspecialchars($atestado['periodoFim'] ?? ''); ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="motivo">Motivo*</label>
            <textarea id="motivo" name="motivo" placeholder="Descreva o motivo do atestado" required><?php echo htmlspecialchars($atestado['Motivo'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <a href="Listagem_Atestado.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Atestado</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Converte os dados dos alunos (PHP) para um objeto JavaScript
    const alunosPorTurma = <?php echo json_encode($alunos_por_turma); ?>;
    const turmaSelect = document.getElementById('turma_select');
    const alunoSelect = document.getElementById('id_aluno');
    
    // Função para popular o dropdown de alunos
    function popularAlunos() {
        const turmaId = turmaSelect.value;
        // Limpa as opções atuais
        alunoSelect.innerHTML = '<option value="">Selecione o aluno</option>';

        if (turmaId && alunosPorTurma[turmaId]) {
            // Habilita o select de alunos
            alunoSelect.disabled = false;
            // Adiciona os alunos da turma selecionada
            alunosPorTurma[turmaId].forEach(function(aluno) {
                const option = document.createElement('option');
                option.value = aluno.ID_Aluno;
                option.textContent = aluno.Nome;
                alunoSelect.appendChild(option);
            });
        } else {
            // Desabilita se nenhuma turma for selecionada
            alunoSelect.disabled = true;
        }
    }

    // Adiciona o listener de evento ao dropdown de turmas
    turmaSelect.addEventListener('change', popularAlunos);
    
    // Se estiver em modo de edição, popula e seleciona o aluno correto
    <?php if ($is_edit_mode && $turma_do_aluno_selecionado): ?>
        popularAlunos(); // Popula os alunos da turma já selecionada
        alunoSelect.value = "<?php echo htmlspecialchars($atestado['ID_Aluno'] ?? ''); ?>";
    <?php endif; ?>
});
</script>

<?php require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php'; ?>