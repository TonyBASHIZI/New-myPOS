<?php
// N'oubliez pas l'inclusion de votre classe Database
require('fpdf.php');

if (isset($_GET['id']) && $_GET['id'] != "") {
    
    $db = new Database();
    $id_vente = $_GET['id'];

    // --- RÉCUPÉRATION DES DONNÉES ---
    $sql_sale = "SELECT * FROM sales WHERE receipt_no = :id";
    $sales_items = $db->query($sql_sale, ['id' => $id_vente]);

    if (!$sales_items) {
        die("Erreur : Aucune donnée trouvée pour ce reçu.");
    }

    $first_row = $sales_items[0];
    $user_id = $first_row['user_id'] ?? 0; 
    $sql_user = "SELECT * FROM users WHERE id = :uid LIMIT 1";
    $user_result = $db->query($sql_user, ['uid' => $user_id]);
    $user = $user_result ? $user_result[0] : ['username' => 'Inconnu'];

    // --- CONFIGURATION PDF 58MM ---
    class PDF extends FPDF {
        function Header() {}
        function Footer() {}
    }

    // Largeur 58mm, Hauteur variable (200mm)
    $pdf = new PDF('P', 'mm', array(58, 200)); 
    $pdf->SetMargins(2, 4, 2); // Marges réduites à 2mm pour utiliser 54mm utiles
    $pdf->SetAutoPageBreak(true, 5);
    $pdf->AddPage();

    // Largeur utile réelle (58 - 4mm de marges = 54mm)
    $w_util = 54;

    // --- DESIGN DU TICKET ---
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell($w_util, 5, "PATRICK BUSINESS CAR", 0, 'C');
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell($w_util, 4, utf8_decode("VENTE DE PIECES DE RECHANGE"), 0, 'C');
    $pdf->Ln(2);
    $pdf->Cell($w_util, 0, "", 'T', 1, 'C');
    $pdf->Ln(2);

    // INFOS GÉNÉRALES
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($w_util, 5, utf8_decode("TICKET N° : ") . $id_vente, 0, 1, 'C'); // Centré
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell($w_util, 4, "Date : " . date('d/m/Y H:i', strtotime($first_row['date'])), 0, 1, 'C'); // Centré
    $pdf->Cell($w_util, 4, "Vendeur : " . utf8_decode($user['username']), 0, 1, 'C'); // Centré

    $pdf->Ln(2);
    $pdf->Cell($w_util, 0, "", 'T', 1, 'C');
    $pdf->Ln(1);

    // --- ENTÊTE TABLEAU (Plus gros, 9pt) ---
    // Répartition : Qty (8mm) | Desc (26mm) | Montant (20mm)
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(8, 5, "Qt", 0, 0, 'L');
    $pdf->Cell(26, 5, "ARTICLE", 0, 0, 'L');
    $pdf->Cell(20, 5, "P.T", 0, 1, 'R');
    $pdf->Cell($w_util, 0, "", 'T', 1);
    $pdf->Ln(1);

    // --- BOUCLE PRODUITS (Police 9pt) ---
    $grand_total = 0;

    foreach ($sales_items as $item) {
        $startX = $pdf->GetX();
        $startY = $pdf->GetY();
        
        // 1. Quantité (Agrandie et en gras)
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(8, 5, $item['qty'], 0, 0, 'L');
        
        // 2. Description (Police normale 8pt)
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(26, 5, utf8_decode($item['description']), 0, 'L');
        $endY = $pdf->GetY();
        
        // 3. Montant (Prix Total en gras 9pt)
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY($startX + 34, $startY); // On se place après les 8mm + 26mm
        $pdf->Cell(20, 5, number_format($item['total'], 2) . " $", 0, 1, 'R');
        
        $pdf->SetY($endY); // Repositionne le curseur pour le prochain article
        $grand_total += $item['total'];
        $pdf->Ln(1);
    }

    // --- TOTAL GÉNÉRAL (BIEN VISIBLE) ---
    $pdf->Ln(1);
    $pdf->Cell($w_util, 0, "", 'T', 1);
    $pdf->SetFont('Arial', 'B', 12); // Très gros pour le prix final
    $pdf->Cell(25, 10, "TOTAL", 0, 0, 'L');
    $pdf->Cell(29, 10, number_format($grand_total, 2) . " $", 0, 1, 'R');

    // PIED DE PAGE
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'BI', 8); // Agrandissement à 8pt
    $pdf->MultiCell($w_util, 4, utf8_decode("Marchandises ni reprises ni échangées.\nMerci de votre confiance !"), 0, 'C');

    $pdf->Ln(10);
    $pdf->Cell($w_util, 2, ".", 0, 1, 'C');

    $pdf->Output('I', 'Ticket_'.$id_vente.'.pdf');
    exit;
}
