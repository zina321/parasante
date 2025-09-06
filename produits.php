<?php
session_start();

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Supprimer un produit
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM produits WHERE id = $id");
    header("Location: produits.php");
    exit;
}

$result = $conn->query("SELECT * FROM produits");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Produits</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            color: #333;
        }

        nav {
            background-color: #34495e;
            padding: 15px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        nav .greeting {
            font-weight: bold;
        }

        nav .links {
            display: flex;
            flex-wrap: wrap;
        }

        nav .links a {
            color: white;
            margin: 5px 8px;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav .links a:hover {
            background-color: #99a3adff;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            nav .greeting {
                margin-bottom: 10px;
            }

            nav .links {
                flex-direction: column;
                gap: 10px;
                width: 100%;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            nav .links a {
                width: 100%;
                max-width: 300px;
                text-align: center;
                margin: 5px 0;
                font-size: 1rem;
            }

            .greeting {
                font-size: 1rem;
            }
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin: 20px auto;
            max-width: 1000px;
            padding: 0 20px;
        }

        .top-bar a {
            text-decoration: none;
            background: #28a745;
            padding: 10px 15px;
            color: white;
            border-radius: 5px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
            position: relative;
        }

        .card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card h3 { margin: 10px 0 5px; }

        .card p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .btns {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .btns a {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 13px;
        }

        .edit { background-color: #007BFF; }
        .delete { background-color: #dc3545; }
    </style>
</head>
<body>

<nav>
    <div class="greeting"><?php

$email = $_SESSION['admin_email'] ?? null;

if ($email) {
    // Extraire la partie avant le @
    $username = strstr($email, '@', true);
} else {
    $username = "Invité";
}

echo "Bonjour, " . htmlspecialchars($username);
?>
 👋</div>
    <div class="links">
             <a href="dashboard.php">tableau de bord</a>
        <a href="admin.php">Voir les commandes</a>
   
        <a href="logout.php">Déconnexion</a>
    </div>
</nav>

<div class="top-bar">
    <h2>Produits</h2>
    <a href="ajouter-produit.php">+ Ajouter un produit</a>
</div>

<div class="container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
            <h3><?= htmlspecialchars($row['nom']) ?></h3>
            <p><strong>Prix:</strong> <?= htmlspecialchars($row['prix']) ?> TND</p>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="btns">
                <a class="edit" href="modifier-produit.php?id=<?= $row['id'] ?>">Modifier</a>
                <a class="delete" href="produits.php?delete=<?= $row['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
