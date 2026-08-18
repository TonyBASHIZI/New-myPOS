<?php


/**
 * orders class
 */
class Order extends Model
{
    protected $table = "orders";
    protected $allowed_columns = [
        'order_no',
        'customer_name',
        'customer_phone',
        'total',
        'status',
        'created_by',
        'ref_user',
        'created_at',
    ];

    public function validate($data)
    {
        $errors = [];

        if(empty($data['order_no']))
            $errors[] = "Order number is required";

        if(empty($data['created_by']))
            $errors[] = "created_by (user_id) is required";

        if(!isset($data['total']) || !is_numeric($data['total']) || $data['total'] < 0)
            $errors[] = "Total must be a valid number";

        if(!in_array($data['status'] ?? 'Pending', ['Pending','Approved','Cancelled']))
            $errors[] = "Invalid status";

        return $errors;
    }

    // Called by the "just order" cart: no stock deduction, header + items.
    public function save_order($items, $user_id, $date = null)
    {
        if(!is_array($items) || count($items) == 0)
            return "Cart is empty";

        $db = new Database;
        $order_item_model = new OrderItem();

        $grand_total = 0;
        $prepared_items = [];

        foreach($items as $row)
        {
            $product = $db->query("SELECT * FROM products WHERE id = :id LIMIT 1", ['id' => $row['id']]);
            if(!is_array($product) || count($product) == 0)
                continue;

            $product = $product[0];
            $line_total = $row['qty'] * $product['amount'];
            $grand_total += $line_total;

            $prepared_items[] = [
                'product_id' => $product['id'],
                'qty'        => $row['qty'],
                'price'      => $product['amount'],
                'total'      => $line_total,
            ];
        }

        if(count($prepared_items) == 0)
            return "No valid products in order";

        $order = [
            'order_no'   => "ORD" . date("YmdHis"),
            'total'      => $grand_total,
            'status'     => 'Pending',
            'created_by' => $user_id,
            'created_at' => $date ?: date("Y-m-d H:i:s"),
        ];

        $errors = $this->validate($order);
        if(count($errors) > 0)
            return implode(", ", $errors);

        $order_id = $this->insert_get_id($order);

        foreach($prepared_items as $item)
        {
            $item['order_id'] = $order_id;
            $order_item_model->insert($item);
        }

        return $order['order_no']; // success: return order_no
    }
}

class OrderItem extends Model
{
    protected $table = "order_items";
    protected $allowed_columns = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

    public function validate($data)
    {
        $errors = [];

        if(empty($data['order_id']))
            $errors[] = "order_id is required";

        if(empty($data['product_id']))
            $errors[] = "product_id is required";

        if(!isset($data['qty']) || !is_numeric($data['qty']) || $data['qty'] <= 0)
            $errors[] = "Qty must be greater than 0";

        return $errors;
    }
}