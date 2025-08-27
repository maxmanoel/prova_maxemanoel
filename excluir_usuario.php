<?php
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

//INICIALIZA AS VARIAVEIS
$usuario = null;

//Busca todos os usuario cadastrados em ordem alfabetica
$sql="SELECT * from usuario order by nome ASC";
$stmt = $pdo->prepare($sql);
$stmt ->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//SE um id for passado via get, exclui o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];

    //Exclui o usuaroi do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindparam(':id',$id_usuario,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Usuario excluido com sucesso!');window.location.href='excluir_usuario.php';</script>";
    }else{
        echo "<script>alert('Erro ao excluir usuario!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuário</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            text-align: center;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            margin: 20px auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f7f9fb;
        }

        tr:hover {
            background-color: #d6eaf8;
            transition: 0.2s;
        }

        a {
            display: inline-block;
            margin: 5px 0;
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            background-color: #c0392b;
            transition: background-color 0.2s ease;
        }

        a:hover {
            background-color: #922b21;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background-color: #1c5980;
        }

        address {
            margin-top: 30px;
            font-size: 0.9em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<h2>Excluir Usuário</h2>

<?php if (!empty($usuarios)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>

        <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['id_perfil']) ?></td>
                <td>
                    <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                       Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum usuário encontrado</p>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

</body>
</html>
