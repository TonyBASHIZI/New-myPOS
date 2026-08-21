<style>
@media print {
    body * { visibility: hidden; }
    #printable-inventory, #printable-inventory * { visibility: visible; }
    #printable-inventory {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print { display: none !important; }
}
</style>
<div class="card mb-3 border-0 shadow-sm no-print">
    <div class="card-body py-3">
        <form method="get" class="row g-2 align-items-end">
            <input type="hidden" name="pg" value="admin">
            <input type="hidden" name="tab" value="inventory">
            <div class="col-auto">
                <label class="form-label small text-muted mb-1">From</label>
                <input type="date" name="date_from" value="<?=esc($date_from)?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <label class="form-label small text-muted mb-1">To</label>
                <input type="date" name="date_to" value="<?=esc($date_to)?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-dark">
                    <i class="fa fa-filter me-1"></i>Filter
                </button>
            </div>
            <div class="col-auto">
                <a href="?pg=admin&tab=inventory" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-rotate-left me-1"></i>Reset
                </a>
            </div>
            <div class="col-auto ms-auto">
                <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                    <i class="fa fa-print me-1"></i>Print Report
                </button>
            </div>
        </form>
    </div>
</div>

<div id="printable-inventory" class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fa fa-boxes me-2"></i>Inventory Report</h5>
        <div class="small text-muted"><?=date("jS M Y",strtotime($date_from))?> — <?=date("jS M Y",strtotime($date_to))?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Barcode</th>
                    <th>Unit Price</th>
                    <th>Qty Received</th>
                    <th>Qty Sold</th>
                    <th>Current Stock</th>
                    <th>Total Net</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($inventory)):?>
                    <?php foreach($inventory as $row):?>
                    <tr>
                        <td><?=esc($row['description'])?></td>
                        <td class="text-muted"><?=esc($row['barcode'])?></td>
                        <td class="text-muted">$<?=number_format($row['amount'],2)?></td>
                        <td class="text-success fw-bold"><?=esc($row['qty_received'])?></td>
                        <td class="text-danger fw-bold"><?=esc($row['qty_sold'])?></td>
                        <td class="fw-bold"><?=esc($row['current_stock'])?></td>
                        <td class="fw-bold text-primary">$<?=number_format($row['total_net'],2)?></td>
                    </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No data for this period</td></tr>
                <?php endif;?>
            </tbody>
            <?php if(!empty($inventory)):?>
            <tfoot>
                <tr style="border-top: 2px solid #000;">
                    <td colspan="3" class="fw-bold text-end">TOTALS</td>
                    <td class="fw-bold text-success"><?=esc($totals['qty_received'])?></td>
                    <td class="fw-bold text-danger"><?=esc($totals['qty_sold'])?></td>
                    <td class="fw-bold"><?=esc($totals['current_stock'])?></td>
                    <td class="fw-bold text-primary" style="font-size:15px;">$<?=number_format($totals['total_net'],2)?></td>
                </tr>
            </tfoot>
            <?php endif;?>
        </table>
    </div>
</div>