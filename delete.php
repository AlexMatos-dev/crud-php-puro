<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->bindParam
        ('id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Contato excluído com sucesso!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir o contato.";
            $_SESSION['message_type'] = "error";
        }    
    } catch (PDOException $e) {
        $_SESSION['message'] = "Erro ao excluir o contato: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header('Location: index.php');
    exit;                                                                                                                                                                                                                                                                                                          
}   