<style>
  @media print {
    .no-print {
      display: none;
    }
  }

  .orders-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
}

.orders-card .card-header {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: #fff;
    padding: 18px 24px;
    border: none;
}

.orders-table {
    margin-bottom: 0;
}

.orders-table thead th {
    background-color: #f8f9fa;
    color: #6c757d;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    padding: 14px 16px;
    white-space: nowrap;
}

.orders-table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    font-size: 14px;
    border-bottom: 1px solid #f1f1f1;
}

.orders-table tbody tr:hover {
    background-color: #f8fbff;
}

.order-no-badge {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    font-weight: 700;
    color: #2c3e50;
    background: #eef2f7;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
}

.status-pill {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
}

.line-total {
    font-weight: 600;
    color: #2c3e50;
}

.order-total-cell {
    font-size: 15px;
    font-weight: 700;
    color: #27ae60;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    text-decoration: none;
    color: #34495e;
}

.user-chip .avatar-circle {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #dbe4ee;
    color: #34495e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #adb5bd;
}
</style>

<div class="table-responsive">

	<table class="table table-striped table-hover">
        <!-- ton tableau existant avec ses données -->
    </table>
</div> <br>
<ul class="nav nav-tabs">
  
  <li class="nav-item">
    <a class="nav-link <?=($section =='table') ? 'active':''?>" aria-current="page" href="index.php?pg=admin&tab=orders">
	    Table View
	</a>
	
  </li>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;flex-wrap:wrap;gap:10px;">

    <!-- Bouton export -->
   
                <div class="mb-3">
                    <button onclick="exportTableToExcel()" class="btn btn-success">
                        <i class="fa fa-file-excel"></i> Exporter en Excel
                    </button>

                    <button onclick="functionTablePDF()" class="btn btn-danger ms-2">
                        <i class="fa fa-file-pdf"></i> Exporter en PDF
                    </button>
                </div>

</div>

  
  <li class="nav-item">
    <!-- <a class="nav-link <?=($section =='graph') ? 'active':''?>" href="index.php?pg=admin&tab=sales&s=graph">
	    Graph View
	</a> -->
  </li>
  
</ul>
<br>



<div class="card orders-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="fa fa-clipboard-list me-2"></i>Orders</h5>
        </div>
        <span class="badge bg-light text-dark px-3 py-2">
            <?=count($orders)?> order<?=count($orders) != 1 ? 's' : ''?>
        </span>
    </div>

    <div class="p-3 border-bottom bg-light">
    <div class="row g-2 align-items-end">

        <div class="col-md-3">
            <label class="form-label mb-1">From</label>
            <input type="date" id="orderDateFrom" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label mb-1">To</label>
            <input type="date" id="orderDateTo" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label mb-1">Status</label>
            <select id="orderStatusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="button" onclick="filterOrders()" class="btn btn-primary">
                <i class="fa fa-filter"></i> Filter
            </button>

            <button type="button" onclick="resetOrderFilters()" class="btn btn-secondary">
                Reset
            </button>
        </div>

    </div>
</div>

    <div class="table-responsive">

        <div class="table-responsive">
    <table id="ordersTable" class="table orders-table mb-0">
            <thead>
                <tr>
                    <th>Order No</th><th>Order ID</th><th>Description</th><th>Qty</th><th>Price</th><th>Line Total</th><th>Order Total</th><th>Status</th><th>Created By</th><th>Approved By</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)):?>
                    <?php foreach ($orders as $order):?>

                        <?php
                            $creator_name = !empty($order['creator_name']) ? $order['creator_name'] : "Unknown";
                            $creator_link = !empty($order['created_by']) ? "index.php?pg=profile&id=".$order['created_by'] : "#";
                            $creator_initial = strtoupper(substr($creator_name, 0, 1));

                            $approver_name = !empty($order['approver_name']) ? $order['approver_name'] : null;
                            $approver_link = !empty($order['ref_user']) ? "index.php?pg=profile&id=".$order['ref_user'] : "#";
                            $approver_initial = $approver_name ? strtoupper(substr($approver_name, 0, 1)) : null;

                            $status = $order['status'];
                            $status_badge = "bg-secondary";
                            $status_icon = "fa-circle";
                            if($status == "Pending"){   $status_badge = "bg-warning text-dark"; $status_icon = "fa-clock"; }
                            if($status == "Approved"){  $status_badge = "bg-success"; $status_icon = "fa-check-circle"; }
                            if($status == "Cancelled"){ $status_badge = "bg-danger"; $status_icon = "fa-times-circle"; }

                            $item_count = count($order['items']);
                        ?>

                        <?php foreach ($order['items'] as $i => $item):?>
                        <tr>
                            <?php if($i == 0):?>
							<td rowspan="<?=$item_count?>">
							    <span class="order-no-badge" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#orderModal<?=$order['order_id']?>" title="Click to view order details">
							        <?=esc($order['order_no'])?>
							    </span>
							</td>
							<td rowspan="<?=$item_count?>">
							    <span class="badge bg-light text-dark border">#<?=esc($order['order_id'])?></span>
							</td>
							<?php endif;?>

                           
                            <td><?=esc($item['description'])?></td>
                            <td><span class="badge bg-light text-dark border"><?=esc($item['qty'])?></span></td>
                            <td class="text-muted">$<?=number_format($item['price'],2)?></td>
                            <td class="line-total">$<?=number_format($item['line_total'],2)?></td>

                            <?php if($i == 0):?>
                            <td rowspan="<?=$item_count?>" class="order-total-cell">
                                $<?=number_format($order['order_total'],2)?>
                            </td>
                            <td rowspan="<?=$item_count?>" class="order-status">
                                <span class="status-pill <?=$status_badge?>">
                                    <i class="fa <?=$status_icon?> me-1"></i><?=esc($status)?>
                                </span>
                            </td>
                            <td rowspan="<?=$item_count?>">
                                <a href="<?=$creator_link?>" class="user-chip">
                                    <span class="avatar-circle"><?=$creator_initial?></span>
                                    <?=esc($creator_name)?>
                                </a>
                            </td>
                            <td rowspan="<?=$item_count?>">
                                <?php if($approver_name):?>
                                    <a href="<?=$approver_link?>" class="user-chip">
                                        <span class="avatar-circle"><?=$approver_initial?></span>
                                        <?=esc($approver_name)?>
                                    </a>
                                <?php else:?>
                                    <span class="text-muted small">— pending —</span>
                                <?php endif;?>
                            </td>
                            <td rowspan="<?=$item_count?>" class="text-muted" data-date="<?=date("Y-m-d",strtotime($order['created_at']))?>">
							    <?=date("jS M, Y",strtotime($order['created_at']))?>
							</td>
                            <?php endif;?>
                        </tr>
                        <?php endforeach;?>

                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="fa fa-inbox fa-2x mb-2"></i>
                                <div>No orders found</div>
                            </div>
                        </td>
                    </tr>
                <?php endif;?>
            </tbody>
        </table>

		        <?php foreach($orders as $order):?>
		<div class="modal fade" id="orderModal<?=$order['order_id']?>" tabindex="-1">
		  <div class="modal-dialog modal-lg modal-dialog-centered">
		    <div class="modal-content" style="border-radius: 12px; border: none;">

		      <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50, #34495e); color: #fff; border-radius: 12px 12px 0 0;">
		        <h5 class="modal-title">
		            <i class="fa fa-receipt me-2"></i>Order <?=esc($order['order_no'])?>
		        </h5>
		        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
		      </div>

		      <div class="modal-body">

		        <div class="row mb-3 g-3">
		            <div class="col-6">
		                <div class="small text-muted">Status</div>
		                <?php
		                    $status = $order['status'];
		                    $status_badge = "bg-secondary";
		                    if($status == "Pending")   $status_badge = "bg-warning text-dark";
		                    if($status == "Approved")  $status_badge = "bg-success";
		                    if($status == "Cancelled") $status_badge = "bg-danger";
		                ?>
		                <span class="badge <?=$status_badge?>"><?=esc($status)?></span>
		            </div>
		            <div class="col-6">
		                <div class="small text-muted">Date</div>
		                <div><?=date("jS M, Y",strtotime($order['created_at']))?></div>
		            </div>
		            <div class="col-6">
		                <div class="small text-muted">Created By</div>
		                <div><?=esc($order['creator_name'] ?: 'Unknown')?></div>
		            </div>
		            <div class="col-6">
		                <div class="small text-muted">Approved By</div>
		                <div><?=esc($order['approver_name'] ?: '— pending —')?></div>
		            </div>
		        </div>

		        <table class="table table-sm table-striped mb-3">
		            <thead>
		                <tr><th>Product</th><th>Qty</th><th>Price</th><th>Line Total</th></tr>
		            </thead>
		            <tbody>
		                <?php foreach($order['items'] as $item):?>
		                <tr>
		                    <td><?=esc($item['description'])?></td>
		                    <td><?=esc($item['qty'])?></td>
		                    <td>$<?=number_format($item['price'],2)?></td>
		                    <td>$<?=number_format($item['line_total'],2)?></td>
		                </tr>
		                <?php endforeach;?>
		            </tbody>
		        </table>

		        <div class="text-end" style="font-size: 18px; font-weight: 700; color: #27ae60;">
		            Total: $<?=number_format($order['order_total'],2)?>
		        </div>

		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		      </div>

		    </div>
		  </div>
		</div>
		<?php endforeach;?>
       
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
 

<script>

// export excel
function exportTableToExcel() {
    var table = document.getElementById("orders-table");
    var workbook = XLSX.utils.table_to_book(table, {sheet:"Feuille1"});
    XLSX.writeFile(workbook, "Orders.xlsx");
}

function functionTablePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4'); // landscape, since you have many columns

    // Title
    doc.setFontSize(14);
    doc.text("Orders Report", 40, 30);
    doc.setFontSize(9);
    doc.text("Generated: " + new Date().toLocaleString(), 40, 45);

    doc.autoTable({
        html: '#orders-table table',   // point directly at the table inside your div
        startY: 55,
        theme: 'striped',
        headStyles: {
            fillColor: [40, 40, 40],
            textColor: 255,
            fontSize: 8
        },
        bodyStyles: {
            fontSize: 8
        },
        // Drop the trailing empty <th></th> column if it has no real data
        columnStyles: {
            10: { cellWidth: 0 } // adjust/remove if that last column is actually used
        },
        margin: { left: 20, right: 20 }
    });

    doc.save('Orders_export_' + Date.now() + '.pdf');
}

</script>
<script>

function filterOrders()
{
    let dateFrom = document.getElementById("orderDateFrom").value;
    let dateTo = document.getElementById("orderDateTo").value;
    let status = document.getElementById("orderStatusFilter").value.toLowerCase();

    let rows = document.querySelectorAll("#ordersTable tbody tr");

    rows.forEach(function(row)
    {
        let show = true;

        // Date
        let dateCell = row.querySelector(".order-date");

        if(dateCell)
        {
            let rowDate = dateCell.getAttribute("data-date");

            if(dateFrom && rowDate < dateFrom)
            {
                show = false;
            }

            if(dateTo && rowDate > dateTo)
            {
                show = false;
            }
        }

        // Status
        let statusCell = row.querySelector(".order-status");

        if(status && statusCell)
        {
            let rowStatus = statusCell.innerText.trim().toLowerCase();

            if(rowStatus !== status)
            {
                show = false;
            }
        }

        row.style.display = show ? "" : "none";
    });
}


function resetOrderFilters()
{
    document.getElementById("orderDateFrom").value = "";
    document.getElementById("orderDateTo").value = "";
    document.getElementById("orderStatusFilter").value = "";

    document.querySelectorAll("#ordersTable tbody tr").forEach(function(row)
    {
        row.style.display = "";
    });
}

</script>




