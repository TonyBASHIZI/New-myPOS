<style>
    @keyframes appear{
    0%{opacity: 0;transform: translateY(-100px);}
    100%{opacity: 1;transform: translateY(0px);}
}
@keyframes disappear {
    0%{opacity: 1; transform: translateY(0px);}
    100%{opacity: 0; transform: translateY(-20px);}
}
.hide{
    display: none;
}
</style>

<!--toast notification-->
<div class="js-toast hide" style="animation: appear .4s ease; position: fixed; top: 20px; right: 20px; z-index: 1060; min-width: 320px; max-width: 400px;">
    <div class="js-toast-box shadow-lg rounded p-3 d-flex align-items-start" style="background-color: white; border-left: 6px solid #28a745;">
        <div class="js-toast-icon me-3" style="font-size: 28px; color: #28a745;">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="flex-grow-1">
            <div class="js-toast-title fw-bold" style="font-size: 15px;"></div>
            <div class="js-toast-message text-muted" style="font-size: 13px;"></div>
        </div>
        <button role="close-button" onclick="hide_toast()" class="btn-close ms-2"></button>
    </div>
</div>
<!--end toast notification-->

<!--confirm modal-->
<div role="close-button" onclick="hide_modal(event,'confirm')" class="js-confirm-modal hide" style="animation: appear .3s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 1055;">
    <div style="width:420px;min-height:150px;background-color:white;padding:20px;margin:auto;margin-top:150px;border-radius:6px;">
        <div class="js-confirm-message" style="font-size:16px;margin-bottom:20px;"></div>
        <div class="text-end">
            <button role="close-button" onclick="hide_modal(event,'confirm')" class="btn btn-secondary me-2">Cancel</button>
            <button onclick="confirm_yes()" class="btn btn-warning">Confirm</button>
        </div>
    </div>
</div>
<!--end confirm modal-->

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
<script>
    var TOAST_TIMEOUT = null;

function show_toast(type, title, message)
{
    var toast = document.querySelector(".js-toast");
    var box   = toast.querySelector(".js-toast-box");
    var icon  = toast.querySelector(".js-toast-icon");

    toast.querySelector(".js-toast-title").innerHTML = title;
    toast.querySelector(".js-toast-message").innerHTML = message;

    if(type == "error")
    {
        box.style.borderLeftColor = "#dc3545";
        icon.style.color = "#dc3545";
        icon.innerHTML = '<i class="fa fa-times-circle"></i>';
    }else{
        box.style.borderLeftColor = "#28a745";
        icon.style.color = "#28a745";
        icon.innerHTML = '<i class="fa fa-check-circle"></i>';
    }

    toast.classList.remove("hide");
    toast.style.animation = "appear .4s ease";

    clearTimeout(TOAST_TIMEOUT);
    TOAST_TIMEOUT = setTimeout(hide_toast, 3500);
}

function hide_toast()
{
    var toast = document.querySelector(".js-toast");
    toast.style.animation = "disappear .4s ease";
    setTimeout(function(){
        toast.classList.add("hide");
    }, 350);
}

var CONFIRM_CALLBACK = null;

        function show_confirm(message, callback)
        {
            CONFIRM_CALLBACK = callback;
            var mydiv = document.querySelector(".js-confirm-modal");
            mydiv.querySelector(".js-confirm-message").innerHTML = message;
            mydiv.classList.remove("hide");
        }

        function confirm_yes()
        {
            var mydiv = document.querySelector(".js-confirm-modal");
            mydiv.classList.add("hide");

            if(typeof CONFIRM_CALLBACK == "function")
            {
                CONFIRM_CALLBACK();
            }
            CONFIRM_CALLBACK = null;
        }

        function hide_modal(e, modal)
        {
            if(e == true || e.target.getAttribute("role") == "close-button")
            {
                if(modal == "confirm"){
                    document.querySelector(".js-confirm-modal").classList.add("hide");
                    CONFIRM_CALLBACK = null;
                }
            }
        }
</script>