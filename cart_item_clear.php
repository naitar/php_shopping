<?php
    session_start();
    require 'Config/config.php';
    if(isset($_GET['pid']))
    {
        unset($_SESSION['cart']['id'.$_GET['pid']]);
    }
    header("location:cart.php");

?>