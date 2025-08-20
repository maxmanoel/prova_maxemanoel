<?php 
 session_start();
require_once 'conexao.php';

//Garante que o usuario esteja logado
if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2){
    echo "<script>alert('Acesso negado');window.location.href='principal.php';</script>";
    exit();



}

// inicialização a variavel para evitar erros 
$usuarios = [];

// se o formulario for enviado, busca o usuario pelo id ou nome

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);

    // VERIFICA SE A  BUSCA É UM NUMERO (ID) OU UM NOME

    if(is_numeric($busca)){
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca,PDO::PARAM_INT);

    }else{
        $sql="SELECT * FROM usuario WHERE nome LIKE:busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%busca%",PDO::PARAM_STR);
    }
}else{
    $sql="SELECT * FROM usuario ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchALL(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuario</title>

    <link rel="stylesheet" href="styles.css">

</head>
<body>


<h2> Lista de Usuarios</h2>
<!-- formulario  buscar usuarios-->
    <form action="buscar_usuario.php" method="POST">

    <label for ="busca">>Digite o ID ou nome (opcional):</label>

    <input type="text" id= "busca"  name="busca" required>
    
<button type="submit">Pesquisar</button>

    
</form>

<?php if (!empty($usuarios)):?>
    <table border ="1">
        <tr>
            <th>ID </th>
            <th>Nome </th>
            <th>Email </th>
            <th>Perfil </th>
            <th>Ações </th>
</tr>

<?php foreach($usuarios as $usuarios):?>
    <tr>
        <td><?=htmlspecialchars($usuarios['id_usuario'])?></td>

        <td><?=htmlspecialchars($usuarios['nome'])?></td>

        <td><?=htmlspecialchars($usuarios['email'])?></td>

        <td><?=htmlspecialchars($usuarios['id_perfil'])?></td>
        <td>
            <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuarios['id_usuario'])?>">Alterar</a>

            <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuarios['id_usuario'])?>" onclick="return confirm('Tem certeza que deseka excluir esse usuario')">excluir</a>
        </td>


    </tr>
    <?php endforeach;?>
    </table>
    <?php else:?>
        <p> Nenhum usuario encontrado.</p>
        <?php endif;?>

 <a href="principal.php">Voltar</a>

    
</body>
</html>

