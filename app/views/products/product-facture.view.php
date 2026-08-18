<?php
require('fpdf.php');

// Largeur standard POS-58
$w_page = 58; 
$h_page = 150; // Hauteur ajustable selon la longueur de la facture

class PDF extends FPDF {
    function Header() {
        // Optionnel : Logo ici si nécessaire
    }

    function GenerateInvoice($data, $items) {
        // --- CONFIGURATION ZONE IMPRIMABLE ---
        $margin = 5; // Marge de sécurité (indispensable sur POS-58)
        $w_util = 58 - ($margin * 2); // Soit 48mm réel
        
        $this->SetMargins($margin, 2, $margin);
        $this->AddPage();
        
        // --- INFOS SOCIÉTÉ ---
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell($w_util, 4, "PATRICK BUSINESS CAR", 0, 'C');
        
        $this->SetFont('Arial', '', 6);
        $this->MultiCell($w_util, 3, utf8_decode("RCCM: 19-A-03875 | ID.NAT: 5-910-N52032\nTel: +243 990 284 171"), 0, 'C');
        
        $this->Ln(1);
        $this->Cell($w_util, 0, '', 'T', 1); // Ligne fine de séparation
        $this->Ln(1);

        // --- TITRE FACTURE ---
        $this->SetFont('Arial', 'B', 9);
        $this->Cell($w_util, 5, "FACTURE", 0, 1, 'C');
        
        $this->SetFont('Arial', '', 7);
        $this->Cell($w_util, 4, "Date: " . date('d/m/Y'), 0, 1, 'L');
        $this->Cell($w_util, 4, "Client: .................................", 0, 1, 'L');
        $this->Ln(2);

        // --- TABLEAU DES ARTICLES (Format optimisé 48mm utile) ---
        // Qt (7mm) | Article (26mm) | Total (15mm)
        $col_qty = 7;
        $col_total = 15;
        $col_art = $w_util - $col_qty - $col_total;

        $this->SetFont('Arial', 'B', 7);
        $this->Cell($col_qty, 5, "Qt", 'B', 0, 'L');
        $this->Cell($col_art, 5, "ARTICLE", 'B', 0, 'L');
        $this->Cell($col_total, 5, "TOTAL", 'B', 1, 'R');

        $this->SetFont('Arial', '', 7);
        foreach($items as $item) {
            $x = $this->GetX();
            $y = $this->GetY();
            
            $this->Cell($col_qty, 5, $item['qty'], 0, 0, 'L');
            
            // Nom de l'article avec gestion automatique de la largeur
            $this->MultiCell($col_art, 5, utf8_decode($item['name']), 0, 'L');
            $newY = $this->GetY();
            
            // On place le prix à droite sur la même ligne que le début de l'article
            $this->SetXY($x + $col_qty + $col_art, $y);
            $this->Cell($col_total, 5, $item['pt'], 0, 1, 'R');
            
            // Repositionnement après l'article le plus long
            $this->SetY($newY);
        }

        // --- TOTAL ---
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($col_qty + $col_art, 6, "TOTAL GENERAL  ", 'T', 0, 'R');
        $this->Cell($col_total, 6, $data['total'], 'T', 1, 'R');

        // --- PIED DE PAGE ---
        $this->Ln(3);
        $this->SetFont('Arial', 'I', 6);
        $this->MultiCell($w_util, 3, utf8_decode("Les marchandises ne sont ni reprises ni échangées."), 0, 'C');
        
        // Espace final (indispensable pour la coupe papier)
        $this->Ln(12);
        $this->Cell($w_util, 1, ".", 0, 0, 'C'); 
    }
}

// --- INITIALISATION ---
$data = ['total' => '250.00 $'];
$items = [
    ['qty' => '2', 'name' => 'Amortisseur Avant', 'pt' => '100.00'],
    ['qty' => '1', 'name' => 'Batterie 12V 70Ah', 'pt' => '150.00'],
];

// Création du PDF : P (Portrait), mm (millimètres), format 58x150
$pdf = new PDF('P', 'mm', array(58, 150)); 
$pdf->GenerateInvoice($data, $items);

// Sortie forcée pour éviter le cache du navigateur
$pdf->Output('I', 'ticket_'.time().'.pdf');
