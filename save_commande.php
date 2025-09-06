<?php
$conn = new mysqli("localhost", "root", "", "parasante");
$data = json_decode(file_get_contents("php://input"), true);

$nom = $conn->real_escape_string($data['nom']);
$adresse = $conn->real_escape_string($data['adresse']);
$telephone = $conn->real_escape_string($data['telephone']);
$produit = $conn->real_escape_string(json_encode($data['produit']));
$paiement = $conn->real_escape_string(json_encode($data['paiement'] ?? []));

$sql = "INSERT INTO commandes (nom, adresse, telephone, produit, paiement) 
        VALUES ('$nom', '$adresse', '$telephone', '$produit', '$paiement')";

if ($conn->query($sql)) {
    echo "✅ Commande enregistrée avec succès.";
} else {
    echo "❌ Erreur : " . $conn->error;
}
?>
