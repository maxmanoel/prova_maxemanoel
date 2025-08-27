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

// Atualiza o produto se o formulário for enviado
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
        $sql_update = "UPDATE produto 
                       SET nome_prod = :nome, 
                           descricao = :descricao, 
                           qtde = :qtde, 
                           valor_unit = :valor 
                       WHERE id_produto = :id";

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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alterar Produto</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
    h2 { text-align: center; }
    form { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
    label { display: block; margin-top: 15px; }
    input, textarea, button { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
    button { cursor: pointer; margin-top: 15px; }
    a { display: block; text-align: center; margin-top: 20px; }
</style>
<!-- Chama o JS externo das máscaras -->
<script src="js/masks.js"></script>
</head>
<body>
<h2>Alterar Produto</h2>

<form action="alterar_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" method="POST">
    <label for="nome">Nome do Produto</label>
    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod'] ?? '') ?>" required>

    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" required><?= htmlspecialchars($produto['descricao_prod'] ?? '') ?></textarea>

    <label for="qtde">Quantidade</label>
    <input type="text" id="qtde" name="qtde" value="<?= htmlspecialchars($produto['qtde'] ?? '') ?>" required>

    <label for="valor_unit">Valor Unitário</label>
    <input type="text" id="valor_unit" name="valor_unit" value="<?= htmlspecialchars($produto['valor_unit'] ?? '') ?>" required>

    <button type="submit">Salvar Alterações</button>
    <button type="reset">Cancelar</button>
</form>

<a href="buscar_produto.php">Voltar</a>
</body>
</html>
