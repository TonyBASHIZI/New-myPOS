<?php

class StockReceived extends Model
{
    protected $table = "stock_received";
    protected $allowed_columns = [
        'product_id',
        'qty_received',
        'note',
        'received_by',
    ];

    public function validate($data)
    {
        $errors = [];

        if(empty($data['product_id']))
            $errors[] = "Product is required";

        if(!isset($data['qty_received']) || !is_numeric($data['qty_received']) || $data['qty_received'] <= 0)
            $errors[] = "Quantity received must be greater than 0";

        if(empty($data['received_by']))
            $errors[] = "received_by (user_id) is required";

        return $errors;
    }

    // Logs the received quantity AND bumps products.qty, in one call.
    // Uses a transaction so both writes succeed or neither does.
    public function receive($product_id, $qty, $note, $user_id)
    {
        $data = [
            'product_id'   => $product_id,
            'qty_received' => $qty,
            'note'         => $note,
            'received_by'  => $user_id,
        ];

        $errors = $this->validate($data);
        if(count($errors) > 0)
            return implode(", ", $errors);

        $db = new Database;
        $db->beginTransaction();

        try {
            $this->insert($data);

            $db->query(
                "UPDATE products SET qty = qty + :qty WHERE id = :id",
                ['qty' => $qty, 'id' => $product_id]
            );

            $db->commit();
            return true;

        } catch (Exception $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }
}