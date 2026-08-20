<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body py-3">
        <form method="get" class="row g-2 align-items-end">
            <input type="hidden" name="pg" value="home">
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
                <button type="submit" class="btn btn-sm btn-dark">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Product</th>
                <th>Barcode</th>
                <th>Qty Received</th>
                <th>Qty Sold</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($inventory)):?>
                <?php foreach($inventory as $row):?>
                <tr>
                    <td><?=esc($row['description'])?></td>
                    <td class="text-muted"><?=esc($row['barcode'])?></td>
                    <td class="text-success"><?=esc($row['qty_received'])?></td>
                    <td class="text-danger"><?=esc($row['qty_sold'])?></td>
                    <td class="fw-bold"><?=esc($row['current_stock'])?></td>
                </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr><td colspan="5" class="text-center text-muted">No data</td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>