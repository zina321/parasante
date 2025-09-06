<?php
require('fpdf/fpdf.php');

// Connexion DB
$conn = new mysqli("localhost", "root", "", "parasante");
if ($conn->connect_error) {
    die("Erreur connexion DB : " . $conn->connect_error);
}

// Filtrage
$dateFiltre = $_GET['date'] ?? '';
$filtreSql = $dateFiltre ? "WHERE DATE(date_commande) = '" . $conn->real_escape_string($dateFiltre) . "'" : "";
$result = $conn->query("SELECT * FROM commandes $filtreSql ORDER BY date_commande DESC");

// Création PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// 🔰 TITRE
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 102, 102);
$pdf->Cell(0, 10, utf8_decode('Liste des Commandes ParaSanté'), 0, 1, 'C');
$pdf->Ln(3);

// 📆 Date (juste la date)
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, utf8_decode("📅 Exporté le : ") . date("d/m/Y"), 0, 1, 'R');
$pdf->Ln(2);

// 📑 En-têtes (ajout de la colonne Total)
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 150, 136);
$pdf->SetTextColor(255);
$headers = ['#', 'Nom', 'Adresse', 'Téléphone', 'Produits', 'Date', 'Total'];
$widths = [10, 30, 40, 30, 50, 25, 20];
foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 10, utf8_decode($header), 1, 0, 'C', true);
}
$pdf->Ln();

// 🖋 Données
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0);
$pdf->SetFillColor(245, 245, 245);
$fill = false;

$index = 0;

while ($row = $result->fetch_assoc()) {
    $index++;

    // Préparation produits + calcul total
    $produits = json_decode($row['produit'], true);
    $listeProduits = [];
    $total = 0;

    if (is_array($produits)) {
        foreach ($produits as $produit) {
            $nom = $produit['nom'] ?? '';
            $prix = floatval($produit['prix'] ?? 0);
            $listeProduits[] = "$nom - $prix DT";
            $total += $prix;
        }
    }

    $produitsTxt = implode(", ", $listeProduits);

    // Données avec Total ajouté
    $data = [
        $index,
        utf8_decode($row['nom']),
        utf8_decode($row['adresse']),
        $row['telephone'],
        utf8_decode($produitsTxt),
        date("Y-m-d", strtotime($row['date_commande'])),
        number_format($total, 2) . " DT"
    ];

    // Calcul max lignes nécessaires par cellule
    $lineHeight = 5;
    $maxLines = 1;
    for ($i = 0; $i < count($data); $i++) {
        $nbLines = $pdf->GetStringWidth($data[$i]) > $widths[$i]
            ? ceil($pdf->GetStringWidth($data[$i]) / ($widths[$i] - 2))
            : 1;
        $maxLines = max($maxLines, $nbLines);
    }

    $rowHeight = $lineHeight * $maxLines;
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    for ($i = 0; $i < count($data); $i++) {
        $pdf->SetXY($x, $y);
        $pdf->MultiCell($widths[$i], $lineHeight, $data[$i], 1, 'L', $fill);
        $x += $widths[$i];
        $pdf->SetY($y);
    }

    $pdf->Ln($rowHeight);
    $fill = !$fill;
}

$pdf->Output('D', 'commandes_para.pdf');
?>
