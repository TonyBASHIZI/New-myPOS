<?php 

$errors = [];
$id = $_GET['id'] ?? null;
$sale = new Sale();
$product = new Product(); 

// On récupère les infos de la vente (qui contient le barcode et la qty)
$row = $sale->first(['id' => $id]);

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{
    // 1. On prépare les données depuis la ligne de vente ($row)
    $barcode = $row['barcode']; 
    $qty_to_return = $row['qty'];

    // 2. MISE À JOUR DU STOCK
    // On ajoute la quantité vendue au stock actuel du produit correspondant
    $sql = "UPDATE products SET qty = qty + :qty_to_return WHERE barcode = :barcode LIMIT 1";
    
    $product->query($sql, [
        'qty_to_return' => $qty_to_return,
        'barcode'       => $barcode
    ]);

    // 3. SUPPRESSION DE LA VENTE
    $sale->delete($row['id']);
  
    // Redirection après succès
    redirect('admin&tab=sales');
}

require views_path('sales/sale-delete');