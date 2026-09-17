<style>
.notif-bell-wrapper {
    position: relative;
    margin-right: 10px;
}
.notif-bell-btn {
    background: transparent;
    border: none;
    font-size: 20px;
    color: #495057;
    position: relative;
    padding: 8px 10px;
    cursor: pointer;
}
.notif-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #dc3545;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    min-width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
}
.notif-dropdown {
    position: absolute;
    top: 42px;
    right: 0;
    width: 280px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    z-index: 1050;
    overflow: hidden;
}
.notif-dropdown-header {
    padding: 12px 16px;
    font-weight: 700;
    font-size: 14px;
    border-bottom: 1px solid #f1f1f1;
    background: #f8f9fa;
}
.notif-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f8f9fa;
    text-decoration: none;
    color: #495057;
}
.notif-item:hover {
    background: #f8f9fa;
    color: #495057;
}
.notif-item:last-child {
    border-bottom: none;
}
.notif-item .label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}
.notif-item .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}
.notif-item .count {
    font-weight: 700;
    font-size: 14px;
}
</style>
<?php
  
   $db = new Database();

		$pending_orders = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE status = 'Pending'");
		$totalPendingOrders = is_array($pending_orders) ? $pending_orders[0]['total'] : 0;
		$amountPendingOrders = is_array($pending_orders) ? $pending_orders[0]['amount'] : 0;

        // NEW — products expiring within 30 days
        $expiring_soon = $db->query("
            SELECT COUNT(*) as total FROM products
            WHERE expire_date IS NOT NULL
            AND expire_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ");
        $totalExpiringSoon = is_array($expiring_soon) ? $expiring_soon[0]['total'] : 0;

		$approved_orders = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE status = 'Approved'");
		$totalApprovedOrders = is_array($approved_orders) ? $approved_orders[0]['total'] : 0;
		$amountApprovedOrders = is_array($approved_orders) ? $approved_orders[0]['amount'] : 0;

		$today_orders = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE DATE(created_at) = CURDATE()");
		$totalTodayOrders = is_array($today_orders) ? $today_orders[0]['total'] : 0;
		$amountTodayOrders = is_array($today_orders) ? $today_orders[0]['amount'] : 0;


        // my_orders
        $db = new Database();
    $my_id = auth("id");

    // Stat cards, scoped to this user only
    $pending = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE created_by = :uid AND status = 'Pending'", ['uid' => $my_id]);
    $myPendingOrders = is_array($pending) ? $pending[0]['total'] : 0;
    $myPendingAmount = is_array($pending) ? $pending[0]['amount'] : 0;

    $approved = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE created_by = :uid AND status = 'Approved'", ['uid' => $my_id]);
    $myApprovedOrders = is_array($approved) ? $approved[0]['total'] : 0;
    $myApprovedAmount = is_array($approved) ? $approved[0]['amount'] : 0;

    $today = $db->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as amount FROM orders WHERE created_by = :uid AND DATE(created_at) = CURDATE()", ['uid' => $my_id]);
    $myTodayOrders = is_array($today) ? $today[0]['total'] : 0;
    $myTodayAmount = is_array($today) ? $today[0]['amount'] : 0;

    // Orders list, scoped to this user only
        $query = "
        SELECT 
            o.id AS order_id,
            o.order_no,
            o.customer_name,
            o.customer_phone,
            o.status,
            o.total AS order_total,
            o.created_by,
            o.ref_user,
            o.created_at,
            creator.username AS creator_name,
            approver.username AS approver_name,
            oi.id AS order_item_id,
            oi.qty,
            oi.price,
            oi.total AS line_total,
            p.id AS product_id,
            p.description,
            p.barcode,
            p.image
        FROM orders o
        INNER JOIN order_items oi ON oi.order_id = o.id
        INNER JOIN products p ON p.id = oi.product_id
        LEFT JOIN users creator ON creator.id = o.created_by
        LEFT JOIN users approver ON approver.id = o.ref_user
        WHERE o.created_by = :uid
        ORDER BY o.created_at DESC, o.id, oi.id
        ";
        $rows = $db->query($query, ['uid' => $my_id]);

        $orders = [];
        if(is_array($rows))
        {
            foreach($rows as $row)
            {
                $oid = $row['order_id'];
                if(!isset($orders[$oid]))
                {
                    $orders[$oid] = [
                        'order_id'       => $row['order_id'],
                        'order_no'       => $row['order_no'],
                        'customer_name'  => $row['customer_name'],
                        'customer_phone' => $row['customer_phone'],
                        'status'         => $row['status'],
                        'order_total'    => $row['order_total'],
                        'created_by'     => $row['created_by'],
                        'creator_name'   => $row['creator_name'],
                        'ref_user'       => $row['ref_user'],
                        'approver_name'  => $row['approver_name'],
                        'created_at'     => $row['created_at'],
                        'items'          => [],
                    ];
                }
                $orders[$oid]['items'][] = [
                    'order_item_id' => $row['order_item_id'],
                    'product_id'    => $row['product_id'],
                    'description'   => $row['description'],
                    'barcode'       => $row['barcode'],
                    'image'         => $row['image'],
                    'qty'           => $row['qty'],
                    'price'         => $row['price'],
                    'line_total'    => $row['line_total'],
                ];
            }
        }
        $orders = array_values($orders);

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light" style="min-width:350px">
	  <div class="container-fluid">
	  	<h1><img src="../public/assets/imgpresentation/logoPBcars.jpeg" width="70px"></i></h1>
	    <a class="navbar-brand" href="index.php?pg=home"><?=esc(APP_NAME)?></a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	        <li class="nav-item" class="nav-item"
                style="border-radius:8px;"
                onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                onmouseout="this.style.backgroundColor=''; this.style.color=''; ">
	          <a class="nav-link active" aria-current="page" href="index.php?pg=home">Point of sale</a>
	        </li>
            <?php if(Auth::access('user')):?>
            <li class="nav-item" class="nav-item"
                style="border-radius:8px;"
                onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                onmouseout="this.style.backgroundColor=''; this.style.color='';">
                <a class="nav-link" href="index.php?pg=my-orders">My Orders</a>
            </li>
            <?php endif;?>
	        

			<?php if(Auth::access('cashier')): ?>
            <!-- Ceci sera visible par Admin, Supervisor et Cashier -->
             <li class="nav-item" class="nav-item"
                        style="border-radius:8px;"
                        onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor=''; this.style.color=''; ">
                      <a class="nav-link active" aria-current="page" href="index.php?pg=admin&tab=orders">Orders</a>
                    </li>
            <li class="nav-item" class="nav-item"
            style="border-radius:8px;"
            onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
            onmouseout="this.style.backgroundColor=''; this.style.color='';">
                <a class="nav-link" href="index.php?pg=admin&tab=sales">Sales</a>
            </li>
        <?php endif; ?>

        <?php if(Auth::access('cashier')): ?>
            <!-- Ceci sera visible par Admin, Supervisor et Cashier -->
            <li class="nav-item" class="nav-item"
            style="border-radius:8px;"
            onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
            onmouseout="this.style.backgroundColor=''; this.style.color='';">
                <a class="nav-link" href="index.php?pg=admin&tab=voirboss">Voir boss</a>
            </li>
        <?php endif; ?>

        <?php if(Auth::access('cashier')): ?>
            <!-- Ceci sera visible par Admin, Supervisor et Cashier -->
            <li class="nav-item" class="nav-item"
            style="border-radius:8px;"
            onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
            onmouseout="this.style.backgroundColor=''; this.style.color='';">
                <a class="nav-link" href="index.php?pg=admin&tab=depense">Expenses</a>
            </li>
        <?php endif; ?>

	        
	        <?php if(Auth::access('supervisor')):?>
		        <li class="nav-item" class="nav-item"
                style="border-radius:8px;"
                onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                onmouseout="this.style.backgroundColor=''; this.style.color='';">
            		          <a class="nav-link" href="index.php?pg=admin">Admin</a>
        		</li>
		    <?php endif;?>

		    <?php if(!Auth::logged_in()):?>
		        <li class="nav-item">
		          <a class="nav-link" href="index.php?pg=login">Login</a>
		        </li>
	        <?php else:?>

		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            Hi, <?=auth('username')?> (<?=Auth::get('role')?>)
		          </a>
		          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		            
		            <li><a class="dropdown-item" href="index.php?pg=profile">Profile</a></li>
		            <li><a class="dropdown-item" href="index.php?pg=edit-user&id=<?=Auth::get('id')?>">Profile-parametre</a></li>
		            <li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item" href="index.php?pg=logout">Logout</a></li>
		          </ul>
		        </li>
	    	 <?php endif;?>
	      </ul>
	      <form class="d-flex align-items-center">

    <div class="notif-bell-wrapper">
    <button type="button" class="notif-bell-btn" onclick="toggle_notif_dropdown(event)">
        <i class="fa fa-bell"></i>
        <?php $notif_total = $totalPendingOrders + $totalExpiringSoon; ?>
        <?php if($notif_total > 0):?>
        <span class="notif-badge"><?=$notif_total?></span>
        <?php endif;?>
    </button>

    <div class="notif-dropdown" id="notifDropdown" style="display:none;">
        <div class="notif-dropdown-header">Orders Overview</div>

        <a href="index.php?pg=admin&tab=orders" class="notif-item">
            <div class="label">
                <span class="dot" style="background:#f39c12;"></span>Pending
            </div>
            <div class="count"><?=$totalPendingOrders?></div>
        </a>

        <a href="index.php?pg=admin&tab=orders" class="notif-item">
            <div class="label">
                <span class="dot" style="background:#28a745;"></span>Approved
            </div>
            <div class="count"><?=$totalApprovedOrders?></div>
        </a>

        <a href="index.php?pg=admin&tab=orders" class="notif-item">
            <div class="label">
                <span class="dot" style="background:#3498db;"></span>Today
            </div>
            <div class="count"><?=$totalTodayOrders?></div>
        </a>

        <div class="notif-dropdown-header" style="border-top:1px solid #eee;">Products</div>

        <a href="index.php?pg=admin&tab=expiring-products" class="notif-item">
            <div class="label">
                <span class="dot" style="background:#B33A3A;"></span>Expiring Soon
            </div>
            <div class="count"><?=$totalExpiringSoon?></div>
        </a>

    </div>
</div>

    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-success" type="submit">Search</button>
</form>
	     
	</nav>
	<script>
		function toggle_notif_dropdown(e)
			{
			    e.stopPropagation();
			    var dropdown = document.getElementById('notifDropdown');
			    dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
			}

			document.addEventListener('click', function(e){
			    var wrapper = document.querySelector('.notif-bell-wrapper');
			    var dropdown = document.getElementById('notifDropdown');

			    // Only close if the click happened OUTSIDE the bell/dropdown area
			    if(!wrapper.contains(e.target))
			    {
			        dropdown.style.display = 'none';
			    }
			});
	</script>