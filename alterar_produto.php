<?php
session_start();
require_once 'conexao.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso Negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Verifica se o ID do produto foi passado via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Produto não informado!'); window.location.href='buscar_produto.php';</script>";
    exit();
}

$id_produto = (int)$_GET['id'];

// Busca o produto no banco
$sql = "SELECT * FROM produto WHERE id_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.location.href='buscar_produto.php';</script>";
    exit();
}

// Atualiza o produto se o formulário for enviadoA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $qtde = (int)$_POST['qtde'];
    $valor_unit = str_replace(',', '.', str_replace('.', '', $_POST['valor_unit'])); // remove pontos e converte vírgula em ponto

    // Validações básicas
    if (strlen($nome) < 2) {
        echo "<script>alert('O nome do produto deve ter pelo menos 2 caracteres.');</script>";
    } elseif ($qtde < 0) {
        echo "<script>alert('A quantidade não pode ser negativa.');</script>";
    } elseif ($valor_unit <= 0) {
        echo "<script>alert('O valor unitário deve ser maior que zero.');</script>";
    } else {
        $sql_update = "UPDATE produto SET nome_prod = :nome, descricao = :descricao, qtde = :qtde, valor_unit = :valor WHERE id_produto = :id";

        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':descricao', $descricao);
        $stmt_update->bindParam(':qtde', $qtde, PDO::PARAM_INT);
        $stmt_update->bindParam(':valor', $valor_unit);
        $stmt_update->bindParam(':id', $id_produto, PDO::PARAM_INT);

        try {
            if ($stmt_update->execute()) {
                echo "<script>alert('Produto alterado com sucesso!'); window.location.href='buscar_produto.php';</script>";
                exit();
            } else {
                echo "<script>alert('Erro ao alterar o produto.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>

