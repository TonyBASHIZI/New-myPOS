<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fa fa-cash-register me-2"></i>Daily Cash Closing</h5>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label small text-muted">Date</label>
                <input type="date" class="form-control js-closing-date" value="<?=date('Y-m-d')?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Counted Cash</label>
                <input type="number" step="0.01" class="form-control js-counted-cash" placeholder="0.00">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Note (optional)</label>
                <input type="text" class="form-control js-closing-note" placeholder="e.g. Short due to change given">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-dark w-100" onclick="save_cash_closing()">
                    <i class="fa fa-check"></i> Close Day
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Closing History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th><th>Cashier</th><th>Expected Cash</th><th>Expected Mobile</th><th>Counted</th><th>Difference</th><th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($closings)):?>
                    <?php foreach($closings as $c):?>
                    <tr>
                        <td><?=date("jS M, Y",strtotime($c['closing_date']))?></td>
                        <td><?=esc($c['username'] ?: 'Unknown')?></td>
                        <td>$<?=number_format($c['expected_cash'],2)?></td>
                        <td>$<?=number_format($c['expected_mobile'],2)?></td>
                        <td>$<?=number_format($c['counted_cash'],2)?></td>
                        <td class="fw-bold" style="color: <?=$c['difference'] == 0 ? '#28a745' : ($c['difference'] < 0 ? '#dc3545' : '#f39c12')?>;">
                            <?=$c['difference'] > 0 ? '+' : ''?>$<?=number_format($c['difference'],2)?>
                        </td>
                        <td class="text-muted"><?=esc($c['note'])?></td>
                    </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No closings recorded yet</td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function save_cash_closing()
{
    var closing_date = document.querySelector(".js-closing-date").value;
    var counted_cash = document.querySelector(".js-counted-cash").value;
    var note = document.querySelector(".js-closing-note").value.trim();

    if(counted_cash == "" || parseFloat(counted_cash) < 0)
    {
        show_toast("error", "Invalid amount", "Enter the counted cash amount.");
        return;
    }

    show_confirm("Close the day with this counted amount? This cannot be edited afterward.", function(){

        var ajax = new XMLHttpRequest();
        ajax.addEventListener('readystatechange', function(){
            if(ajax.readyState == 4 && ajax.status == 200)
            {
                var obj = JSON.parse(ajax.responseText);
                if(obj.success)
                {
                    show_toast("success", "Day Closed", "Difference: $" + parseFloat(obj.difference).toFixed(2));
                    location.reload();
                }else{
                    show_toast("error", "Failed", obj.message || "Please try again.");
                }
            }
        });
        ajax.open('post', 'index.php?pg=ajax', true);
        ajax.send(JSON.stringify({
            data_type: "save_cash_closing",
            closing_date: closing_date,
            counted_cash: counted_cash,
            note: note
        }));
    });
}
</script>