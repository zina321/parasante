<?php
include("securite.php");
$conn = new mysqli("localhost", "root", "", "parasante");
$id = $_GET['id'];
$conn->query("DELETE FROM produits WHERE id=$id");
header("Location: produits.php");
?>
