<?php
session_start();
require_once 'conexao.php';
require_once 'menu_dropdow.php';

if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA A VARIAVEL PARA EVITAR ERROS
$usuarios = [];

// SE O FORMULARIO FOR ENCIADO, BUSCA O USUARIO PELO ID OU NOME

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty( $_POST["busca"] )) {
    $busca = trim($_POST["busca"]);

    // VERIFICA SE A BUSCA É UM NÚMERO OU UM NOME

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
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

        form {
            margin-bottom: 25px;
        }

        input[type="text"] {
            padding: 10px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: #2980b9;
        }

        button {
            padding: 10px 15px;
            border: none;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background-color: #1c5980;
        }

        table {
            margin: 20px auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 90%;
            max-width: 900px;
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
            transition: background-color 0.2s ease;
        }

        a[href*="alterar"] {
            background-color: #27ae60;
        }

        a[href*="alterar"]:hover {
            background-color: #1e8449;
        }

        a[href*="excluir"] {
            background-color: #c0392b;
        }

        a[href*="excluir"]:hover {
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
            font-style: normal;
        }
    </style>
</head>
<body>

<h2>Lista de Usuários</h2>

<form action="buscar_usuario.php" method="POST">
    <label for="busca">Digite o ID ou NOME (opcional):</label>
    <input type="text" id="busca" name="busca">
    <button type="submit">Buscar</button>
</form>

<?php if(!empty($usuarios)): ?>
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
                <td><?=htmlspecialchars($usuario['id_usuario'])?></td>
                <td><?=htmlspecialchars($usuario['nome'])?></td>
                <td><?=htmlspecialchars($usuario['email'])?></td>
                <td><?=htmlspecialchars($usuario['id_perfil'])?></td>
                <td>
                    <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>">Alterar</a>
                    <br>
                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum usuário encontrado.</p>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoe / estudante / desenvolvimento 
</address>

</body>
</html>



