<?php
$page_title = 'Registrar Ocorrência';
$page_icon = 'fas fa-exclamation-triangle';
require_once '../templates/header_secretario.php';

// Inicializa variáveis
$ocorrencia = ['ID_Ocorrencia' => null, 'Alunos_ID_Aluno' => '', 'Data_Ocorrencia' => date('Y-m-d'), 'Tipo' => '', 'Descricao' => ''];
$is_edit_mode = false;

// Modo Edição
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Ocorrência';
    $id_ocorrencia = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM Ocorrencias WHERE ID_Ocorrencia = ?");
    $stmt->bind_param("i", $id_ocorrencia);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $ocorrencia = $result->fetch_assoc();
    $stmt->close();
}

// Processamento do Formulário
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

// Busca alunos para o dropdown
$alunos = $conexao->query("SELECT ID_Aluno, Nome FROM Alunos ORDER BY Nome");
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Ocorrencia.php<?php echo $is_edit_mode ? '?id=' . $ocorrencia['ID_Ocorrencia'] : ''; ?>">
        <input type="hidden" name="id_ocorrencia" value="<?php echo $ocorrencia['ID_Ocorrencia']; ?>">
        <h3 class="section-title">Dados da Ocorrência</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="id_aluno">Aluno*</label>
                <select id="id_aluno" name="id_aluno" required>
                    <option value="">Selecione o aluno</option>
                    <?php while($aluno_item = $alunos->fetch_assoc()): ?>
                        <option value="<?php echo $aluno_item['ID_Aluno']; ?>" <?php echo ($ocorrencia['Alunos_ID_Aluno'] == $aluno_item['ID_Aluno']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($aluno_item['Nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
             <div class="form-group">
                <label for="data">Data da Ocorrência*</label>
                <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($ocorrencia['Data_Ocorrencia']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="tipo">Tipo de Ocorrência*</label>
                <select id="tipo" name="tipo" required>
                    <option value="Saúde" <?php echo ($ocorrencia['Tipo'] == 'Saúde') ? 'selected' : ''; ?>>Saúde</option>
                    <option value="Comportamento" <?php echo ($ocorrencia['Tipo'] == 'Comportamento') ? 'selected' : ''; ?>>Comportamento</option>
                    <option value="Incidente" <?php echo ($ocorrencia['Tipo'] == 'Incidente') ? 'selected' : ''; ?>>Incidente</option>
                    <option value="Pedagógico" <?php echo ($ocorrencia['Tipo'] == 'Pedagógico') ? 'selected' : ''; ?>>Pedagógico</option>
                    <option value="Outro" <?php echo ($ocorrencia['Tipo'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição*</label>
            <textarea id="descricao" name="descricao" placeholder="Descreva a ocorrência detalhadamente" required><?php echo htmlspecialchars($ocorrencia['Descricao']); ?></textarea>
        </div>
        <div class="form-actions">
            <a href="Listagem_Ocorrencia.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Ocorrência</button>
        </div>
    </form>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>