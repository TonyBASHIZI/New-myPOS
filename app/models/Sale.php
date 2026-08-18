<?php 


/**
 * sales class
 */
class Sale extends Model
{
    protected $table = "sales";
    protected $allowed_columns = [
        'barcode',
        'receipt_no',
        'user_id',
        'description',
        'qty',
        'amount',
        'total',
        'date',
    ];

    public function validate($data)
    {
        $errors = [];

        if(empty($data['barcode']))
            $errors[] = "Barcode is required";

        if(empty($data['receipt_no']))
            $errors[] = "Receipt number is required";

        if(empty($data['user_id']))
            $errors[] = "Cashier (user_id) is required";

        if(!isset($data['qty']) || !is_numeric($data['qty']) || $data['qty'] <= 0)
            $errors[] = "Qty must be a number greater than 0";

        if(!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] < 0)
            $errors[] = "Amount must be a valid number";

        if(!isset($data['total']) || !is_numeric($data['total']) || $data['total'] < 0)
            $errors[] = "Total must be a valid number";

        if(empty($data['date']))
            $errors[] = "Date is required";

        return $errors; // empty array = valid
    }

    // Called by the cashier cart flow: validates stock, deducts it,
    // writes one flat row per line item into `sales`, all in one transaction.
    public function save_sale($receipt_no, $user_id, $items, $date)
    {
        $db = new Database;
        $db->beginTransaction();

        try {
            foreach($items as $row)
            {
                $product = $db->query(
                    "SELECT * FROM products WHERE id = :id LIMIT 1 FOR UPDATE",
                    ['id' => $row['id']]
                );

                if(!is_array($product) || count($product) == 0)
                    throw new Exception("Product not found: ".$row['id']);

                $product = $product[0];

                if($row['qty'] > $product['qty'])
                    throw new Exception("Stock insufficient for: ".$product['description']);

                $line = [
                    'barcode'     => $product['barcode'],
                    'receipt_no'  => $receipt_no,
                    'user_id'     => $user_id,
                    'description' => $product['description'],
                    'qty'         => $row['qty'],
                    'amount'      => $product['amount'],
                    'total'       => $row['qty'] * $product['amount'],
                    'date'        => $date,
                ];

                $errors = $this->validate($line);
                if(count($errors) > 0)
                    throw new Exception(implode(", ", $errors));

                $this->insert($line);

                // deduct stock
                $db->query(
                    "UPDATE products SET qty = qty - :qty WHERE id = :id",
                    ['qty' => $row['qty'], 'id' => $product['id']]
                );
            }

            $db->commit();
            return true;

        } catch (Exception $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }
}