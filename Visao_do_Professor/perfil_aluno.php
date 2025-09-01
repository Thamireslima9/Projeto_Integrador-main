<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Perfil do Aluno';
$page_icon = 'fas fa-user';
$breadcrumb = 'Portal do Professor > Perfil do Aluno';


// 1. Obter o ID do aluno da URL
$aluno_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($aluno_id === 0) {
    echo "<p>ID do aluno não fornecido. Por favor, selecione um aluno da lista.</p>";
    require_once '../templates/footer_professor.php';
    exit();
}

// 2. Simulação de busca de dados de MÚLTIPLAS tabelas
// $aluno = $conexao->query("SELECT a.*, t.nome as nome_turma FROM alunos a JOIN turmas t ON a.turma_id = t.id WHERE a.id = $aluno_id")->fetch_assoc();
// $ocorrencias = $conexao->query("SELECT * FROM ocorrencias WHERE aluno_id = $aluno_id ORDER BY data_ocorrencia DESC")->fetch_all(MYSQLI_ASSOC);
// $registros_diarios = $conexao->query("SELECT * FROM registros_diarios WHERE aluno_id = $aluno_id ORDER BY data DESC, horario DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$aluno_simulado = [
    'nome_completo' => 'João Pedro Silva', 'data_nascimento' => '2024-05-10', 'nome_turma' => 'Berçário II', 'alergias' => 'Nenhuma', 'medicamentos_uso_continuo' => 'Nenhum'
];
$ocorrencias_simuladas = [
    ['data_ocorrencia' => '2025-08-17 10:30:00', 'tipo' => 'Saúde', 'descricao' => 'Apresentou febre de 38°C.'],
    ['data_ocorrencia' => '2025-08-15 15:00:00', 'tipo' => 'Acidente', 'descricao' => 'Pequeno arranhão no joelho ao cair no pátio.']
];
$registros_diarios_simulados = [
    ['data' => '2025-08-17', 'horario' => '12:00', 'tipo' => 'Alimentação', 'detalhes' => 'Comeu toda a papinha de legumes.'],
    ['data' => '2025-08-17', 'horario' => '10:00', 'tipo' => 'Higiene', 'detalhes' => 'Troca de fralda (xixi).'],
    ['data' => '2025-08-16', 'horario' => '13:00', 'tipo' => 'Sono', 'detalhes' => 'Dormiu por 1h30.']
];
?>

<div class="card">
    <div class="card-header">
        <h3>Dossiê de <?php echo htmlspecialchars($aluno_simulado['nome_completo']); ?></h3>
    </div>
    <div class="card-body">
        <h4 class="section-title">Dados Pessoais</h4>
        <div class="form-row">
            <div class="form-group"><label>Turma</label><input type="text" value="<?php echo htmlspecialchars($aluno_simulado['nome_turma']); ?>" readonly></div>
            <div class="form-group"><label>Nascimento</label><input type="text" value="<?php echo date('d/m/Y', strtotime($aluno_simulado['data_nascimento'])); ?>" readonly></div>
            <div class="form-group"><label>Alergias</label><input type="text" value="<?php echo htmlspecialchars($aluno_simulado['alergias']); ?>" readonly></div>
            <div class="form-group"><label>Medicamentos</label><input type="text" value="<?php echo htmlspecialchars($aluno_simulado['medicamentos_uso_continuo']); ?>" readonly></div>
        </div>

        <h4 class="section-title" style="margin-top: 20px;">Últimos Registros Diários</h4>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Data/Hora</th><th>Tipo</th><th>Detalhes</th></tr></thead>
                <tbody>
                    <?php foreach($registros_diarios_simulados as $reg): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($reg['data'])) . ' ' . $reg['horario']; ?></td>
                        <td><?php echo htmlspecialchars($reg['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($reg['detalhes']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="section-title" style="margin-top: 20px;">Histórico de Ocorrências</h4>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Data/Hora</th><th>Tipo</th><th>Descrição</th></tr></thead>
                <tbody>
                    <?php foreach($ocorrencias_simuladas as $oc): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($oc['data_ocorrencia'])); ?></td>
                        <td><?php echo htmlspecialchars($oc['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($oc['descricao']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <a href="criancas_por_turma.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Lista</a>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer_professor.php';
?>