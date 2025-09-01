<?php
// Define o título e o ícone da página
$page_title = 'Cadastro de Responsável';
$page_icon = 'fas fa-user-tie';

// Define a constante PROJECT_ROOT e inclui o cabeçalho
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/visao_secretario/templates/header_secretario.php';

// Inicializa arrays com valores padrão
$responsavel = ['id' => null, 'nome' => '', 'cpf' => '', 'rg' => '', 'datanasc' => '', 'email' => '', 'telefone' => ''];
$endereco = ['idendereco' => null, 'logradouro' => '', 'cep' => '', 'numero' => '', 'complemento' => '', 'bairro' => '', 'cidade' => '', 'estado' => ''];
$is_edit_mode = false;

// --- MODO EDIÇÃO ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $is_edit_mode = true;
    $id_responsavel = $_GET['id'];
    $page_title = 'Editar Responsável';

    // Busca dados das duas tabelas usando JOIN
    $stmt = $conexao->prepare("SELECT u.*, e.* FROM usuario u LEFT JOIN endereco e ON u.id = e.usuario_id WHERE u.id = ? AND u.tipo_idtipo = 5");
    $stmt->bind_param("i", $id_responsavel);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Separa os dados nos arrays correspondentes
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $responsavel)) $responsavel[$key] = $value;
            if (array_key_exists($key, $endereco)) $endereco[$key] = $value;
        }
    }
    $stmt->close();
}

// --- PROCESSAMENTO DO FORMULÁRIO (SALVAR) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicia uma transação para garantir que ambas as tabelas sejam atualizadas com sucesso
    $conexao->begin_transaction();

    try {
        // Dados do Responsável (tabela: usuario)
        $id_responsavel = $_POST['id_responsavel'] ?: null;
        $nome = $_POST['nome'];
        $cpf = $_POST['responsavel-cpf'];
        $rg = $_POST['responsavel-rg'];
        $email = $_POST['responsavel-email'];
        $telefone = $_POST['responsavel-phone'];
        $tipo_id = 5; // ID para 'responsavel'

        if ($id_responsavel) { // UPDATE
            $stmt_user = $conexao->prepare("UPDATE usuario SET nome=?, cpf=?, rg=?, email=?, telefone=? WHERE id=?");
            $stmt_user->bind_param("sssssi", $nome, $cpf, $rg, $email, $telefone, $id_responsavel);
        } else { // INSERT
            $result_max_id = $conexao->query("SELECT MAX(id) as max_id FROM usuario");
            $max_id_row = $result_max_id->fetch_assoc();
            $id_responsavel = ($max_id_row['max_id'] ?? 0) + 1;

            $stmt_user = $conexao->prepare("INSERT INTO usuario (id, nome, cpf, rg, email, telefone, tipo_idtipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_user->bind_param("isssssi", $id_responsavel, $nome, $cpf, $rg, $email, $telefone, $tipo_id);
        }
        $stmt_user->execute();
        $stmt_user->close();

        // Dados do Endereço (tabela: endereco)
        $id_endereco = $_POST['id_endereco'] ?: null;
        $cep = $_POST['responsavel-cep'];
        $logradouro = $_POST['responsavel-address'];
        $numero = $_POST['responsavel-number'];
        $complemento = $_POST['responsavel-complement'];
        $bairro = $_POST['responsavel-district'];
        $cidade = $_POST['responsavel-city'];
        $estado = $_POST['responsavel-state'];

        if ($id_endereco) { // UPDATE
            $stmt_addr = $conexao->prepare("UPDATE endereco SET logradouro=?, cep=?, numero=?, complemento=?, bairro=?, cidade=?, estado=? WHERE idendereco=?");
            $stmt_addr->bind_param("sssssssi", $logradouro, $cep, $numero, $complemento, $bairro, $cidade, $estado, $id_endereco);
        } else { // INSERT
             $result_max_id_end = $conexao->query("SELECT MAX(idendereco) as max_id FROM endereco");
            $max_id_row_end = $result_max_id_end->fetch_assoc();
            $novo_id_end = ($max_id_row_end['max_id'] ?? 0) + 1;

            $stmt_addr = $conexao->prepare("INSERT INTO endereco (idendereco, logradouro, cep, numero, complemento, bairro, cidade, estado, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_addr->bind_param("isssssssi", $novo_id_end, $logradouro, $cep, $numero, $complemento, $bairro, $cidade, $estado, $id_responsavel);
        }
        $stmt_addr->execute();
        $stmt_addr->close();
        
        // Se tudo deu certo, confirma a transação
        $conexao->commit();
        header("Location: Listagem_Responsavel.php?sucesso=Responsável salvo com sucesso!");
        exit();

    } catch (Exception $e) {
        // Se algo deu errado, desfaz a transação
        $conexao->rollback();
        header("Location: Cadastro_Responsavel.php?erro=Erro ao salvar. Verifique os dados e tente novamente.");
        exit();
    }
}
?>

<div class="form-container">
    <form id="form-responsavel" method="POST" action="Cadastro_Responsavel.php<?php echo $is_edit_mode ? '?id=' . htmlspecialchars($responsavel['id']) : ''; ?>">
        <input type="hidden" name="id_responsavel" value="<?php echo htmlspecialchars($responsavel['id'] ?? ''); ?>">
        <input type="hidden" name="id_endereco" value="<?php echo htmlspecialchars($endereco['idendereco'] ?? ''); ?>">

        <h3 class="section-title">Dados do Responsável</h3>
        <div class="form-row">
            <div class="form-group"><label for="nome">Nome*</label><input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($responsavel['nome'] ?? ''); ?>" required></div>
            <div class="form-group"><label for="responsavel-rg">RG</label><input type="text" id="responsavel-rg" name="responsavel-rg" value="<?php echo htmlspecialchars($responsavel['rg'] ?? ''); ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="responsavel-cpf">CPF*</label><input type="text" id="responsavel-cpf" name="responsavel-cpf" value="<?php echo htmlspecialchars($responsavel['cpf'] ?? ''); ?>" required></div>
            <div class="form-group"><label for="responsavel-phone">Telefone*</label><input type="tel" id="responsavel-phone" name="responsavel-phone" value="<?php echo htmlspecialchars($responsavel['telefone'] ?? ''); ?>" required></div>
        </div>
        <div class="form-group">
            <label for="responsavel-email">E-mail*</label>
            <input type="email" id="responsavel-email" name="responsavel-email" value="<?php echo htmlspecialchars($responsavel['email'] ?? ''); ?>" required>
        </div>

        <h3 class="section-title">Endereço</h3>
        <div class="form-row">
            <div class="form-group"><label for="responsavel-cep">CEP</label><input type="text" id="responsavel-cep" name="responsavel-cep" value="<?php echo htmlspecialchars($endereco['cep'] ?? ''); ?>"></div>
            <div class="form-group"><label for="responsavel-address">Logradouro</label><input type="text" id="responsavel-address" name="responsavel-address" value="<?php echo htmlspecialchars($endereco['logradouro'] ?? ''); ?>"></div>
            <div class="form-group"><label for="responsavel-number">Número</label><input type="text" id="responsavel-number" name="responsavel-number" value="<?php echo htmlspecialchars($endereco['numero'] ?? ''); ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="responsavel-complement">Complemento</label><input type="text" id="responsavel-complement" name="responsavel-complement" value="<?php echo htmlspecialchars($endereco['complemento'] ?? ''); ?>"></div>
            <div class="form-group"><label for="responsavel-district">Bairro</label><input type="text" id="responsavel-district" name="responsavel-district" value="<?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="responsavel-city">Cidade</label><input type="text" id="responsavel-city" name="responsavel-city" value="<?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>"></div>
            <div class="form-group">
                <label for="responsavel-state">Estado</label>
                <select id="responsavel-state" name="responsavel-state">
                    <option value="">Selecione</option>
                    <option value="SP" <?php echo (($endereco['estado'] ?? '') == 'SP') ? 'selected' : ''; ?>>São Paulo</option>
                    <option value="RJ" <?php echo (($endereco['estado'] ?? '') == 'RJ') ? 'selected' : ''; ?>>Rio de Janeiro</option>
                    <option value="MG" <?php echo (($endereco['estado'] ?? '') == 'MG') ? 'selected' : ''; ?>>Minas Gerais</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="Listagem_Responsavel.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Cadastro</button>
        </div>
    </form>
</div>

<?php require_once PROJECT_ROOT . '/visao_secretario/templates/footer_secretario.php'; ?>