<?php
$page_title = 'Cadastro de Sala';
$page_icon = 'fas fa-door-open';
require_once '../templates/header_secretario.php';

$sala = ['ID_Sala' => null, 'Numero' => '', 'Capacidade' => '', 'status' => 'Disponível'];
$is_edit_mode = false;

// Modo Edição
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Sala';
    $id_sala = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM Salas WHERE ID_Sala = ?");
    $stmt->bind_param("i", $id_sala);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $sala = $result->fetch_assoc();
    $stmt->close();
}

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sala = $_POST['id_sala'] ?: null;
    $numero = $_POST['numero'];
    $capacidade = $_POST['capacidade'];
    $status = $_POST['status'];

    if ($id_sala) {
        $stmt = $conexao->prepare("UPDATE Salas SET Numero = ?, Capacidade = ?, status = ? WHERE ID_Sala = ?");
        $stmt->bind_param("sisi", $numero, $capacidade, $status, $id_sala);
    } else {
        $stmt = $conexao->prepare("INSERT INTO Salas (Numero, Capacidade, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $numero, $capacidade, $status);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Sala.php?sucesso=Sala salva com sucesso!");
    } else {
        header("Location: Cadastro_Salas.php?erro=Erro ao salvar sala.");
    }
    $stmt->close();
    exit();
}
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Salas.php<?php echo $is_edit_mode ? '?id=' . $sala['ID_Sala'] : ''; ?>">
        <input type="hidden" name="id_sala" value="<?php echo $sala['ID_Sala']; ?>">
        <h3 class="section-title">Dados da Sala</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="numero">Número da Sala*</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($sala['Numero']); ?>" required>
            </div>
            <div class="form-group">
                <label for="capacidade">Capacidade*</label>
                <input type="number" id="capacidade" name="capacidade" value="<?php echo htmlspecialchars($sala['Capacidade']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="status">Status*</label>
                <select id="status" name="status" required>
                    <option value="Disponível" <?php echo ($sala['status'] == 'Disponível') ? 'selected' : ''; ?>>Disponível</option>
                    <option value="Em Manutenção" <?php echo ($sala['status'] == 'Em Manutenção') ? 'selected' : ''; ?>>Em Manutenção</option>
                    <option value="Ocupada" <?php echo ($sala['status'] == 'Ocupada') ? 'selected' : ''; ?>>Ocupada</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <a href="Listagem_Sala.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Sala</button>
        </div>
    </form>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>