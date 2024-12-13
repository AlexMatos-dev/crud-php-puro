<?php
session_start(); // Inicia a sessão para mensagens
include 'db.php'; // Inclui a conexão com o SQLite

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    // Captura e sanitiza os dados
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($name) && !empty($email)) {
        try {
            // Inserção no banco de dados
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone) VALUES (:name, :email, :phone)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Contato salvo com sucesso!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erro ao salvar o contato.";
                $_SESSION['message_type'] = "error";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erro ao inserir no banco: " . $e->getMessage();
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Os campos Nome e Email são obrigatórios.";
        $_SESSION['message_type'] = "warning";
    }

    // Redireciona para a página inicial
    header('Location: index.php');
    exit;
} else {
    echo "Ação inválida.";
}
?>
