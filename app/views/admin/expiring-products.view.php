<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fa fa-triangle-exclamation me-2"></i>Products Expiring Soon</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-dark" onclick="export_pdf()">
                <i class="fa fa-file-pdf"></i> PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="export_excel()">
                <i class="fa fa-file-excel"></i> Excel
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0" id="expiringTable">
            <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Expiry Date</th>
                    <th>Days Left</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($products)):?>
                    <?php foreach($products as $p):
                        $days_left = (int)((strtotime($p['expire_date']) - strtotime(date('Y-m-d'))) / 86400);
                    ?>
                    <tr>
                        <td><?=esc($p['barcode'])?></td>
                        <td><?=esc($p['description'])?></td>
                        <td><?=esc($p['qty'])?></td>
                        <td><?=!empty($p['purchase_price']) ? '$'.number_format($p['purchase_price'],2) : '-'?></td>
                        <td>$<?=number_format($p['amount'],2)?></td>
                        <td><?=date('d M Y', strtotime($p['expire_date']))?></td>
                        <td>
                            <span class="badge <?=$days_left <= 7 ? 'bg-danger' : 'bg-warning text-dark'?>">
                                <?=$days_left?>d
                            </span>
                        </td>
                    </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No products expiring soon</td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<script>
function export_pdf()
{
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4');

    doc.setFontSize(14);
    doc.text("Products Expiring Soon", 40, 30);
    doc.setFontSize(9);
    doc.text("Generated: " + new Date().toLocaleString(), 40, 45);

    doc.autoTable({
        html: '#expiringTable',
        startY: 55,
        theme: 'striped',
        headStyles: { fillColor: [179, 58, 58], textColor: 255, fontSize: 8 },
        bodyStyles: { fontSize: 8 },
        margin: { left: 20, right: 20 }
    });

    doc.save('expiring_products_' + Date.now() + '.pdf');
}

function export_excel()
{
    var table = document.getElementById('expiringTable');
    var wb = XLSX.utils.table_to_book(table, { sheet: "Expiring Products" });
    XLSX.writeFile(wb, 'expiring_products_' + Date.now() + '.xlsx');
}
</script>