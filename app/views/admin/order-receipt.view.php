<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Order <?=esc($order['order_no'])?></title>
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
    .receipt-header h2 { font-size: 18px; letter-spacing: 1px; margin-bottom: 4px; }
    .receipt-header .meta { font-size: 11px; color: #444; }
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: bold;
        padding: 3px 10px;
        border-radius: 10px;
        margin-top: 4px;
        border: 1px solid #000;
    }
    .receipt-items { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .receipt-items th { text-align: left; font-size: 11px; border-bottom: 1px solid #000; padding-bottom: 4px; }
    .receipt-items td { font-size: 12px; padding: 3px 0; vertical-align: top; }
    .receipt-items .qty-col { text-align: center; width: 30px; }
    .receipt-items .amt-col { text-align: right; }
    .receipt-totals { border-top: 1px dashed #000; padding-top: 8px; margin-top: 8px; }
    .receipt-totals .row { display: flex; justify-content: space-between; font-size: 12px; padding: 2px 0; }
    .receipt-totals .grand-total { font-size: 16px; font-weight: bold; border-top: 1px solid #000; margin-top: 6px; padding-top: 6px; }
    .receipt-customer { border-top: 1px dashed #000; margin-top: 8px; padding-top: 8px; font-size: 11px; }
    .receipt-footer { text-align: center; margin-top: 16px; padding-top: 12px; border-top: 2px dashed #000; font-size: 11px; color: #444; }
    @media print { body { width: 100%; margin: 0; } }
</style>
</head>
<body>

    <div class="receipt-header">
        <h2>My POS</h2>
        <div class="meta">Order Receipt</div>
        <div class="meta"><?=date("d/m/Y H:i", strtotime($order['created_at']))?></div>
        <div class="meta">Order #<?=esc($order['order_no'])?></div>
        <div class="meta">Created by: <?=esc($order['creator_name'] ?: 'Unknown')?></div>
        <div class="status-badge"><?=esc($order['status'])?></div>
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
            <?php foreach($items as $item):?>
            <tr>
                <td><?=esc($item['description'])?></td>
                <td class="qty-col"><?=esc($item['qty'])?></td>
                <td class="amt-col">$<?=number_format($item['total'],2)?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <div class="receipt-totals">
        <div class="row grand-total">
            <span>TOTAL</span>
            <span>$<?=number_format($order['total'],2)?></span>
        </div>
    </div>

    <?php if(!empty($order['customer_name']) || !empty($order['customer_phone'])):?>
    <div class="receipt-customer">
        <?php if(!empty($order['customer_name'])):?><div><b>Customer:</b> <?=esc($order['customer_name'])?></div><?php endif;?>
        <?php if(!empty($order['customer_phone'])):?><div><b>Phone:</b> <?=esc($order['customer_phone'])?></div><?php endif;?>
    </div>
    <?php endif;?>

    <div class="receipt-footer">
        <div>This is an order confirmation, not a paid receipt.</div>
    </div>

<script>
    window.onafterprint = function(){ window.close(); };
    window.print();
</script>

</body>
</html>