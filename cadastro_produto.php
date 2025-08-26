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