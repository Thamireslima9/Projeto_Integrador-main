<?php
// Define o título e o ícone da página
$page_title = 'Cadastro de Aluno';
$page_icon = 'fas fa-user-graduate';

// Define a constante PROJECT_ROOT e inclui o cabeçalho
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa um array de aluno com todos os campos
$aluno = [
    'ID_Aluno' => null, 'Nome' => '', 'Data_Nascimento' => '', 'RG' => '', 'CPF' => '',
    'Contato_responsavel' => '', 'Email_responsavel' => '', 'Turmas_ID_Turma' => '',
    'CEP' => '', 'Logradouro' => '', 'Numero' => '', 'Complemento' => '', 'Bairro' => '', 'Cidade' => '', 'Estado' => ''
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
    $data_nascimento = $_POST['student-birth'];
    $rg = $_POST['student-rg'];
    $cpf = $_POST['student-cpf'];
    $contato_responsavel = $_POST['student-phone'];
    $email_responsavel = $_POST['student-email'];
    $turma_id = $_POST['turma_id'];
    $cep = $_POST['student-cep'];
    $logradouro = $_POST['student-address'];
    $numero = $_POST['student-number'];
    $complemento = $_POST['student-complement'];
    $bairro = $_POST['student-district'];
    $cidade = $_POST['student-city'];
    $estado = $_POST['student-state'];

    if ($id_aluno) {
        // LÓGICA DE UPDATE
        $sql = "UPDATE Alunos SET Nome=?, Data_Nascimento=?, RG=?, CPF=?, Turmas_ID_Turma=?, Contato_responsavel=?, Email_responsavel=?, CEP=?, Logradouro=?, Numero=?, Complemento=?, Bairro=?, Cidade=?, Estado=? WHERE ID_Aluno = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssisssssssssi", $nome, $data_nascimento, $rg, $cpf, $turma_id, $contato_responsavel, $email_responsavel, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $id_aluno);
    } else {
        // LÓGICA DE INSERT
        $sql = "INSERT INTO Alunos (Nome, Data_Nascimento, RG, CPF, Turmas_ID_Turma, Contato_responsavel, Email_responsavel, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, Estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssisssssssss", $nome, $data_nascimento, $rg, $cpf, $turma_id, $contato_responsavel, $email_responsavel, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $estado);
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
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" placeholder="Nome completo" value="<?php echo htmlspecialchars($aluno['Nome'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="student-rg">RG</label>
                <input type="text" id="student-rg" name="student-rg" placeholder="Número do RG" value="<?php echo htmlspecialchars($aluno['RG'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="student-cpf">CPF</label>
                <input type="text" id="student-cpf" name="student-cpf" placeholder="000.000.000-00" value="<?php echo htmlspecialchars($aluno['CPF'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="student-birth">Data de Nascimento*</label>
                <input type="date" id="student-birth" name="student-birth" value="<?php echo htmlspecialchars($aluno['Data_Nascimento'] ?? ''); ?>" required>
            </div>
        </div>
         <div class="form-row">
            <div class="form-group">
                <label for="student-phone">Telefone (Responsável)*</label>
                <input type="tel" id="student-phone" name="student-phone" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($aluno['Contato_responsavel'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="student-email">E-mail (Responsável)*</label>
                <input type="email" id="student-email" name="student-email" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($aluno['Email_responsavel'] ?? ''); ?>" required>
            </div>
        </div>
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

        <h3 class="section-title">Endereço</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="student-cep">CEP</label>
                <input type="text" id="student-cep" name="student-cep" placeholder="00000-000" value="<?php echo htmlspecialchars($aluno['CEP'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="student-address">Logradouro</label>
                <input type="text" id="student-address" name="student-address" placeholder="Rua, Avenida, etc." value="<?php echo htmlspecialchars($aluno['Logradouro'] ?? ''); ?>">
            </div>
             <div class="form-group">
                <label for="student-number">Número</label>
                <input type="text" id="student-number" name="student-number" placeholder="Número" value="<?php echo htmlspecialchars($aluno['Numero'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
             <div class="form-group">
                <label for="student-complement">Complemento</label>
                <input type="text" id="student-complement" name="student-complement" placeholder="Complemento" value="<?php echo htmlspecialchars($aluno['Complemento'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="student-district">Bairro</label>
                <input type="text" id="student-district" name="student-district" placeholder="Bairro" value="<?php echo htmlspecialchars($aluno['Bairro'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
             <div class="form-group">
                <label for="student-city">Cidade</label>
                <input type="text" id="student-city" name="student-city" placeholder="Cidade" value="<?php echo htmlspecialchars($aluno['Cidade'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="student-state">Estado</label>
                <select id="student-state" name="student-state">
                    <option value="">Selecione</option>
                    <option value="SP" <?php echo (($aluno['Estado'] ?? '') == 'SP') ? 'selected' : ''; ?>>São Paulo</option>
                    <option value="RJ" <?php echo (($aluno['Estado'] ?? '') == 'RJ') ? 'selected' : ''; ?>>Rio de Janeiro</option>
                    <option value="MG" <?php echo (($aluno['Estado'] ?? '') == 'MG') ? 'selected' : ''; ?>>Minas Gerais</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="Listagem_Alunos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </form>
</div>

<?php
// Adiciona o footer.php
require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php';
?>