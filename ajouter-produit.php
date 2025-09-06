<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "parasante");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];

    // Upload image
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, "images/" . $image);

    $stmt = $conn->prepare("INSERT INTO produits (nom, prix, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $nom, $prix, $description, $image);
    $stmt->execute();

    header("Location: produits.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Produit</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 40px; }
        form {
            background: white; padding: 20px; border-radius: 10px;
            width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            padding: 10px 20px; background: #28a745; color: white;
            border: none; border-radius: 5px; cursor: pointer;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Ajouter un nouveau produit</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="nom" placeholder="Nom du produit" required>
    <input type="number" step="0.01" name="prix" placeholder="Prix" required>
    <textarea name="description" placeholder="Description du produit" rows="4" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Ajouter</button>
</form>

</body>
</html>
