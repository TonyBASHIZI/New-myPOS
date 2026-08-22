html
<style>
.order-stat-card .stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f1f1;
}
.order-stat-card .stat-row:last-child {
    border-bottom: none;
}
.order-stat-card .stat-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6c757d;
}
.order-stat-card .stat-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}
.order-stat-card .stat-values {
    text-align: right;
}
.order-stat-card .stat-count {
    font-weight: 700;
    font-size: 15px;
    color: #2c3e50;
}
.order-stat-card .stat-amount {
    font-size: 11px;
    color: #adb5bd;
}
</style>
<div class="row justify-content-center">
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-user" style="font-size: 30px"></i>
		<h4>Total Users</h4>
		<h2><?=$total_users?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-tag" style="font-size: 30px"></i>
		<h4>All Products</h4>
		<h2><?=$total_products?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Amount Products</h4>
		<h2>$<?=$totalStock?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Sales AD-<?=$total_sales_shop1?>$ MAU-<?=$total_sales_shop2?></h4>
		<h2>$<?=$total_sales?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Amount Products Shop 1 ADELARD</h4>
		<h2>$<?=$totalStockShop1?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Amount Products Shop 2 MAURICE</h4>
		<h2>$<?=$totalStockShop2?></h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2" style="color:green;">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Amount Sales today</h4>
		<h2><?=$total_sales_todays?>$</h1>
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-tag" style="font-size: 30px"></i>
		<h4>Items sales</h4>
		<h2><?=$total_sales_today?></h1>
		
	</div>
	<div class="col-md-3 border rounded p-4 my-2 order-stat-card">
    <i class="fa fa-shopping-cart" style="font-size: 30px;"></i>
    <h4>Orders</h4>

    <div class="stat-row">
        <div class="stat-label">
            <span class="stat-dot" style="background:#f39c12;"></span>Pending
        </div>
        <div class="stat-values">
            <div class="stat-count"><?=$totalPendingOrders?></div>
            <div class="stat-amount">$<?=number_format($amountPendingOrders,2)?></div>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-label">
            <span class="stat-dot" style="background:#28a745;"></span>Approved
        </div>
        <div class="stat-values">
            <div class="stat-count"><?=$totalApprovedOrders?></div>
            <div class="stat-amount">$<?=number_format($amountApprovedOrders,2)?></div>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-label">
            <span class="stat-dot" style="background:#3498db;"></span>Today
        </div>
        <div class="stat-values">
            <div class="stat-count"><?=$totalTodayOrders?></div>
            <div class="stat-amount">$<?=number_format($amountTodayOrders,2)?></div>
        </div>
    </div>
</div>
	<div class="col-md-3 border rounded p-4 my-2" style="color:red;">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Balance sales ADELARD</h4>
		<h2><?=$total_balance_shop13?>$</h1>
		
	</div>
	<div class="col-md-3 border rounded p-4 my-2" style="color:red;">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Balance sales MAURICE</h4>
		<h2><?=$total_balance_shop12?>$</h1>
		
	</div>
	<div class="col-md-3 border rounded p-4 my-2">
		<i class="fa fa-money-bill-wave" style="font-size: 30px"></i>
		<h4>Last arrivage</h4>
		<p>ARRIV-BOSCO-2103-26 Total <B><?=$totalLastlot?>$</B> </p>
			
	</div>
	
</div>