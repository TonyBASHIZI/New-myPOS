<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Receipt <?=esc($receipt_no)?></title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Courier New', monospace;
        width: 320px;
        margin: 20px auto;
        padding: 16px;
        color: #000;
        font-size: 13px;
    }
    .receipt-header {
        text-align: center;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 2px dashed #000;
    }
    .receipt-header h2 {
        font-size: 18px;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .receipt-header .meta {
        font-size: 11px;
        color: #444;
    }
    .receipt-items {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .receipt-items th {
        text-align: left;
        font-size: 11px;
        border-bottom: 1px solid #000;
        padding-bottom: 4px;
    }
    .receipt-items td {
        font-size: 12px;
        padding: 3px 0;
        vertical-align: top;
    }
    .receipt-items .qty-col { text-align: center; width: 30px; }
    .receipt-items .amt-col { text-align: right; }

    .receipt-totals {
        border-top: 1px dashed #000;
        padding-top: 8px;
        margin-top: 8px;
    }
    .receipt-totals .row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding: 2px 0;
    }
    .receipt-totals .points-row {
        color: #b8860b;
    }
    .receipt-totals .grand-total {
        font-size: 16px;
        font-weight: bold;
        border-top: 1px solid #000;
        margin-top: 6px;
        padding-top: 6px;
    }
    .receipt-customer {
        border-top: 1px dashed #000;
        margin-top: 8px;
        padding-top: 8px;
        font-size: 11px;
    }
    .receipt-footer {
        text-align: center;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 2px dashed #000;
        font-size: 11px;
        color: #444;
    }
    @media print {
        body { width: 100%; margin: 0; }
    }
</style>
</head>
<body>

    <div class="receipt-header">
        <h2>My POS</h2>
        <div class="meta"><?=date("d/m/Y H:i", strtotime($first_row['date']))?></div>
        <div class="meta">Receipt #<?=esc($receipt_no)?></div>
        <div class="meta">Cashier: <?=esc($cashier_name)?></div>
    </div>

    <table class="receipt-items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="qty-col">Qty</th>
                <th class="amt-col">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sales_items as $item):?>
            <tr>
                <td><?=esc($item['description'])?></td>
                <td class="qty-col"><?=esc($item['qty'])?></td>
                <td class="amt-col">$<?=number_format($item['total'],2)?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <?php
        $TVA_RATE = 0.16;
        $subtotal = $grand_total / (1 + $TVA_RATE);
        $tva = $grand_total - $subtotal;
    ?>

    <div class="receipt-totals">
        <div class="row">
            <span>Subtotal</span>
            <span>$<?=number_format($subtotal,2)?></span>
        </div>
        <div class="row">
            <span>TVA (16%)</span>
            <span>$<?=number_format($tva,2)?></span>
        </div>

        <?php if($points_amount_total > 0):?>
        <div class="row points-row">
            <span>Paid with Points</span>
            <span>-$<?=number_format($points_amount_total,2)?></span>
        </div>
        <?php endif;?>

        <div class="row grand-total">
            <span>TOTAL</span>
            <span>$<?=number_format($grand_total,2)?></span>
        </div>
    </div>

    <?php if($customer):?>
    <div class="receipt-customer">
        <div><b>Customer:</b> <?=esc($customer['name'])?></div>
        <div><b>Phone:</b> <?=esc($customer['phone'])?></div>
        <div><b>Points Balance:</b> <?=number_format($customer['points'],2)?> pts</div>
    </div>
    <?php endif;?>

    <div class="receipt-footer">
        <div>Thank you for your purchase!</div>
    </div>

<script>
    window.onafterprint = function(){ window.close(); };
    window.print();
</script>

</body>
</html>