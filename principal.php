<?php 
 session_start();
require_once 'conexao.php';

f(!isset($_SESSION['id_usuario'])){
    header("Location:login.php")

}


// obtendo o nome do perfil do usuario logado

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome, perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmt->bindParam(':id_perfil', $id_perfil);
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// definição das permissoaes por perfil

$Permissoes = 1=>["casdastrar=>"["cadastro_usuario.php", "cadastro_perfil.php","cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php", 
"cadastro_funcionario.php"]],

"Buscar"=>["buscar_usuario.php", "buscar_perfil.php","buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", 
"buscar_funcionario.php"],

"Alterar=>"["Alterar_usuario.php", "Alterar_perfil.php","Alterar_cliente.php", "Alterar_fornecedor.php", "Alterar_produto.php", 
"Alterar_funcionario.php"],


"excluir"=>["excluir_usuario.php", "excluir_perfil.php","excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", 
"excluir_funcionario.php"],

 2=>["casdastrar"=>["cadastro_cliente.php"],

 "Buscar"=>["Buscar_cliente.php", "Buscar_fornecedor.php", "Buscar_produto.php"],


 "Alterar"=>["Alterar_fornecedor.php", "Alterar_produto.php"]],


 3=>["casdastrar"=>["cadastro_fornecedor.php", "cadastro_produto.php", 
 "cadastro_funcionario.php"],

 "Buscar"=>["Buscar_cliente.php", "Buscar_fornecedor.php", 
 "Buscar_produto.php"],

"Alterar"=>["Alterar_fornecedor.php", 
 "Alterar_produto.php"],

 "excluir"=>["excluir_produto.php"]],


 4=>["casdastrar"=>["cadastro_cliente.php"],

 "Buscar"=>["Buscar_produto.php"],

"Alterar"=>["Alterar_cliente.php"]],

    




// obtendo as apocoes para o perfil

$opcoes_menu = $Permissoes[$id_perfil];

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>

</head>
<body>
    <header>
        <div class="saudacao">

<h2>Bem vindo:</h2>
</div>

<div class="logout">

<h2> Bem Vindo,<?php echo $_SESSION['usuario'];?>. Perfil: <?php echo $nome_perfil;?></h2>


    <form action="logout.php" method="POST">
        
            <button type="submit"> logout </button>

</form>
</div>

</header>

<nav>
    <ul class="menu">
        <?php foreach($opcoes_menu as $categoria=> $arquivo):?>
            <li class="dropdown">

        <a href="#"><?=$categoria ?></a>


        <ul class="dropdown-menu">
        <?php foreach($arquivo as $arquivo):?>
            <li>
                <s href="<?=$arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php")))?></a>

        </li>
        

        </ul>

        </li>
        </ul>

</body>
</html>




