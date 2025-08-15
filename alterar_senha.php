<?php 
 session_start();
require_once 'conexao.php';

//Garante que o usuario esteja logado
if(!isset($_SESSION['id_usuario'])){
    echo "<script>alert('Acesso negado'); window.location.href='login.php';</script>";
    exit();

}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_SESSION['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha !== $confirmar_senha){
        echo "<script>alert('As senha não coincidem');</script>";

    }elseif(strlen($nova_senha) > 8){
        "<script>alert('A senha deve ter pelo menos 8 caracteres');</script>";

    }elseif(strlen($nova_senha) == "temp123"){
        "<script>alert('Escolha uma senha diferente de temporaria');</script>";
}else{
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// ATUALIZA A SENHA E REMOVE O STATUS DE TEMPORARIA

    $sql = "UPDATE usuario SET senha = : senha,senha_temporaria = FALSE WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':id', $id_usuario);

    if($stmt->execute()){
        session_destroy(); // finalizar a sesao
        echo  "<script>alert('Senha alterada com sucesso! Faça login novamente');</script>";
    }



}
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>alterar senha</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>

<h2>Alterar senha:</h2>

<p> Olá, <strong><?php echo $_SESSION['usuario'];?></strong>. Digite sua nova senha abaixo:</p>


    <form action="alterar_senha.php" method="POST">
        <label for="nova_senha"> Nova Senha</label>


        <input type="password" id="nova_senha" name="nova_senha" required>

        </br>

        <label for="confirmar_senha"> confirmar senha</label>


        <input type="password" id="confirmar_senha" name="confirmar_senha" required>

        <label> 
            <input type="checkbox" onclick="mostrarSenha()"> Mostra senha </label>

            <button type="submit"> Salvar Nova Senha </button>

</form>

<script>
    function mostrarSenha();{
        var senha1 = document.getelemetById("nova_senha");

        var senha2 = document.getelemetById("confirmar_senha");
        
        var tipo = senha1.type === "password" ? "text": "password";
        senha1.type=tipo;
        senha2.type=tipo;
        
    }

    </script> 
</body>
</html>