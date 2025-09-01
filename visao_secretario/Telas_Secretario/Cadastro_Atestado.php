<?php
$page_title = 'Cadastro de Atestado';
$page_icon = 'fas fa-file-medical';
require_once '../templates/header_secretario.php';

// Inicializa variáveis
$atestado = ['ID_Atestado' => null, 'ID_Aluno' => '', 'Data_Atestado' => '', 'periodoFim' => '', 'Motivo' => ''];
$is_edit_mode = false;

// Modo Edição
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Atestado';
    $id_atestado = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM Atestados WHERE ID_Atestado = ?");
    $stmt->bind_param("i", $id_atestado);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $atestado = $result->fetch_assoc();
    $stmt->close();
}

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atestado = $_POST['id_atestado'] ?: null;
    $id_aluno = $_POST['id_aluno'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $motivo = $_POST['motivo'];
    // Assumindo que o anexo é tratado em outra lógica por enquanto
    $anexos_id_anexos = 1; // Valor placeholder

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

// Busca alunos para o dropdown
$alunos = $conexao->query("SELECT ID_Aluno, Nome FROM Alunos ORDER BY Nome");
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Atestado.php<?php echo $is_edit_mode ? '?id=' . $atestado['ID_Atestado'] : ''; ?>">
        <input type="hidden" name="id_atestado" value="<?php echo $atestado['ID_Atestado']; ?>">
        <h3 class="section-title">Dados do Atestado</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="id_aluno">Aluno*</label>
                <select id="id_aluno" name="id_aluno" required>
                    <option value="">Selecione o aluno</option>
                    <?php while($aluno_item = $alunos->fetch_assoc()): ?>
                        <option value="<?php echo $aluno_item['ID_Aluno']; ?>" <?php echo ($atestado['ID_Aluno'] == $aluno_item['ID_Aluno']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($aluno_item['Nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="data_inicio">Período (Início)*</label>
                <input type="date" id="data_inicio" name="data_inicio" value="<?php echo htmlspecialchars($atestado['Data_Atestado']); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Período (Fim)*</label>
                <input type="date" id="data_fim" name="data_fim" value="<?php echo htmlspecialchars($atestado['periodoFim']); ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="motivo">Motivo*</label>
            <textarea id="motivo" name="motivo" placeholder="Descreva o motivo do atestado" required><?php echo htmlspecialchars($atestado['Motivo']); ?></textarea>
        </div>
        <div class="form-actions">
            <a href="Listagem_Atestado.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Atestado</button>
        </div>
    </form>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>