<?php
    session_start();
    require '../Config/config.php';

    if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
        header('location:login.php');
    }
    
    $id = $_GET['id'];
    
    $stmt = $pdo -> prepare("DELETE FROM users where id=$id");
    $stmt->execute();

    header('location:userlist.php');



?>