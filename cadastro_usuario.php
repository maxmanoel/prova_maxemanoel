<?php 
 session_start();
require_once 'conexao.php';

//Garante que o usuario esteja logado
if($_SESSION['perfil']!=1){
    echo "Acesso negado";
    exit();

}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_SESSION['nome'];
    $email = $_SESSION['email'];
    $senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];
    $sql = "INSERT into usuario(nome, email, senha, id_perfil) values(:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);
    

    if ($stmt->execute());
}

?>
 

