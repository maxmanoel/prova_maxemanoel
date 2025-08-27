<?php 
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = trim($_POST['nome_produto']);
    $descricao = trim($_POST['descricao']);
    $qtde = (int)$_POST['quantidade'];
    $valor_unit = str_replace(',', '.', str_replace('.', '', $_POST['valor_unitario'])); // remove pontos e converte vírgula em ponto

    // Validações
    if (strlen($nome_produto) < 2) {
        echo "<script>alert('O nome do produto deve ter pelo menos 2 caracteres.');</script>";
    } elseif ($qtde < 0) {
        echo "<script>alert('A quantidade não pode ser negativa.');</script>";
    } elseif ($valor_unit <= 0) {
        echo "<script>alert('O valor unitário deve ser maior que zero.');</script>";
    } else {
        try {
            $sql = "INSERT INTO produto (nome_prod, descricao, qtde, valor_unit) 
                    VALUES (:nome_prod, :descricao, :qtde, :valor_unit)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_prod', $nome_produto);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':qtde', $qtde, PDO::PARAM_INT);
            $stmt->bindParam(':valor_unit', $valor_unit);

            if ($stmt->execute()) {
                echo "<script>alert('Produto cadastrado com sucesso'); window.location.href='buscar_produto.php';</script>";
            } else {
                $erro = $stmt->errorInfo();
                echo "<pre>Erro ao cadastrar produto: ";
                print_r($erro);
                echo "</pre>";
            }
        } catch (PDOException $e) {
            echo "<pre>Erro PDO: " . $e->getMessage() . "</pre>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Produto</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f4f8;
        color: #333;
        padding: 20px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #2c3e50;
    }

    form {
        max-width: 500px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: left;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: 500;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        outline: none;
    }

    input[type="text"]:focus,
    input[type="number"]:focus {
        border-color: #2980b9;
    }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        margin-right: 10px;
        color: white;
        transition: background-color 0.2s ease;
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

<h2>Cadastro de Produto</h2>

<form action="cadastro_produto.php" method="POST">
    <label for="nome_produto">Nome do Produto</label>
    <input type="text" id="nome_produto" name="nome_produto" required >

    <label for="descricao">Descrição</label>
    <input type="text" id="descricao" name="descricao" required>

    <label for="quantidade">Quantidade</label>
    <input type="text" id="quantidade" name="quantidade" required>

    <label for="valor_unitario">Valor Unitário</label>
    <input type="text" id="valor_unitario" name="valor_unitario" required>

    <button type="submit">Salvar</button>
    <button type="reset">Cancelar</button>
</form>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoe / estudante / desenvolvimento 
</address>

</body>
</html>
