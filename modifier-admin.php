<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur connexion : " . $conn->connect_error);
}

// Récupérer les infos actuelles
$result = $conn->query("SELECT * FROM admins LIMIT 1");
$admin = $result->fetch_assoc();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_nom = trim($_POST['nom']);
    $new_email = trim($_POST['email']);
    $new_pass = trim($_POST['mot_de_passe']);

    if (!empty($new_nom) && !empty($new_email)) {
        $sql = "UPDATE admins SET nom=?, email=?";
        $types = "ss";
        $params = [$new_nom, $new_email];

        // Si un nouveau mot de passe est fourni
        if (!empty($new_pass)) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $sql .= ", mot_de_passe=?";
            $types .= "s";
            $params[] = $hashed_pass;
        }

        $sql .= " WHERE id=?";
        $types .= "i";
        $params[] = $admin['id'];

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $success = "✅ Informations modifiées avec succès.";
            // Mettre à jour session si email changé
            $_SESSION['admin_email'] = $new_email;
        } else {
            $error = "❌ Erreur : " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "⚠️ Nom et Email sont obligatoires.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #f2f2f2;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ccc;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border: none;
        }
        .msg {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        @media screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Modifier les informations de l’admin</h2>

    <?php if ($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

        <label>Nouveau mot de passe (laisser vide si inchangé)</label>
        <input type="password" name="mot_de_passe" placeholder="Laisser vide pour ne pas changer">

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
