<?php  

var_dump($_SESSION['USER'] );
class Promo extends Model
{
    protected $table = "promos";

    protected $allowed_columns = [
        'product_id', 'regular_price', 'promo_price',
        'start_date', 'end_date', 'active', 'created_by'
    ];

    public function validate($data)
    {
        $errors = [];
        if(empty($data['product_id'])) $errors[] = "Product is required";
        if(!isset($data['promo_price']) || !is_numeric($data['promo_price']) || $data['promo_price'] <= 0)
            $errors[] = "Promo price must be greater than 0";

        if(isset($data['regular_price']) && $data['promo_price'] >= $data['regular_price'])
            $errors[] = "Promo price must be lower than the regular price";

        if(empty($data['start_date']) || empty($data['end_date']))
            $errors[] = "Start and end date are required";

        if(!empty($data['start_date']) && !empty($data['end_date']) && $data['start_date'] > $data['end_date'])
            $errors[] = "Start date must be before end date";

        return $errors;
    }
}