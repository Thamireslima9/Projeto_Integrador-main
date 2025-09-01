<?php
define('ROOT_PATH', dirname(__DIR__)); // Ou o caminho correto para a raiz do seu projeto
require_once(ROOT_PATH . '/Visao_do_Professor/templates/header_professor.php');
$page_title = 'Registrar Observação';
$page_icon = 'fas fa-edit';
$breadcrumb = 'Portal do Professor > Acompanhamento > Registrar Observação';

?>

<div class="form-container">
    <form method="POST" action="processa_observacao.php">
        <div class="form-row">
            <div class="form-group">
                <label for="data">Data</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label for="crianca">Criança</label>
                <select id="crianca" name="aluno_id" required>
                    <option value="">Selecione...</option>
                    </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="area">Área de Desenvolvimento</label>
                <select id="area" name="area" required>
                    <option value="">Selecione...</option>
                    <option value="Motricidade">Motricidade</option>
                    <option value="Cognitivo">Cognitivo</option>
                    <option value="Socioafetivo">Socioafetivo</option>
                    <option value="Linguagem">Linguagem</option>
                </select>
            </div>
            <div class="form-group">
                <label for="habilidade">Habilidade Observada</label>
                <input type="text" id="habilidade" name="habilidade" placeholder="Ex: Equilíbrio, vocabulário..." required>
            </div>
        </div>
        <div class="form-group">
            <label for="observacao">Descrição da Observação</label>
            <textarea id="observacao" name="descricao" rows="4" placeholder="Descreva o que foi observado..." required></textarea>
        </div>
        <div class="form-actions">
            <a href="desenvolvimento_aluno.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Observação</button>
        </div>
    </form>
</div>

<?php
require_once 'templates/footer_professor.php';
?>