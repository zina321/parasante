<?php
session_start();

// Vérifie si l'admin est bien connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Sécurité : id entier
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupération du produit
$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo "Produit introuvable.";
    exit;
}
$prod = $res->fetch_assoc();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $desc = $_POST['description'];
    $prix = floatval($_POST['prix']);
    $image = $prod['image']; // image actuelle par défaut

    // Si nouvelle image
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    }

    // Update
    $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $nom, $desc, $prix, $image, $id);
    $stmt->execute();

    // Redirection
    header("Location: produits.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            margin: 0;
        }

        h2 {
            color: #444;
            text-align: center;
        }

        form {
            background: white;
            padding: 20px;
            max-width: 500px;
            width: 90%;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #27ae60;
        }

        img {
            max-width: 100px;
            margin-top: 10px;
            display: block;
        }

        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            input, textarea, button {
                font-size: 15px;
            }

            img {
                max-width: 80px;
            }
        }
    </style>
</head>
<body>

<h2>Modifier Produit</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Nom :</label>
    <input name="nom" value="<?= htmlspecialchars($prod['nom']) ?>" required>

    <label>Description :</label>
    <textarea name="description" required><?= htmlspecialchars($prod['description']) ?></textarea>

    <label>Prix :</label>
    <input name="prix" type="number" step="0.01" value="<?= htmlspecialchars($prod['prix']) ?>" required>

    <label>Image actuelle :</label><br>
    <img src="images/<?= htmlspecialchars($prod['image']) ?>" alt="Image produit"><br>

    <label>Nouvelle image (facultatif) :</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">✔ Modifier</button>
</form>

</body>
</html>

