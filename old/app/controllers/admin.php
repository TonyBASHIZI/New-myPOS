<?php 

$tab = $_GET['tab'] ?? 'dashboard';


if($tab == "products")
{

	$productClass = new Product();
	$products = $productClass->query("select * from products order by id desc");
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

	$query = "select * from sales order by id desc limit $limit offset $offset";

	//get today's sales total
	$year = date("Y");
	$month = date("m");
	$day = date("d");

	$query_total = "SELECT sum(total) as total FROM sales WHERE day(date) = $day && month(date) = $month && year(date) = $year";


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
	$journal = $journalClass->query("SELECT * FROM products prod INNER JOIN approvisionnement appro ON appro.id_produit=prod.id ORDER BY prod.id DESC");	
}
if(Auth::access('supervisor')){
	require views_path('admin/admin');
}else{

	Auth::setMessage("You dont have access to the admin page");
	require views_path('auth/denied');
}

