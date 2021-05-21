<?php
    session_start();

    if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']))
    {
      header('location:login.php');
    }

    unset($_SESSION['cart']);
    header("location:index.php");
?>