<?php
// Define o título e o ícone da página
$page_title = 'Cadastro de Aluno';
$page_icon = 'fas fa-user-graduate';

// Define a constante PROJECT_ROOT
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa um array de aluno com valores padrão
$aluno = [
    'ID_Aluno' => null, 'Nome' => '', 'Data_Nascimento' => '', 'Genero' => '', 'Endereco' => '',
    'Contato_responsavel' => '', 'Turmas_ID_Turma' => '', 'RG' => '', 'CPF' => '', 'Email_responsavel' => ''
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
    // ... (A lógica de salvar que já fizemos antes continua aqui) ...
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
                <input type="text" id="student-rg" name="rg" placeholder="Número do RG" value="<?php echo htmlspecialchars($aluno['RG'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="student-cpf">CPF</label>
                <input type="text" id="student-cpf" name="cpf" placeholder="000.000.000-00" value="<?php echo htmlspecialchars($aluno['CPF'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="student-birth">Data de Nascimento*</label>
                <input type="date" id="student-birth" name="data_nascimento" value="<?php echo htmlspecialchars($aluno['Data_Nascimento'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="form-row">
             <div class="form-group">
                <label for="turma_id">Turma*</label>
                <select id="turma_id" name="turma_id" required>
                    <option value="">Selecione a turma</option>
                    <?php 
                    if ($turmas_result->num_rows > 0) {
                        while($turma = $turmas_result->fetch_assoc()): ?>
                            <option value="<?php echo $turma['ID_Turma']; ?>" <?php echo (($aluno['Turmas_ID_Turma'] ?? '') == $turma['ID_Turma']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($turma['Nome_Turma']); ?>
                            </option>
                        <?php endwhile;
                    }
                    ?>
                </select>
            </div>
        </div>

        <h3 class="section-title">Contato do Responsável</h3>

        <div class="form-row">
             <div class="form-group">
                <label for="student-phone">Telefone (Responsável)*</label>
                <input type="tel" id="student-phone" name="contato_responsavel" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($aluno['Contato_responsavel'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="student-email">E-mail (Responsável)*</label>
                <input type="email" id="student-email" name="email_responsavel" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($aluno['Email_responsavel'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="Listagem_Alunos.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar
            </button>
        </div>
    </form>
</div>

<?php
require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php';
?>