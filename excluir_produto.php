<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso Negado!'); window.location.href='principal.php';</script>";
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Produto não informado!'); window.location.href='buscar_produto.php';</script>";
    exit();
}

$id_produto = (int)$_GET['id'];

$sql_check = "SELECT * FROM produto WHERE id_produto = :id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':id', $id_produto, PDO::PARAM_INT);
$stmt_check->execute();
$produto = $stmt_check->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.location.href='buscar_produto.php';</script>";
    exit();
}

$sql_delete = "DELETE FROM produto WHERE id_produto = :id";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id_produto, PDO::PARAM_INT);

try {
    if ($stmt_delete->execute()) {
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='buscar_produto.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao excluir produto.'); window.location.href='buscar_produto.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    echo "<script>alert('Erro ao excluir produto: " . addslashes($e->getMessage()) . "'); window.location.href='buscar_produto.php';</script>";
    exit();
}
?>

<style>
    address {
        display: block;
        text-align: center;
        margin-top: 30px;
        font-size: 0.9em;
        color: #7f8c8d;
        font-style: normal;
    }
</style>

<address>
    | Max Emanoe / estudante / desenvolvimento 
</address>
