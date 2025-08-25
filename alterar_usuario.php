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

//SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU PELO NOME
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (!empty($_POST['busca_usuario'])) {
        $busca = trim($_POST["busca_usuario"]);

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // SE O USUÁRIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA

    if(!$usuario){
        echo "<script>alert('Usuário não encontrado!');</script>";
    }
}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: left;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #2980b9;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            margin-right: 10px;
            transition: background-color 0.2s ease;
            color: white;
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

        #sugestoes {
            margin-top: 5px;
            padding: 5px;
            background-color: #ecf0f1;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
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

<h2>Alterar Usuário</h2>

<form action="alterar_usuario.php" method="POST">
    <label for="busca_usuario">Digite o ID ou NOME do usuário:</label>
    <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">
    <div id="sugestoes"></div>
    <button type="submit">Buscar</button>
</form>

<?php if ($usuario): ?>
    <form action="processa_alteracao_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario'])?>">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required data-mask="nome">


        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?=htmlspecialchars($usuario['email'])?>" required>

        <label for="id_perfil">Perfil:</label>
        <select id="id_perfil" name="id_perfil">
            <option value="1"<?=$usuario['id_perfil'] == 1 ? ' selected': '' ?>>Administrador</option>
            <option value="2"<?=$usuario['id_perfil'] == 2 ? ' selected': '' ?>>Secretaria</option>
            <option value="3"<?=$usuario['id_perfil'] == 3 ? ' selected': '' ?>>Almoxarife</option>
            <option value="4"<?=$usuario['id_perfil'] == 4 ? ' selected': '' ?>>Cliente</option>
        </select>

        <?php if ($_SESSION['perfil'] == 1): ?>
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha">
        <?php endif; ?>

        <button type="submit">Alterar</button>
        <button type="reset">Cancelar</button>
    </form>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoe / estudante / desenvolvimento 
</address>

</body>
</html>
