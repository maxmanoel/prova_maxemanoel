<?php
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável para evitar erros
$produtos = [];

// Se o formulário for enviado, busca o produto por ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["busca"])) {
    $busca = trim($_POST["busca"]);

    if (is_numeric($busca)) {
        $sql = "SELECT * FROM produto WHERE id_produto = :busca ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    // Se não houver busca, lista todos os produtos
    $sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
    $stmt = $pdo->prepare($sql);
}

// Executa a consulta
try {
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Erro ao buscar produtos: " . addslashes($e->getMessage()) . "');</script>";
}
?>

