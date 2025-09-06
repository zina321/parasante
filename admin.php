<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur connexion DB : " . $conn->connect_error);
}

$dateFiltre = $_GET['date'] ?? '';
$filtreSql = $dateFiltre ? "WHERE DATE(date_commande) = '" . $conn->real_escape_string($dateFiltre) . "'" : "";

$result = $conn->query("SELECT * FROM commandes $filtreSql ORDER BY date_commande DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Commandes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f5f6fa;
            padding: 0;
        }

        nav {
            background-color: #2c3e50;
            padding: 15px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        nav .greeting {
            font-weight: bold;
            margin-bottom: 8px;
        }

        nav .links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav .links a:hover {
            background-color: #34495e;
        }

        main {
            max-width: 1100px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 2rem;
            text-align: center;
        }

        .top-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .top-bar form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 14px;
}


        .top-bar input[type="date"] {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .top-bar button, .top-bar a.btn-export {
            padding: 8px 12px;
            border: none;
            background-color: #009688;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .top-bar button:hover, .top-bar a.btn-export:hover {
            background-color: #00796b;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
            font-size: 15px;
        }

        th {
            background-color: #ecf0f1;
        }

        td img {
            width: 60px;
            height: auto;
        }

        form button {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
           
        }

        form button[name="action"][value="valider"] {
            background-color: #4CAF50;
            color: white;
        }

        form button[name="action"][value="supprimer"] {
            background-color: #e74c3c;
            color: white;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                text-align: center;
            }

            nav .links {
                width: 100%;
                margin-top: 10px;
            }

            nav .links a {
                display: inline-block;
                margin: 5px 8px;
            }

            main {
                margin: 20px 10px;
                padding: 15px;
            }

            th, td {
                font-size: 13px;
                padding: 8px;
            }

            .top-bar input[type="date"],
            .top-bar button,
            .top-bar a.btn-export {
                font-size: 14px;
                padding: 6px 10px;
                margin-top: 5px;
            }
        }

        @media (max-width: 480px) {
            .top-bar form {
                flex-direction: column;
                align-items: center;
            }

            .top-bar .btn-export {
                display: block;
                width: 100%;
                margin-bottom: 10px;
                text-align: center;
            }

            nav .links a {
                display: block;
                margin: 5px 0;
            }

            td:nth-child(3),
            th:nth-child(3), /* Adresse */
            td:nth-child(4),
            th:nth-child(4), /* Téléphone */
            td:nth-child(6),
            th:nth-child(6)  /* Date */
            {
                display: none;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="greeting">Bienvenue <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?> |</div>
    <div class="links">
        <a href="dashboard.php">Tableau de bord</a>
        <a href="produits.php">Gérer les produits</a>
        <a href="logout.php">Déconnexion</a>
    </div>
</nav>

<main>
    <h1>📦 Liste des commandes</h1>

    <div class="top-bar">
        <form method="get" action="admin.php">
            <label for="date">📅 Filtrer par date :</label>
            <input type="date" name="date" id="date" value="<?= htmlspecialchars($dateFiltre) ?>">
            <button type="submit">Filtrer</button> 
            <a href="admin.php" class="btn-export">Réinitialiser</a>
        </form>

        <a href="<?= $dateFiltre ? "export_pdf.php?date=" . urlencode($dateFiltre) : "export_pdf.php" ?>" target="_blank" class="btn-export">📄 Exporter en PDF</a>
    </div>

    <div class="table-container">
        <table>
           <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Adresse</th>
        <th>Téléphone</th>
        <th>Produits</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Total</th> <!-- Nouvelle colonne -->
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php $index = 0; ?>
    <?php while($row = $result->fetch_assoc()): $index++; ?>
    <tr>
        <td>#<?= $index ?></td>
        <td><?= htmlspecialchars($row['nom']) ?></td>
        <td><?= htmlspecialchars($row['adresse']) ?></td>
        <td><?= htmlspecialchars($row['telephone']) ?></td>
        <td>
            <?php
            $prods = json_decode($row['produit'], true);
            $total = 0;
            if (is_array($prods)) {
                foreach ($prods as $p) {
                    echo htmlspecialchars($p['nom']) . " - " . htmlspecialchars($p['prix']) . " TND<br>";
                    $total += floatval($p['prix']);
                }
            } else {
                echo "Aucun produit";
            }
            ?>
        </td>
        <td><?= $row['date_commande'] ?></td>
        <td><?= htmlspecialchars($row['statut'] ?? 'en attente') ?></td>
        <td><?= number_format($total, 2) ?> TND</td> <!-- Affichage total -->
        <td>
            <form method="post" action="actions.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="valider" title="Valider la commande">✅</button>
                <button type="submit" name="action" value="supprimer" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer la commande">🗑</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>

        </table>
    </div>
</main>

</body>
</html>
