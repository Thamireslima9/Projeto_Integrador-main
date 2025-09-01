<?php
// Define a constante PROJECT_ROOT e inclui o cabeçalho
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(dirname(__DIR__)));
}
$page_title = 'Cadastro de Aluno';
$page_icon = 'fas fa-user-graduate';
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa um array de aluno com valores padrão
$aluno = [
    'ID_Aluno' => null, 'Nome' => '', 'Data_Nascimento' => '', 'RG' => '', 'CPF' => '',
    'Contato_responsavel' => '', 'Email_responsavel' => '', 'Turmas_ID_Turma' => '', 'Endereco' => ''
];
$is_edit_mode = false;

// --- MODO EDIÇÃO ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $is_edit_mode = true;
    $id_aluno = $_GET['id'];
    $page_title = "Editar Aluno"; 

    $stmt = $conexao->prepare("SELECT * FROM Alunos WHERE ID_Aluno = ?");
    $stmt->bind_param("i", $id_aluno);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $aluno = $result->fetch_assoc();
    }
    $stmt->close();
}

// --- PROCESSAMENTO DO FORMULÁRIO (SALVAR DADOS) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_aluno = $_POST['id_aluno'] ?: null;
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $contato_responsavel = $_POST['contato_responsavel'];
    $email_responsavel = $_POST['email_responsavel'];
    $turma_id = $_POST['turma_id'];
    $endereco = $_POST['endereco']; // Campo único de endereço

    if ($id_aluno) {
        // LÓGICA DE UPDATE
        $sql = "UPDATE Alunos SET Nome=?, Data_Nascimento=?, RG=?, CPF=?, Turmas_ID_Turma=?, Contato_responsavel=?, Email_responsavel=?, Endereco=? WHERE ID_Aluno = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssisssi", $nome, $data_nascimento, $rg, $cpf, $turma_id, $contato_responsavel, $email_responsavel, $endereco, $id_aluno);
    } else {
        // LÓGICA DE INSERT
        $sql = "INSERT INTO Alunos (Nome, Data_Nascimento, RG, CPF, Turmas_ID_Turma, Contato_responsavel, Email_responsavel, Endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssisss", $nome, $data_nascimento, $rg, $cpf, $turma_id, $contato_responsavel, $email_responsavel, $endereco);
    }

    if ($stmt->execute()) {
        header("Location: Listagem_Alunos.php?sucesso=Aluno salvo com sucesso!");
    } else {
        header("Location: Cadastro_Alunos.php?erro=Erro ao salvar aluno: " . $stmt->error);
    }
    $stmt->close();
    exit();
}

// Busca as turmas para o dropdown
$turmas_result = $conexao->query("SELECT ID_Turma, Nome_Turma FROM Turmas ORDER BY Nome_Turma");
?>

<div class="form-container">
    <form method="POST" action="Cadastro_Alunos.php<?php echo $is_edit_mode ? '?id=' . htmlspecialchars($aluno['ID_Aluno'] ?? '') : ''; ?>">
        <input type="hidden" name="id_aluno" value="<?php echo htmlspecialchars($aluno['ID_Aluno'] ?? ''); ?>">

        <h3 class="section-title">Dados do Aluno</h3>
        <div class="form-row">
            <div class="form-group"><label for="nome">Nome*</label><input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['Nome'] ?? ''); ?>" required></div>
            <div class="form-group"><label for="rg">RG</label><input type="text" id="rg" name="rg" value="<?php echo htmlspecialchars($aluno['RG'] ?? ''); ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="cpf">CPF</label><input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($aluno['CPF'] ?? ''); ?>"></div>
            <div class="form-group"><label for="data_nascimento">Data de Nascimento*</label><input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($aluno['Data_Nascimento'] ?? ''); ?>" required></div>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço Completo</label>
            <input type="text" id="endereco" name="endereco" placeholder="Rua, Número, Bairro, Cidade - Estado, CEP" value="<?php echo htmlspecialchars($aluno['Endereco'] ?? ''); ?>">
        </div>

        <h3 class="section-title">Dados da Matrícula</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="turma_id">Turma*</label>
                <select id="turma_id" name="turma_id" required>
                    <option value="">Selecione a turma</option>
                    <?php if ($turmas_result->num_rows > 0) {
                        while($turma = $turmas_result->fetch_assoc()): ?>
                            <option value="<?php echo $turma['ID_Turma']; ?>" <?php echo (($aluno['Turmas_ID_Turma'] ?? '') == $turma['ID_Turma']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($turma['Nome_Turma']); ?>
                            </option>
                        <?php endwhile; } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
             <div class="form-group">
                <label for="contato_responsavel">Telefone (Responsável)*</label>
                <input type="tel" id="contato_responsavel" name="contato_responsavel" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($aluno['Contato_responsavel'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email_responsavel">E-mail (Responsável)*</label>
                <input type="email" id="email_responsavel" name="email_responsavel" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($aluno['Email_responsavel'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="Listagem_Alunos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </form>
</div>

<?php
require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php';
?>