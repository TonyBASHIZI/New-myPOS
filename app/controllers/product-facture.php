<?php

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

require views_path('admin/product-facture');