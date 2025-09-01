<?php
$page_title = 'Cadastro de Responsável';
$page_icon = 'fas fa-user-tie';
require_once '../templates/header_secretario.php';

// Inicializa variáveis
$responsavel = ['id' => null, 'nome' => '', 'cpf' => '', 'rg' => '', 'datanasc' => '', 'email' => ''];
$is_edit_mode = false;

// Modo Edição
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $page_title = 'Editar Responsável';
    $id_responsavel = $_GET['id'];
    // Busca na tabela 'usuario'
    $stmt = $conexao->prepare("SELECT id, nome, cpf, rg, datanasc, email FROM usuario WHERE id = ? AND tipo_idtipo = 5");
    $stmt->bind_param("i", $id_responsavel);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $responsavel = $result->fetch_assoc();
    $stmt->close();
}

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_responsavel = $_POST['id_responsavel'] ?: null;
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $datanasc = $_POST['datanasc'];
    $email = $_POST['email'];
    $tipo_id = 5; // ID fixo para 'responsavel'

    if ($id_responsavel) {
        // UPDATE
        $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, cpf = ?, rg = ?, datanasc = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $cpf, $rg, $datanasc, $email, $id_responsavel);
    } else {
        // INSERT
        // Precisamos gerar um ID único para a tabela 'usuario'
        $result_max_id = $conexao->query("SELECT MAX(id) as max_id FROM usuario");
        $max_id_row = $result_max_id->fetch_assoc();
        $novo_id = ($max_id_row['max_id'] ?? 0) + 1;

        $stmt = $conexao->prepare("INSERT INTO usuario (id, nome, cpf, rg, datanasc, email, tipo_idtipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssi", $novo_id, $nome, $cpf, $rg, $datanasc, $email, $tipo_id);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Responsavel.php?sucesso=Responsável salvo com sucesso!");
    } else {
        header("Location: Cadastro_Responsavel.php?erro=Erro ao salvar responsável. Verifique se o CPF já não está cadastrado.");
    }
    $stmt->close();
    exit();
}
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Responsavel.php<?php echo $is_edit_mode ? '?id=' . $responsavel['id'] : ''; ?>">
        <input type="hidden" name="id_responsavel" value="<?php echo $responsavel['id']; ?>">
        <h3 class="section-title">Dados do Responsável</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($responsavel['nome']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="responsavel-cpf">CPF*</label>
                <input type="text" id="responsavel-cpf" name="cpf" placeholder="000.000.000-00" value="<?php echo htmlspecialchars($responsavel['cpf']); ?>" required>
            </div>
             <div class="form-group">
                <label for="responsavel-rg">RG</label>
                <input type="text" id="responsavel-rg" name="rg" placeholder="Número do RG" value="<?php echo htmlspecialchars($responsavel['rg']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="datanasc">Data de Nascimento</label>
                <input type="date" id="datanasc" name="datanasc" value="<?php echo htmlspecialchars($responsavel['datanasc']); ?>">
            </div>
            <div class="form-group">
                <label for="responsavel-email">E-mail*</label>
                <input type="email" id="responsavel-email" name="email" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($responsavel['email']); ?>" required>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="Listagem_Responsavel.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </form>
</div>

<?php require_once '../templates/footer_secretario.php'; ?>