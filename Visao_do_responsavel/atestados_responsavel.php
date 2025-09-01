<?php
$page_title = 'Atestados';
$page_icon = 'fas fa-notes-medical';
// Define a constante PROJECT_ROOT e inclui o cabeçalho do template do responsável
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(dirname(__DIR__)));
}
require_once 'templates/header_responsavel.php';

// Pega o ID do responsável e do aluno da sessão (ajuste conforme sua lógica de login)
$id_responsavel_logado = $_SESSION['id_usuario'];
$id_aluno_associado = null;

// Busca o ID do aluno associado a este responsável
$stmt_aluno = $conexao->prepare("SELECT id_aluno FROM alunos_responsaveis WHERE id_responsavel = ? LIMIT 1");
$stmt_aluno->bind_param("i", $id_responsavel_logado);
$stmt_aluno->execute();
$result_aluno = $stmt_aluno->get_result();
if ($result_aluno->num_rows > 0) {
    $id_aluno_associado = $result_aluno->fetch_assoc()['id_aluno'];
}
$stmt_aluno->close();


// --- PROCESSAMENTO DO FORMULÁRIO DE NOVO ATESTADO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['data_inicio'])) {
    if ($id_aluno_associado) {
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $motivo = $_POST['motivo'];
        // Lógica de upload de arquivo viria aqui
        $caminho_anexo = "uploads/atestado_placeholder.pdf"; // Placeholder

        $stmt = $conexao->prepare("INSERT INTO atestados (id_aluno, data_inicio, data_fim, motivo, caminho_anexo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_aluno_associado, $data_inicio, $data_fim, $motivo, $caminho_anexo);
        
        if ($stmt->execute()) {
            // Redireciona com mensagem de sucesso
            header("Location: atestados_responsavel.php?sucesso=1");
            exit();
        } else {
            $erro = "Não foi possível enviar o atestado.";
        }
        $stmt->close();
    } else {
        $erro = "Nenhum aluno associado para registrar o atestado.";
    }
}


// --- BUSCA DE DADOS PARA A TABELA DE HISTÓRICO ---
$atestados = [];
if ($id_aluno_associado) {
    $stmt_hist = $conexao->prepare("SELECT * FROM atestados WHERE id_aluno = ? ORDER BY data_inicio DESC");
    $stmt_hist->bind_param("i", $id_aluno_associado);
    $stmt_hist->execute();
    $result_hist = $stmt_hist->get_result();
    while ($row = $result_hist->fetch_assoc()) {
        $atestados[] = $row;
    }
    $stmt_hist->close();
}
?>

<div class="card">
    <div class="card-header"><i class="fas fa-calendar-check"></i><h3 class="section-title">Gerenciar Atestados</h3></div>
    <div class="card-body">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="openTab(event, 'novo-atestado')"><i class="fas fa-plus-circle"></i> Enviar Novo Atestado</button>
            <button class="tab-btn" onclick="openTab(event, 'historico')"><i class="fas fa-history"></i> Histórico de Atestados</button>
        </div>

        <div id="novo-atestado" class="tab-content active">
            <form id="form-atestado" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="inicio-afastamento">Início do Afastamento*</label>
                        <input type="date" id="inicio-afastamento" name="data_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fim-afastamento">Fim do Afastamento*</label>
                        <input type="date" id="fim-afastamento" name="data_fim" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo/Observações (Opcional)</label>
                    <textarea id="motivo" name="motivo" rows="3" placeholder="Ex: A criança apresentou febre e dor de garganta."></textarea>
                </div>

                <div class="file-upload-container">
                    <label for="arquivo-atestado" class="file-upload">
                        <input type="file" id="arquivo-atestado" name="arquivo_atestado" accept=".pdf,.jpg,.jpeg,.png" required>
                        <span class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Clique para enviar o atestado</span>
                            <p>Formatos: PDF, JPG, PNG (máx. 5MB)</p>
                        </span>
                    </label>
                    <div id="file-info" class="file-info"><i class="fas fa-file-alt"></i> Nenhum arquivo selecionado</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar Atestado</button>
                </div>
            </form>
        </div>

        <div id="historico" class="tab-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Início Afastamento</th>
                            <th>Fim Afastamento</th>
                            <th>Motivo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($atestados)): ?>
                            <tr><td colspan="4" style="text-align: center;">Nenhum atestado enviado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($atestados as $atestado): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($atestado['data_inicio'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($atestado['data_fim'])); ?></td>
                                <td><?php echo htmlspecialchars($atestado['motivo']); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($atestado['caminho_anexo']); ?>" class="btn btn-view" download><i class="fas fa-download"></i> Baixar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.classList.add("active");
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('.tab-btn.active').click();

        document.getElementById('arquivo-atestado').addEventListener('change', function() {
            const fileInfo = document.getElementById("file-info");
            if (this.files.length > 0) {
                fileInfo.innerHTML = `<i class="fas fa-file-check"></i> ${this.files[0].name}`;
            } else {
                fileInfo.innerHTML = '<i class="fas fa-file-alt"></i> Nenhum arquivo selecionado';
            }
        });
    });
</script>

<?php
require_once 'templates/footer_responsavel.php';
?>