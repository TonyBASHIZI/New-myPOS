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


<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fa fa-truck-loading me-2"></i>Receive Stock</h5>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small text-muted">Product</label>
                <select class="form-control js-receive-product">
                    <option value="">-- Select product --</option>
                    <?php foreach($products as $p):?>
                        <option value="<?=$p['id']?>"><?=esc($p['description'])?> (<?=esc($p['barcode'])?>)</option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Qty</label>
                <input type="number" step="0.01" class="form-control js-receive-qty" placeholder="0">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Note (optional)</label>
                <input type="text" class="form-control js-receive-note" placeholder="e.g. Supplier delivery">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-success w-100" onclick="receive_stock()">
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fa fa-history me-2"></i>Stock Received History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
          
                <tr>
                    <th>Product</th>
                    <th>Barcode</th>
                    <th>Qty</th>
                    <th>Note</th>
                    <th>Received By</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                
            <?php foreach ($stock_received as $all):?>
               <tr>
                    <td><?=esc($all['description'])?></td>
                    <td><?=esc($all['barcode'])?></td>
                    <td><?=esc($all['qty_received'])?></td>
                    <td><?=esc($all['note'])?></td>
                    <td><?=esc($all['username'])?></td>
                    <td><?=esc($all['received_at'])?></td>
                    <td> 
                    
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="open_edit_stock('<?=$all['id']?>', <?=htmlspecialchars(json_encode($all['description'] ?: ''), ENT_QUOTES)?>, '<?=$all['qty_received']?>', <?=htmlspecialchars(json_encode($all['note'] ?: ''), ENT_QUOTES)?>)">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="delete_stock_received('<?=$all['id']?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                    
                    </td>
                </tr>

                 <?php endforeach;?>
           
        </table>
    </div>
</div>

<div class="modal fade" id="editStockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Stock Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" class="js-edit-id">

        <label class="form-label small text-muted">Product</label>
        <input type="text" class="form-control mb-3 js-edit-product-label" disabled>

        <label class="form-label small text-muted">Quantity</label>
        <input type="number" step="0.01" class="form-control mb-3 js-edit-qty">

        <label class="form-label small text-muted">Note</label>
        <input type="text" class="form-control js-edit-note">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="save_stock_edit()">
            <i class="fa fa-save me-1"></i>Save Changes
        </button>
      </div>
    </div>
  </div>
</div>

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
<div class="modal fade" id="editStockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">
      <div class="modal-header">
        <h5 class="modal-title">Edit Stock Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" class="js-edit-id">
        <label class="form-label small text-muted">Quantity</label>
        <input type="number" step="0.01" class="form-control mb-3 js-edit-qty">
        <label class="form-label small text-muted">Note</label>
        <input type="text" class="form-control js-edit-note">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="save_stock_edit()">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>

function load_stock_received()
{
    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            render_stock_received(obj.data);
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({ data_type: "list_stock_received" }));
}

function render_stock_received(rows)
{
    var tbody = document.querySelector(".js-stock-received-list");

    if(!rows || rows.length == 0)
    {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No stock received yet</td></tr>';
        return;
    }

    var html = "";
    rows.forEach(function(row){
        html += `
            <tr>
                <td>${row.description || '-'}</td>
                <td class="text-muted">${row.barcode || '-'}</td>
                <td class="fw-bold">${row.qty_received}</td>
                <td>${row.note || '-'}</td>
                <td>${row.username || 'Unknown'}</td>
                <td class="text-muted">${row.received_at}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="open_edit_stock('${row.id}','${row.qty_received}', ${JSON.stringify(row.note || '')})">
                        <i class="fa fa-pen"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="delete_stock_received('${row.id}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function open_edit_stock(id, product_label, qty, note)
{
    document.querySelector(".js-edit-id").value = id;
    document.querySelector(".js-edit-product-label").value = product_label;
    document.querySelector(".js-edit-qty").value = qty;
    document.querySelector(".js-edit-note").value = note;

    var modal = new bootstrap.Modal(document.getElementById('editStockModal'));
    modal.show();
}

function save_stock_edit()
{
    var id   = document.querySelector(".js-edit-id").value;
    var qty  = document.querySelector(".js-edit-qty").value;
    var note = document.querySelector(".js-edit-note").value.trim();

    if(qty == "" || parseFloat(qty) <= 0)
    {
        show_toast("error", "Invalid quantity", "Enter a quantity greater than 0.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                show_toast("success", "Updated", obj.message);
                bootstrap.Modal.getInstance(document.getElementById('editStockModal')).hide();
                location.reload(); // simplest way to refresh both history + product qty display
            }else{
                show_toast("error", "Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "update_stock_received",
        id: id,
        qty: qty,
        note: note
    }));
}

function delete_stock_received(id)
{
    show_confirm("Delete this stock entry? This will also adjust product stock accordingly.", function(){

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
        ajax.send(JSON.stringify({ data_type: "delete_stock_received", id: id }));
    });
}
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

function receive_stock()
{
    var product_id = document.querySelector(".js-receive-product").value;
    var qty        = document.querySelector(".js-receive-qty").value;
    var note       = document.querySelector(".js-receive-note").value.trim();
    if(product_id == "")
    {
        show_toast("error", "Missing product", "Please select a product.");
        return;
    }
    if(qty == "" || parseFloat(qty) <= 0)
    {
        show_toast("error", "Invalid quantity", "Enter a quantity greater than 0.");
        return;
    }
    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                show_toast("success", "Stock Received", obj.message);
                location.reload();
            }else{
                show_toast("error", "Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "receive_stock",
        product_id: product_id,
        qty: qty,
        note: note
    }));
}

function save_stock_edit()
{
    var id   = document.querySelector(".js-edit-id").value;
    var qty  = document.querySelector(".js-edit-qty").value;
    var note = document.querySelector(".js-edit-note").value.trim();
    if(qty == "" || parseFloat(qty) <= 0)
    {
        show_toast("error", "Invalid quantity", "Enter a quantity greater than 0.");
        return;
    }
    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                show_toast("success", "Updated", obj.message);
                bootstrap.Modal.getInstance(document.getElementById('editStockModal')).hide();
                location.reload();
            }else{
                show_toast("error", "Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "update_stock_received",
        id: id,
        qty: qty,
        note: note
    }));
}

function delete_stock_received(id)
{
    show_confirm("Delete this stock entry? This will also adjust product stock accordingly.", function(){
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
        ajax.send(JSON.stringify({ data_type: "delete_stock_received", id: id }));
    });
}
</script>

<script>
    $(document).ready(function(){
    $('.js-receive-product').select2({
        placeholder: "-- Select product --",
        width: '100%'
    });
});
</script>