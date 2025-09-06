<?php
$conn = new mysqli("localhost", "root", "", "parasante");
$produits = $conn->query("SELECT * FROM produits");

$data = [];

while($p = $produits->fetch_assoc()) {
    $data[] = $p;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
