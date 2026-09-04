<?php

$db = new Database();
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$order_result = $db->query("
    SELECT o.*, u.username AS creator_name
    FROM orders o
    LEFT JOIN users u ON u.id = o.created_by
    WHERE o.id = :id LIMIT 1
", ['id' => $order_id]);

if(!is_array($order_result) || count($order_result) == 0)
{
    die("Order not found.");
}
$order = $order_result[0];

$items = $db->query("
    SELECT oi.*, p.description, p.barcode
    FROM order_items oi
    LEFT JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = :order_id
", ['order_id' => $order_id]);
if(!is_array($items)) $items = [];

require views_path('admin/order-receipt');