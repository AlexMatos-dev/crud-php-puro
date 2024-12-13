<?php
session_start();
include 'db.php';

// Verifica se o ID foi enviado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados do contato no banco de dados
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o contato não for encontrado
    if (!$contact) {
        $_SESSION['message'] = "Contato não encontrado.";
        $_SESSION['message_type'] = "error";
        header('Location: index.php');
        exit;
    }
} else {
    // Redireciona se o ID não for fornecido
    $_SESSION['message'] = "ID do contato não fornecido.";
    $_SESSION['message_type'] = "error";
    header('Location: index.php');
    exit;
}

// Atualiza o contato ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($name) && !empty($email)) {
        try {
            $stmt = $conn->prepare("UPDATE contacts SET name = :name, email = :email, phone = :phone WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = "Contato atualizado com sucesso!";
            $_SESSION['message_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erro ao atualizar o contato: " . $e->getMessage();
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Os campos Nome e Email são obrigatórios.";
        $_SESSION['message_type'] = "warning";
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contato</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <img src="photos/alexmatosdevs.png" alt="Logo" style="height: 50px; margin-right: 15px; border-radius: 50%;">
    <style>
        body {
            font-family: 'Roboto', sans-serif
        }
    </style>
</head>
<body>
    <div class="container">
    

        <form action="" method="POST" style="max-width: 500px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Editar Contato</h2>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="name" style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Nome</label>
        <input type="text" id="name" name="name" value="<?php echo $contact['name']; ?>" 
               required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="email" style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $contact['email']; ?>" 
               required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;">
    </div>
    
    <div class="form-group" style="margin-bottom: 20px;">
        <label for="phone" style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Telefone</label>
        <input type="text" id="phone" name="phone" value="<?php echo $contact['phone']; ?>" 
               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;">
    </div>
    
    <button type="submit" name="update" style="width: 100%; padding: 12px; background: #4CAF50; color: #fff; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s;">
        Atualizar
    </button>
    
    <a href="index.php" style="display: block; text-align: center; margin-top: 15px; color: #555; text-decoration: none; font-size: 14px;">
        Voltar à Página Inicial
    </a>
</form>


        <?php
        session_start();
        include 'db.php';

        // Mensagens de feedback
        if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>