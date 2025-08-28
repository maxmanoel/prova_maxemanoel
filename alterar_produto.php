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

// SE O FORMULÁRIO FOR ENVIADO, BUSCA O PRODUTO PELO ID OU PELO NOME
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_produto'])) {
        $busca = trim($_POST["busca_produto"]);

        if(is_numeric($busca)) {
            $sql = "SELECT * FROM produto WHERE id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O PRODUTO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if(!$produto){
            echo "<script>alert('Produto não encontrado!');</script>";
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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: left;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            outline: none;
        }

        input:focus {
            border-color: #2980b9;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            margin-right: 10px;
            transition: background-color 0.2s ease;
            color: white;
        }

        button[type="submit"] {
            background-color: #2980b9;
        }

        button[type="submit"]:hover {
            background-color: #1c5980;
        }

        button[type="reset"] {
            background-color: #c0392b;
        }

        button[type="reset"]:hover {
            background-color: #922b21;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        a.back-link:hover {
            background-color: #1c5980;
        }

        address {
            margin-top: 30px;
            font-size: 0.9em;
            color: #7f8c8d;
            font-style: normal;
        }
    </style>
    <script src="masks.js"></script>
</head>
<body>

<h2>Alterar Produto</h2>

<form action="alterar_produto.php" method="POST">
    <label for="busca_produto">Digite o ID ou NOME do produto:</label>
    <input type="text" id="busca_produto" name="busca_produto" required>
    <button type="submit">Buscar</button>
</form>

<?php if ($produto): ?>
    <form action="processa_alteracao_produto.php" method="POST">
        <input type="hidden" name="id_produto" value="<?=htmlspecialchars($produto['id_produto'])?>">

        <label for="nome_prod">Nome:</label>
        <input type="text" id="nome_prod" name="nome_prod" value="<?=htmlspecialchars($produto['nome_prod'])?>" required>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" value="<?=htmlspecialchars($produto['descricao'])?>" required>

        <label for="qtde">Quantidade:</label>
        <input type="text" id="qtde" name="qtde" value="<?=htmlspecialchars($produto['qtde'])?>" required>

        <label for="valor_unit">Valor Unitário:</label>
        <input type="text" step="0.01" id="valor_unit" name="valor_unit" value="<?=htmlspecialchars($produto['valor_unit'])?>" required>

        
        <button type="submit">Alterar</button>
        <button type="reset">Cancelar</button>
    </form>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

</body>
</html>
