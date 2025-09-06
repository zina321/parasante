<?php
$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur connexion DB : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'valider') {
        $conn->query("UPDATE commandes SET statut = 'validée' WHERE id = $id");
    } elseif ($action === 'supprimer') {
        $conn->query("DELETE FROM commandes WHERE id = $id");
    }

    header("Location: admin.php");
    exit;
}
?>
