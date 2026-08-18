<?php 


/**
 * products class
 */
class Voirboss extends Model
{
	
	protected $table = "voirboss";

	protected $allowed_columns = [

				'id',
				'ref_sales',
				'productname',
				'montant_total_a_payer',
				'montant_reduction',
				'detail',
				'ref_user',
				'date_creation',
				
			];


 public function validate($data)
{
    $errors = [];

    if (empty($data['ref_sales'])) {
        $errors['ref_sales'] = "La référence de vente est requise.";
    }
    
    if (empty($data['productname'])) {
        $errors['productname'] = "Le nom du produit est requis.";
    }

    return $errors; // Important pour que le Model puisse vérifier les erreurs
}


// public function insert($data) {
//     $db = new Database();
//     $columns = implode(',', array_keys($data));
//     $values = ':' . implode(',:', array_keys($data));
//     $query = "insert into {$this->table} ($columns) values ($values)";
//     return $db->query($query, $data);
// }


}