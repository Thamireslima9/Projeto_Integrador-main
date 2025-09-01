<?php
$page_title = 'Cadastro de Turma';
$page_icon = 'fas fa-layer-group';
require_once '../templates/header_secretario.php';

// Inicializa variáveis
$turma = ['ID_Turma' => null, 'Nome_Turma' => '', 'Turno' => '', 'ID_Sala' => '', 'prof_id' => ''];
$is_edit_mode = false;

// Modo Edição
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Turma';
    $id_turma = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM Turmas WHERE ID_Turma = ?");
    $stmt->bind_param("i", $id_turma);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $turma = $result->fetch_assoc();
    $stmt->close();
}

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turma = $_POST['id_turma'] ?: null;
    $nome_turma = $_POST['nome_turma'];
    $turno = $_POST['turno'];
    $id_sala = $_POST['id_sala'];
    $id_prof = $_POST['prof_id'];

    if ($id_turma) {
        // UPDATE
        $stmt = $conexao->prepare("UPDATE Turmas SET Nome_Turma = ?, Turno = ?, ID_Sala = ?, prof_id = ? WHERE ID_Turma = ?");
        $stmt->bind_param("ssiii", $nome_turma, $turno, $id_sala, $id_prof, $id_turma);
    } else {
        // INSERT
        $stmt = $conexao->prepare("INSERT INTO Turmas (Nome_Turma, Turno, ID_Sala, prof_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nome_turma, $turno, $id_sala, $id_prof);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Turma.php?sucesso=Turma salva com sucesso!");
    } else {
        header("Location: Cadastro_Turma.php?erro=Erro ao salvar turma.");
    }
    $stmt->close();
    exit();
}

// Busca de dados para os dropdowns
$salas = $conexao->query("SELECT ID_Sala, Numero FROM Salas ORDER BY Numero");
// Supondo que o tipo 'professor' tenha idtipo = 3 na sua tabela 'tipo'
$professores = $conexao->query("SELECT id, nome FROM usuario WHERE tipo_idtipo = 3 ORDER BY nome"); 
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Turma.php<?php echo $is_edit_mode ? '?id=' . $turma['ID_Turma'] : ''; ?>">
        <input type="hidden" name="id_turma" value="<?php echo $turma['ID_Turma']; ?>">
        <h3 class="section-title">Dados da Turma</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="nome_turma">Nome da Turma*</label>
                <input type="text" id="nome_turma" name="nome_turma" value="<?php echo htmlspecialchars($turma['Nome_Turma']); ?>" required>
            </div>
            <div class="form-group">
                <label for="turno">Turno*</label>
                <select id="turno" name="turno" required>
                    <option value="Manhã" <?php echo ($turma['Turno'] == 'Manhã') ? 'selected' : ''; ?>>Manhã</option>
                    <option value="Tarde" <?php echo ($turma['Turno'] == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
                    <option value="Integral" <?php echo ($turma['Turno'] == 'Integral') ? 'selected' : ''; ?>>Integral</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="id_sala">Sala Designada*</label>
                <select id="id_sala" name="id_sala" required>
                    <option value="">Selecione a sala</option>
                    <?php while($sala = $salas->fetch_assoc()): ?>
                        <option value="<?php echo $sala['ID_Sala']; ?>" <?php echo ($turma['ID_Sala'] == $sala['ID_Sala']) ? 'selected' : ''; ?>>
                            Sala <?php echo htmlspecialchars($sala['Numero']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="prof_id">Professor Responsável*</label>
                <select id="prof_id" name="prof_id" required>
                     <option value="">Selecione o professor</option>
                    <?php while($prof = $professores->fetch_assoc()): ?>
                        <option value="<?php echo $prof['id']; ?>" <?php echo ($turma['prof_id'] == $prof['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prof['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <a href="Listagem_Turma.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </form>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>