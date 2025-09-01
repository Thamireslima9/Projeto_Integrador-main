<?php
$page_title = 'Troca de Turma';
$page_icon = 'fas fa-exchange-alt';
require_once '../templates/header_secretario.php';

// --- PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['aluno_id'];
    $nova_turma_id = $_POST['nova_turma_id'];
    
    // Validação básica
    if (empty($id_aluno) || empty($nova_turma_id)) {
        $erro = "Por favor, selecione o aluno e a nova turma.";
    } else {
        // Prepara e executa a atualização no banco de dados
        $stmt = $conexao->prepare("UPDATE Alunos SET Turmas_ID_Turma = ? WHERE ID_Aluno = ?");
        $stmt->bind_param("ii", $nova_turma_id, $id_aluno);

        if ($stmt->execute()) {
            // Sucesso
            header("Location: Troca_Turma.php?sucesso=A troca de turma foi realizada com sucesso!");
            exit();
        } else {
            // Erro
            $erro = "Ocorreu um erro ao tentar realizar a troca. Tente novamente.";
        }
        $stmt->close();
    }
}


// --- LÓGICA PARA POPULAR OS DROPDOWNS ---
// Busca todos os alunos com suas turmas atuais
$alunos_sql = "SELECT a.ID_Aluno, a.Nome, t.Nome_Turma 
               FROM Alunos a 
               LEFT JOIN Turmas t ON a.Turmas_ID_Turma = t.ID_Turma 
               ORDER BY a.Nome";
$alunos_result = $conexao->query($alunos_sql);

// Busca todas as turmas disponíveis
$turmas_result = $conexao->query("SELECT ID_Turma, Nome_Turma FROM Turmas ORDER BY Nome_Turma");

?>

<div class="form-container">
    <?php if(isset($_GET['sucesso'])): ?>
        <div class="alert success"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
    <?php endif; ?>
    <?php if(isset($erro)): ?>
        <div class="alert error"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3 class="section-title">Dados para Troca de Turma</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="Troca_Turma.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="aluno_id">Selecione o Aluno*</label>
                        <select id="aluno_id" name="aluno_id" required onchange="atualizarTurmaAtual(this)">
                            <option value="">-- Selecione um aluno --</option>
                            <?php 
                            if ($alunos_result->num_rows > 0) {
                                while($aluno = $alunos_result->fetch_assoc()): ?>
                                    <option value="<?php echo $aluno['ID_Aluno']; ?>" data-turma-atual="<?php echo htmlspecialchars($aluno['Nome_Turma'] ?? 'Sem turma'); ?>">
                                        <?php echo htmlspecialchars($aluno['Nome']); ?>
                                    </option>
                                <?php endwhile;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="current-class">Turma Atual</label>
                        <input type="text" id="current-class" readonly style="background-color: #e9ecef;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nova_turma_id">Selecione a Nova Turma*</label>
                        <select id="nova_turma_id" name="nova_turma_id" required>
                            <option value="">-- Selecione a nova turma --</option>
                            <?php 
                             if ($turmas_result->num_rows > 0) {
                                // Reinicia o ponteiro do resultado para reutilizar a consulta
                                $turmas_result->data_seek(0);
                                while($turma = $turmas_result->fetch_assoc()): ?>
                                    <option value="<?php echo $turma['ID_Turma']; ?>">
                                        <?php echo htmlspecialchars($turma['Nome_Turma']); ?>
                                    </option>
                                <?php endwhile;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-exchange-alt"></i> Confirmar Troca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Pequeno script para exibir a turma atual do aluno selecionado
    function atualizarTurmaAtual(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const turmaAtual = selectedOption.getAttribute('data-turma-atual');
        document.getElementById('current-class').value = turmaAtual;
    }
</script>


<?php 
$conexao->close();
require_once '../templates/footer_secretario.php'; 
?>