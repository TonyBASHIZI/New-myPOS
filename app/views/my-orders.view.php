<?php 


require views_path('partials/header');


?>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="border rounded p-3">
            <div class="small text-muted">Pending</div>
            <h3 class="mb-0"><?=$myPendingOrders?></h3>
            <div class="small text-muted">$<?=number_format($myPendingAmount,2)?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3">
            <div class="small text-muted">Approved</div>
            <h3 class="mb-0"><?=$myApprovedOrders?></h3>
            <div class="small text-muted">$<?=number_format($myApprovedAmount,2)?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3">
            <div class="small text-muted">Today</div>
            <h3 class="mb-0"><?=$myTodayOrders?></h3>
            <div class="small text-muted">$<?=number_format($myTodayAmount,2)?></div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Order No</th><th>Barcode</th><th>Description</th><th>Qty</th><th>Price</th><th>Line Total</th><th>Order Total</th><th>Status</th><th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($orders)):?>
                <?php foreach($orders as $order):
                    $status = $order['status'];
                    $status_badge = "bg-secondary";
                    if($status == "Pending")   $status_badge = "bg-warning text-dark";
                    if($status == "Approved")  $status_badge = "bg-success";
                    if($status == "Cancelled") $status_badge = "bg-danger";
                    $item_count = count($order['items']);
                ?>
                    <?php foreach($order['items'] as $i => $item):?>
                    <tr>
                        <?php if($i == 0):?>
                        <td rowspan="<?=$item_count?>"><?=esc($order['order_no'])?>/<?=esc($order['order_id'])?></td>
                        <?php endif;?>
                        <td><?=esc($item['barcode'])?></td>
                        <td><?=esc($item['description'])?></td>
                        <td><?=esc($item['qty'])?></td>
                        <td>$<?=number_format($item['price'],2)?></td>
                        <td>$<?=number_format($item['line_total'],2)?></td>
                        <?php if($i == 0):?>
                        <td rowspan="<?=$item_count?>">$<?=number_format($order['order_total'],2)?></td>
                        <td rowspan="<?=$item_count?>"><span class="badge <?=$status_badge?>"><?=esc($status)?></span></td>
                        <td rowspan="<?=$item_count?>"><?=date("jS M, Y",strtotime($order['created_at']))?></td>
                        <?php endif;?>
                    </tr>
                    <?php endforeach;?>
                <?php endforeach;?>
            <?php else:?>
                <tr><td colspan="9" class="text-center text-muted py-4">You haven't placed any orders yet</td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>


<?php require views_path('partials/footer');?>