<?php
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA AS VARIAVEIS
$produto = null;

// Busca todos os produtos cadastrados em ordem alfabetica
$sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// SE um id for passado via get, exclui o produto
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_produto = $_GET['id'];

    // Exclui o produto do banco de dados
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Produto excluído com sucesso!');window.location.href='excluir_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir produto!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            text-align: center;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            margin: 20px auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 90%;
            max-width: 1000px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f7f9fb;
        }

        tr:hover {
            background-color: #d6eaf8;
            transition: 0.2s;
        }

        a {
            display: inline-block;
            margin: 5px 0;
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            background-color: #c0392b;
            transition: background-color 0.2s ease;
        }

        a:hover {
            background-color: #922b21;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background-color: #1c5980;
        }

        address {
            margin-top: 30px;
            font-size: 0.9em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<h2>Excluir Produto</h2>

<?php if (!empty($produtos)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Código</th>
            <th>Ações</th>
        </tr>

        <?php foreach($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                <td><?= htmlspecialchars($produto['qtde']) ?></td>
                <td>R$ <?= number_format($produto['valor_unit'], 2, ',', '.') ?></td>
                
                <td>
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                       Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum produto encontrado</p>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

</body>
</html>
