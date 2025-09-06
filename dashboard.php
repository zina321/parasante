<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Tableau de bord Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url("BKDB.jpeg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* Ajouter un voile léger sur le fond pour lisibilité */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: 0;
        }

        nav {
            position: relative;
            z-index: 1;
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

        main {
            position: relative;
            z-index: 1;
            flex: 1;
            padding: 30px 20px;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            font-size: 2.2rem;
            color: #34495e;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }

        /* Responsive */
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

            main {
                padding: 20px 15px;
                margin: 20px;
            }

            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }
        }
        #btn{
            background-origin: padding-box;
            color: white;
            bo

            

        }


        }
        .admin-actions {
    margin-top: 30px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.admin-actions .btn {
    background-color: #34495e;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.admin-actions .btn:hover {
    background-color: #2c3e50;
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
        @media (max-width: 600px) {
    .admin-actions {
        flex-direction: column;
        gap: 15px;
    }

    .admin-actions .btn {
        width: 100%;
        text-align: center;
    }
}
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
?> 👋</div>
    <div class="links">
        <a href="admin.php"> Voir les commandes</a>
        <a href="produits.php"> Gérer les produits</a>
        <a href="logout.php">Déconnexion</a>
    </div>
</nav>

<main>
    <h1>Tableau de bord</h1>
    <p>Bienvenue dans votre espace d'administration. Ici, vous pouvez gérer votre site, vos produits, commandes, utilisateurs, etc.</p>

    <div class="admin-actions">
        <a href="modifier-admin.php" class="btn">🔒 Modifier mot de passe</a>
        <a href="ajouter_admin.php" class="btn">➕ Créer nouvel admin</a>
    </div>
</main>



</body>
</html>
