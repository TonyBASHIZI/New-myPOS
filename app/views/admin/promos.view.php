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
<div role="close-button" onclick="hide_modal(event,'confirm')" class="js-confirm-modal hide" style="animation: appear .3s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 1055;">
    <div style="width:420px;min-height:150px;background-color:white;padding:20px;margin:auto;margin-top:150px;border-radius:6px;">
        <div class="js-confirm-message" style="font-size:16px;margin-bottom:20px;"></div>
        <div class="text-end">
            <button role="close-button" onclick="hide_modal(event,'confirm')" class="btn btn-secondary me-2">Cancel</button>
            <button onclick="confirm_yes()" class="btn btn-warning">Confirm</button>
        </div>
    </div>
</div>
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
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fa fa-percent me-2"></i>Create Promo</h5></div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small text-muted">Product</label>
                <select class="form-control js-promo-product">
                    <option value="">-- Select product --</option>
                    <?php foreach($products as $p):?>
                        <option value="<?=$p['id']?>" data-price="<?=$p['amount']?>"><?=esc($p['description'])?> ($<?=number_format($p['amount'],2)?>)</option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Promo Price</label>
                <input type="number" step="0.01" class="form-control js-promo-price" placeholder="0.00">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Start Date</label>
                <input type="date" class="form-control js-promo-start" value="<?=date('Y-m-d')?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">End Date</label>
                <input type="date" class="form-control js-promo-end">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-dark w-100" onclick="create_promo()">
                    <i class="fa fa-plus"></i> Add Promo
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Active & Past Promos</h5></div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr><th>Product</th><th>Regular</th><th>Promo</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if(!empty($promos)):?>
                    <?php foreach($promos as $promo):
                        $today = date('Y-m-d');
                        $is_live = $promo['active'] && $promo['start_date'] <= $today && $promo['end_date'] >= $today;
                    ?>
                    <tr>
                        <td><?=esc($promo['description'])?></td>
                        <td class="text-muted">$<?=number_format($promo['regular_price'],2)?></td>
                        <td class="fw-bold text-success">$<?=number_format($promo['promo_price'],2)?></td>
                        <td><?=date("d M Y",strtotime($promo['start_date']))?></td>
                        <td><?=date("d M Y",strtotime($promo['end_date']))?></td>
                        <td>
                            <?php if($is_live):?>
                                <span class="badge bg-success">Live</span>
                            <?php elseif($promo['end_date'] < $today):?>
                                <span class="badge bg-secondary">Expired</span>
                            <?php else:?>
                                <span class="badge bg-warning text-dark">Scheduled</span>
                            <?php endif;?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="delete_promo('<?=$promo['id']?>')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No promos yet</td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<script>

var TOAST_TIMEOUT = null;
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

function create_promo()
{
    var product_id = document.querySelector(".js-promo-product").value;
    var promo_price = document.querySelector(".js-promo-price").value;
    var start_date = document.querySelector(".js-promo-start").value;
    var end_date = document.querySelector(".js-promo-end").value;

    if(!product_id || !promo_price || !start_date || !end_date)
    {
        show_toast("error", "Missing info", "Fill in all fields.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                show_toast("success", "Promo Created", obj.message);
                location.reload();
            }else{
                show_toast("error", "Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "create_promo",
        product_id: product_id,
        promo_price: promo_price,
        start_date: start_date,
        end_date: end_date
    }));
}

function delete_promo(id)
{
    show_confirm("Delete this promo? This cannot be undone.", function(){

        var ajax = new XMLHttpRequest();
        ajax.addEventListener('readystatechange', function(){
            if(ajax.readyState == 4 && ajax.status == 200)
            {
                var obj = JSON.parse(ajax.responseText);
                if(obj.success)
                {
                    show_toast("success", "Deleted", obj.message);
                    location.reload();
                }else{
                    show_toast("error", "Failed", obj.message);
                }
            }
        });
        ajax.open('post', 'index.php?pg=ajax', true);
        ajax.send(JSON.stringify({ data_type: "delete_promo", id: id }));
    });
}


</script>