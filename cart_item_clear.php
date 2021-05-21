<?php
    session_start();
    require 'Config/config.php';

    if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']))
    {
      header('location:login.php');
    }

    if(isset($_GET['pid']))
    {
        unset($_SESSION['cart']['id'.$_GET['pid']]);
    }
    header("location:cart.php");

?>