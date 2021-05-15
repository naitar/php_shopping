<?php

require '../config/common.php';
require '../config/config.php';

$id= $_GET['id'];
$stmt= $pdo -> prepare("DELETE FROM products WHERE id=$id");
$stmt -> execute();
header('location:index.php');

?>