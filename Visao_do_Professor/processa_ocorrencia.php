<?php
session_start();
require_once '../conexao.php';

// Verifica se o método é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Receber e validar os dados (exemplo)
    $turma_id = $_POST['turma_id'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? null;
    $data_ocorrencia = $_POST['data_ocorrencia'] ?? null;
    $tipo = $_POST['tipo'] ?? null;
    $descricao = $_POST['descricao'] ?? '';
    $acoes = $_POST['acoes'] ?? '';
    $registrado_por_id = 1; // Simulação do ID do professor logado

    // Validação simples
    if (empty($aluno_id) || empty($data_ocorrencia) || empty($tipo) || empty($descricao)) {
        $_SESSION['mensagem_erro'] = "Erro: Todos os campos obrigatórios devem ser preenchidos!";
        header("Location: ocorrencias_professor.php");
        exit();
    }

    // 2. Preparar e executar a inserção no banco de dados
    // $sql = "INSERT INTO ocorrencias (aluno_id, data_ocorrencia, tipo, descricao, acoes_tomadas, registrado_por_id) VALUES (?, ?, ?, ?, ?, ?)";
    // $stmt = $conexao->prepare($sql);
    // $stmt->bind_param("issssi", $aluno_id, $data_ocorrencia, $tipo, $descricao, $acoes, $registrado_por_id);

    // 3. Simulação de sucesso
    // if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Ocorrência registrada com sucesso!";
    // } else {
    //     $_SESSION['mensagem_erro'] = "Erro ao registrar a ocorrência: " . $conexao->error;
    // }

    // Redireciona de volta para a página de ocorrências
    header("Location: ocorrencias_professor.php");
    exit();

} else {
    // Se não for POST, redireciona para a página inicial
    $_SESSION['mensagem_erro'] = "Acesso inválido!";
    header("Location: tela_principal_professor.php");
    exit();
}
?>