<?php 

$tab = $_GET['tab'] ?? 'dashboard';


if($tab == "products")
{

	$productClass = new Product();

    $where = "";
    
    if(!empty($_GET['date_from']) && !empty($_GET['date_to']))
    {
        $from = $_GET['date_from']." 00:00:00";
        $to   = $_GET['date_to']." 23:59:59";
    
        $where = " WHERE date BETWEEN '$from' AND '$to' ";
    }
    
    $query = "SELECT * FROM products $where ORDER BY id DESC";
    
    $products = $productClass->query($query);
    
    // Total du stock
    $query_total = "SELECT SUM(qty * amount) AS total_stock FROM products $where";
    
    $total = $productClass->query($query_total);
    
    $total_stock = $total[0]['total_stock'] ?? 0;
    }else if($tab == "inventory"){
    $db = new Database();

    $date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : date('Y-m-01'); // default: start of this month
    $date_to   = isset($_GET['date_to'])   ? trim($_GET['date_to'])   : date('Y-m-d');

    $query = "
    SELECT 
        p.id AS product_id,
        p.barcode,
        p.description,
        p.qty AS current_stock,
        COALESCE(r.total_received, 0) AS qty_received,
        COALESCE(s.total_sold, 0) AS qty_sold
    FROM products p
    LEFT JOIN (
        SELECT product_id, SUM(qty_received) AS total_received
        FROM stock_received
        WHERE DATE(received_at) BETWEEN :date_from1 AND :date_to1
        GROUP BY product_id
    ) r ON r.product_id = p.id
    LEFT JOIN (
        SELECT barcode, SUM(qty) AS total_sold
        FROM sales
        WHERE DATE(date) BETWEEN :date_from2 AND :date_to2
        GROUP BY barcode
    ) s ON s.barcode = p.barcode
    ORDER BY p.description ASC
    ";

    $inventory = $db->query($query, [
        'date_from1' => $date_from,
        'date_to1'   => $date_to,
        'date_from2' => $date_from,
        'date_to2'   => $date_to,
    ]);

   
}else
    if($tab == "sales")
{
	
	$section = $_GET['s'] ?? 'table';
	$startdate = $_GET['start'] ?? null;
	$enddate = $_GET['end'] ?? null;


	$saleClass = new Sale();
	
	$limit = $_GET['limit'] ?? 20;
	$limit = (int)$limit;
	$limit = $limit < 1 ? 10 : $limit;

	$pager = new Pager($limit);
	$offset = $pager->offset;

// 	$query = "select * from sales order by id desc limit $limit offset $offset";
    $query = "select * from sales order by id desc limit $limit offset $offset";

	//get today's sales total
	$year = date("Y");
	$month = date("m");
	$day = date("d");

	$query_total = "SELECT sum(total) as total FROM sales WHERE day(date) = $day && month(date) = $month && year(date) = $year";
	$quer_total_sms = "SELECT 
    us.id,
    us.username,
    SUM(s.total) AS total_sales
    FROM sales s
    INNER JOIN users us ON us.id = s.user_id
    GROUP BY s.user_id
    ORDER BY total_sales DESC;";

    
    
	//if both start and end are set
 	if($startdate && $enddate)
        {
            $start = $startdate . " 00:00:00";
            $end   = $enddate . " 23:59:59";
        
            $query = "SELECT * FROM sales
                      WHERE date BETWEEN '$start' AND '$end'
                      ORDER BY id DESC
                      LIMIT $limit OFFSET $offset";
        
            $query_total = "SELECT SUM(total) AS total
                            FROM sales
                            WHERE date BETWEEN '$start' AND '$end'";
        }else

	//if only start date is set
 	if($startdate && !$enddate)
        {
            $start = $startdate . " 00:00:00";
            $end   = $startdate . " 23:59:59";
        
            $query = "SELECT * FROM sales
                      WHERE date BETWEEN '$start' AND '$end'
                      ORDER BY id DESC
                      LIMIT $limit OFFSET $offset";
        
            $query_total = "SELECT SUM(total) AS total
                            FROM sales
                            WHERE date BETWEEN '$start' AND '$end'";
        }
	

	$sales = $saleClass->query($query);

	$st = $saleClass->query($query_total);
	
	$result = $saleClass->query($quer_total_sms);
	
	$sales_total = 0;
	if($st){
		$sales_total = $st[0]['total'] ?? 0;
	}

	if($section == 'graph')
	{
		//read graph data
		$db = new Database();

		//query todays records
		$today = date('Y-m-d');
		$query = "SELECT total,date FROM sales WHERE DATE(date) = '$today' ";
		$today_records = $db->query($query);

		//query this months records
		$thismonth = date('m');
		$thisyear = date('Y');

		$query = "SELECT total,date FROM sales WHERE month(date) = '$thismonth' && year(date) = '$thisyear'";
		$thismonth_records = $db->query($query);

		//query this years records
		$query = "SELECT total,date FROM sales WHERE year(date) = '$thisyear'";
		$thisyear_records = $db->query($query);

	}

}if($tab == "rapport")
{
	
	$db = new Database();
	$section = $_GET['s'] ?? 'table';
	$startdate = $_GET['start'] ?? null;
	$enddate = $_GET['end'] ?? null;


	$saleClass = new Sale();
	
	$limit = $_GET['limit'] ?? 20;
	$limit = (int)$limit;
	$limit = $limit < 1 ? 10 : $limit;

	$pager = new Pager($limit);
	$offset = $pager->offset;

	
	
	$query = "SELECT * FROM products ORDER BY id DESC ";

	//get today's sales total
	$year = date("Y");
	$month = date("m");
	$day = date("d");

	$query_total= "SELECT sum(total) as total FROM sales WHERE day(date) = $day && month(date) = $month && year(date) = $year";
	$query_totals= "SELECT sum(total) as totals FROM sales ";


	//if both start and end are set
 	if($startdate && $enddate)
 	{
 		
 		$query = "select * from sales where date BETWEEN '$startdate' AND '$enddate' order by id desc limit $limit offset $offset";
 		$query_total = "select sum(total) as total from sales where date BETWEEN '$startdate' AND '$enddate'";
 	
 	}else

	//if only start date is set
 	if($startdate && !$enddate)
 	{
 		$styear = date("Y",strtotime($startdate));
 		$stmonth = date("m",strtotime($startdate));
 		$stday = date("d",strtotime($startdate));
 		
 		$query = "select * from sales where date = '$startdate' order by id desc limit $limit offset $offset";
 		$query_total = "select sum(total) as total from sales where date = '$startdate' ";
 		
 		
 	}
	

	$sales = $saleClass->query($query);

	$st = $saleClass->query($query_total);



	$sales_total = 0;
	if($st){
		$sales_total = $st[0]['total'] ?? 0;
	

	}

	if($section == 'graph')
	{
		//read graph data
		$db = new Database();

		//query todays records
		$today = date('Y-m-d');
		$query = "SELECT total,date FROM sales WHERE DATE(date) = '$today' ";
		$today_records = $db->query($query);

		//query this months records
		$thismonth = date('m');
		$thisyear = date('Y');

		$query = "SELECT total,date FROM sales WHERE month(date) = '$thismonth' && year(date) = '$thisyear'";
		$thismonth_records = $db->query($query);

		//query this years records
		$query = "SELECT total,date FROM sales WHERE year(date) = '$thisyear'";
		$thisyear_records = $db->query($query);

	}

}else
if($tab == "users")
{

	$limit = 10;
	$pager = new Pager($limit);
	$offset = $pager->offset;

	$userClass = new User();
	$users = $userClass->query("select * from users order by id desc limit $limit offset $offset");
}if($tab == "approvisionnement")
{

	$productClass = new Product();
	$products = $productClass->query("select * from products order by id desc");
}else
if($tab == "dashboard")
{

	$db = new Database();
	$query = "select count(id) as total from users";

	$myusers = $db->query($query);
	$total_users = $myusers[0]['total'];

	$query = "select count(id) as total from products";

	$myproducts = $db->query($query);
	$total_products = $myproducts[0]['total'];

	$query = "SELECT sum(total) AS total FROM sales";

	$mysales = $db->query($query);
	$total_sales = $mysales[0]['total'];

	$query = "SELECT sum(total) AS totals FROM sales WHERE DATE(date) = CURDATE() ";

	$mysaless = $db->query($query);
	$total_sales_todays = $mysaless[0]['totals'];

	$query = "SELECT COALESCE(count(*), 0) as totalsv from sales where DATE(date) = CURDATE() ";

	$mysales = $db->query($query);
	$total_sales_today = $mysales[0]['totalsv'];

	$query = "SELECT sum(qty*amount) AS netTotal FROM products";
	$stocknet = $db->query($query);
	$totalStock = $stocknet[0]['netTotal'];
	
	$query = "SELECT sum(qty*amount) AS netTotalShop2 FROM products WHERE shop=2";
	$stocknet2 = $db->query($query);
	$totalStockShop2 = $stocknet2[0]['netTotalShop2'];
	
	$query = "SELECT sum(qty*amount) AS netTotalShop1 FROM products WHERE shop=1";
	$stocknet1 = $db->query($query);
	$totalStockShop1 = $stocknet1[0]['netTotalShop1'];
	
	$query = "SELECT sum(balance) AS totalBalance FROM sales WHERE user_id=13";
	$mysales13 = $db->query($query);
	$total_balance_shop13 = $mysales13[0]['totalBalance'];
	
	$query = "SELECT sum(balance) AS totalBalance2 FROM sales WHERE user_id=12";
	$mysales12 = $db->query($query);
	$total_balance_shop12 = $mysales12[0]['totalBalance2'];
	
	$query = "SELECT sum(total) AS total FROM sales WHERE user_id=13";
	$mysalesShop1 = $db->query($query);
	$total_sales_shop1 = $mysalesShop1[0]['total'];
	
	$query = "SELECT sum(total) AS total FROM sales WHERE user_id=12";
	$mysalesShop2 = $db->query($query);
	$total_sales_shop2 = $mysalesShop2[0]['total'];
	
	$query = "SELECT sum(qty*amount) AS netTotallot FROM products WHERE lot='ARRIV-BOSCO-2103-26'";
	$stocklastlot = $db->query($query);
	$totalLastlot = $stocklastlot[0]['netTotallot'];

	$today_points = $db->query("SELECT COALESCE(SUM(points_amount), 0) AS total_points_today FROM sales WHERE DATE(date) = CURDATE()");
	$totalPointsToday = is_array($today_points) ? $today_points[0]['total_points_today'] : 0;

	
}else
if($tab =="categories")
{
	$categorieClass = new Categorie();
	$categories = $categorieClass->query("select * from categories order by id desc");	
}else
if($tab =="journal")
{
	$journalClass = new product();
	$journalClass2 = new approvisionnement();	
	$journal = $journalClass->query("SELECT * FROM approvisionnement appro INNER JOIN products prod ON prod.id=appro.id_produit ORDER BY prod.id DESC");	
}else
if($tab =="stock")
{
	$stockClass = new Product();
	$stock = $stockClass->query("SELECT * FROM products WHERE qty <= 4 ORDER BY id DESC");
}else
if($tab =="depense")
{
	$depenseClass = new Depense();

$where = "";

if(!empty($_GET['date_from']) && !empty($_GET['date_to']))
{
    $from = $_GET['date_from']." 00:00:00";
    $to   = $_GET['date_to']." 23:59:59";

    $where = " WHERE dep.date_depense BETWEEN '$from' AND '$to' ";
}

$query = "SELECT * 
          FROM depenses dep 
          INNER JOIN users us ON us.id = dep.user_id
          $where
          ORDER BY dep.id_depense DESC";

$depense = $depenseClass->query($query);


// Total dépenses
$query_total = "SELECT SUM(dep.montant) AS total_depense
                FROM depenses dep
                $where";

$total = $depenseClass->query($query_total);

$total_depense = $total[0]['total_depense'] ?? 0;

}else
if($tab =="voirboss")
{
	$voirBossClass = new Voirboss();
	$allVoirboss = $voirBossClass->query("SELECT voirboss.id AS id_voir,ref_sales,productname,montant_total_a_payer,montant_reduction,detail,date_creation,username FROM `voirboss` INNER JOIN users ON voirboss.ref_user = users.id ORDER BY voirboss.id DESC");
}else
if($tab == "transfert")
{
    $productClass = new Product();
    // On sélectionne tout de products, et on précise 'trans.qty' sous un nom unique 'transfer_qty'
    $query = "SELECT prod.*, trans.qty as transfer_qty, trans.from_location, trans.to_location, trans.date_transfert 
              FROM transfers trans 
              INNER JOIN products prod ON prod.id = trans.product_id 
              ORDER BY trans.id DESC";
              
    $products = $productClass->query($query);
}else
if($tab == "saleshistorique")
{
   $salesClass = new Sale();

$where = "";

if(!empty($_GET['date_from']) && !empty($_GET['date_to']))
{
    $from = $_GET['date_from'] . " 00:00:00";
    $to   = $_GET['date_to'] . " 23:59:59";

    $where = " WHERE date BETWEEN '$from' AND '$to' ";
}

$query = "SELECT * FROM sales $where ORDER BY id DESC";

$allsales = $salesClass->query($query);
}else
if($tab == "productshistorique")
{
    $productClass = new Product();
    
    $query = "SELECT * FROM products";
              
    $allpro = $productClass->query($query);

}else if($tab == "orders"){
    $db = new Database();

    $page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $per_page  = 20;
    $offset    = ($page - 1) * $per_page;

    $search    = isset($_GET['search']) ? trim($_GET['search']) : '';
    $date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
    $date_to   = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

    $where  = "WHERE 1=1";
    $params = [];

    if($search !== '')
    {
        $where .= " AND (
            o.order_no LIKE :search
            OR EXISTS (
                SELECT 1 FROM order_items oi2
                INNER JOIN products p2 ON p2.id = oi2.product_id
                WHERE oi2.order_id = o.id AND p2.description LIKE :search
            )
            OR EXISTS (
                SELECT 1 FROM users u2
                WHERE u2.id = o.created_by AND u2.username LIKE :search
            )
        )";
        $params['search'] = '%' . $search . '%';
    }

    if($date_from !== '')
    {
        $where .= " AND DATE(o.created_at) >= :date_from";
        $params['date_from'] = $date_from;
    }

    if($date_to !== '')
    {
        $where .= " AND DATE(o.created_at) <= :date_to";
        $params['date_to'] = $date_to;
    }

    $count_query = "SELECT COUNT(DISTINCT o.id) as total FROM orders o $where";
    $count_result = $db->query($count_query, $params);
    $total_orders = $count_result[0]['total'];
    $total_pages  = max(1, ceil($total_orders / $per_page));

    $id_query = "
        SELECT o.id FROM orders o
        $where
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT $per_page OFFSET $offset
    ";
    $page_ids_result = $db->query($id_query, $params);
    $id_list = array_column((array)$page_ids_result, 'id');

    $orders = [];

    if(!empty($id_list))
    {
        $in_placeholders = [];
        $in_params = [];
        foreach($id_list as $i => $oid)
        {
            $key = "id$i";
            $in_placeholders[] = ":$key";
            $in_params[$key] = $oid;
        }

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
		ORDER BY o.created_at DESC, o.id, oi.id
		";
		$rows = $db->query($query);

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
    }
}
else if($tab == "stock"){
    $db = new Database();

    $products = $db->query("
        SELECT id, description, barcode, qty
        FROM products
        ORDER BY description ASC
    ");

    if(!is_array($products)) $products = [];  

}else if($tab == "stocks"){
    $db = new Database();

    $products = $db->query("
        SELECT id, description, barcode, qty
        FROM products
        ORDER BY description ASC
    ");

    $stock_received = $db->query("
        SELECT sr.*, p.description, p.barcode,u.username
        FROM stock_received sr
        LEFT JOIN products p ON p.id = sr.product_id
        LEFT JOIN users u ON u.id = sr.received_by
        ORDER BY sr.received_at DESC
        LIMIT 100
    ");
    if(!is_array($stock_received)) $stock_received = [];   
    if(!is_array($products)) $products = [];  

}else if($tab == "inventory"){
    $db = new Database();

    $date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : date('Y-m-01');
    $date_to   = isset($_GET['date_to'])   ? trim($_GET['date_to'])   : date('Y-m-d');

    // 1. All products
    $products = $db->query("SELECT id, barcode, description, amount, qty FROM products ORDER BY description ASC");
    if(!is_array($products)) $products = [];

    // 2. Received totals per product, for this date range
    $received_rows = $db->query("
        SELECT product_id, SUM(qty_received) AS total_received
        FROM stock_received
        WHERE DATE(received_at) BETWEEN :date_from AND :date_to
        GROUP BY product_id
    ", ['date_from' => $date_from, 'date_to' => $date_to]);
    if(!is_array($received_rows)) $received_rows = [];

    // 3. Sold totals per barcode, for this date range
    $sold_rows = $db->query("
        SELECT barcode, SUM(qty) AS total_sold
        FROM sales
        WHERE DATE(date) BETWEEN :date_from AND :date_to
        GROUP BY barcode
    ", ['date_from' => $date_from, 'date_to' => $date_to]);
    if(!is_array($sold_rows)) $sold_rows = [];

    // Index received/sold by key for fast lookup while merging
    $received_map = [];
    foreach($received_rows as $r) $received_map[$r['product_id']] = $r['total_received'];

    $sold_map = [];
    foreach($sold_rows as $s) $sold_map[$s['barcode']] = $s['total_sold'];

    // Merge into final inventory array
    $inventory = [];
    $totals = ['qty_received' => 0, 'qty_sold' => 0, 'current_stock' => 0, 'total_net' => 0];

    foreach($products as $p)
    {
        $qty_received = isset($received_map[$p['id']]) ? $received_map[$p['id']] : 0;
        $qty_sold     = isset($sold_map[$p['barcode']]) ? $sold_map[$p['barcode']] : 0;
        $total_net    = $p['qty'] * $p['amount'];

        $inventory[] = [
            'product_id'    => $p['id'],
            'barcode'       => $p['barcode'],
            'description'   => $p['description'],
            'amount'        => $p['amount'],
            'current_stock' => $p['qty'],
            'qty_received'  => $qty_received,
            'qty_sold'      => $qty_sold,
            'total_net'     => $total_net,
        ];

        $totals['qty_received']  += $qty_received;
        $totals['qty_sold']      += $qty_sold;
        $totals['current_stock'] += $p['qty'];
        $totals['total_net']     += $total_net;
    }

}else if($tab == "cash_closing"){
    $db = new Database();
    $user_id = auth("id");

    $closings = $db->query("
        SELECT cc.*, u.username
        FROM cash_closings cc
        LEFT JOIN users u ON u.id = cc.user_id
        ORDER BY cc.closing_date DESC, cc.created_at DESC
        LIMIT 50
    ");
    if(!is_array($closings)) $closings = [];

    
}else if($tab == "print_labels"){

    $db = new Database();

    $products = $db->query("SELECT id, description, barcode, amount FROM products ORDER BY description ASC");
    if(!is_array($products)) $products = [];

    
}else if($tab == "product-facture"){
	
    $db = new Database();
    $receipt_no = isset($_GET['id']) ? $_GET['id'] : '';

    $sales_items = $db->query("SELECT * FROM sales WHERE receipt_no = :id", ['id' => $receipt_no]);
    if(!is_array($sales_items) || count($sales_items) == 0)
    {
        die("Receipt not found.");
    }

    $first_row = $sales_items[0];
    $user_result = $db->query("SELECT username FROM users WHERE id = :uid LIMIT 1", ['uid' => $first_row['user_id']]);
    $cashier_name = is_array($user_result) && count($user_result) > 0 ? $user_result[0]['username'] : 'Unknown';

    $customer = null;
    if(!empty($first_row['customer_phone']))
    {
        $customer_result = $db->query("SELECT name, phone, points FROM customers WHERE phone = :phone LIMIT 1", ['phone' => $first_row['customer_phone']]);
        $customer = is_array($customer_result) && count($customer_result) > 0 ? $customer_result[0] : null;
    }

    $grand_total = 0;
    $points_amount_total = 0;
    foreach($sales_items as $item)
    {
        $grand_total += $item['total'];
        $points_amount_total += $item['points_amount'];
    }

    
}



if(Auth::access('supervisor')){
	require views_path('admin/admin');
}else{
if(Auth::access('cashier')){
    // Si l'utilisateur n'a PAS au moins le niveau cashier
    require views_path('admin/admin');
    die();
}else
	Auth::setMessage("You dont have access to the admin page");
	require views_path('auth/denied');
}

