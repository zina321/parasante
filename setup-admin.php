<?php
// setup-admin.php
session_start();

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si admin existe déjà
$result = $conn->query("SELECT COUNT(*) as total FROM admins");
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
    // Admin déjà créé → redirection vers login
    header("Location: login.php");
    exit;
}

// Si formulaire soumis, créer admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nom = trim($_POST['nom']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_mdp = $_POST['confirm_mdp'];

    $error = "";

    if (empty($email) || empty($nom) || empty($mot_de_passe) || empty($confirm_mdp)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif ($mot_de_passe !== $confirm_mdp) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Hash du mot de passe
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insertion admin
        $stmt = $conn->prepare("INSERT INTO admins (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $mot_de_passe_hash);

        if ($stmt->execute()) {
            $success = "Admin créé avec succès ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Erreur lors de la création de l'admin : " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Création Admin - Setup</title>
<style>
    body {
        margin: 0; padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .container {
        background: white;
        padding: 30px 25px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        max-width: 400px;
        width: 90%;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #34495e;
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #333;
    }
    input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        box-sizing: border-box;
    }
    button {
        margin-top: 25px;
        width: 100%;
        padding: 12px;
        background-color: #34495e;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #2c3e50;
    }
    .error, .success {
        margin-top: 15px;
        text-align: center;
        font-weight: bold;
    }
    .error {
        color: #e74c3c;
    }
    .success {
        color: #27ae60;
    }
    @media (max-width: 480px) {
        .container {
            padding: 20px 15px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h2>Créer Admin Principal</h2>

    <?php
    if (isset($error) && $error !== "") {
        echo "<div class='error'>$error</div>";
    } elseif (isset($success)) {
        echo "<div class='success'>$success</div>";
        echo '<p style="text-align:center; margin-top:20px;"><a href="login.php" style="color:#34495e; text-decoration:none;">→ Aller à la page de connexion</a></p>';
    }
    ?>

    <?php if (!isset($success)) : ?>
    <form method="post" novalidate>
        <label for="nom">Nom complet</label>
        <input type="text" id="nom" name="nom" required placeholder="Votre nom complet" />

        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" required placeholder="admin@example.com" />

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required placeholder="Mot de passe sécurisé" />

        <label for="confirm_mdp">Confirmer mot de passe</label>
        <input type="password" id="confirm_mdp" name="confirm_mdp" required placeholder="Confirmer le mot de passe" />

        <button type="submit">Créer admin</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
