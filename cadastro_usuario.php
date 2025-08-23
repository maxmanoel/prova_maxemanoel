<?php 
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

// Garante que o usuário esteja logado
if($_SESSION['perfil']!=1){
    echo "Acesso negado";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) 
            VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);
    
    if ($stmt->execute()){
        echo "<script>alert('Usuário cadastrado com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar o usuário');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Usuário</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f4f8;
        color: #333;
        text-align: center;
        padding: 20px;
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
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        outline: none;
    }

    input:focus, select:focus {
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
<script src="js/masks.js" defer></script>
</head>
<body>

<h2>Cadastro de Usuário</h2>

<form action="cadastro_usuario.php" method="POST">
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" required data-mask="nome">

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" required>

    <label for="id_perfil">Perfil</label>
    <select id="id_perfil" name="id_perfil" required>
        <option value="1">Administrador</option>
        <option value="2">Secretaria</option>
        <option value="3">Almoxarife</option>
        <option value="4">Cliente</option>
    </select>

    <button type="submit">Salvar</button>
    <button type="reset">Cancelar</button>
</form>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoe / estudante / desenvolvimento 
</address>

</body>
</html>
