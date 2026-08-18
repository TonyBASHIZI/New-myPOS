<?php 

class Approvisionnement extends Model
{
	
	protected $table = "approvisionnement";
	
	protected $allowed_columns = [
	
		'id_produit',
		'qty_appro',
		
	];


	
	public function validate($data, $id = null)
	{
		$errors = [];

			//check description
			if(empty($data['qty_appro']))
			{
				$errors['qty_appro'] = "qty";
			}else
			if(!preg_match('/[a-zA-Z0-9 _\-\&\(\)]+/', $data['description']))
			{
				$errors['qty_appro'] = "Only number allowed in description";
			}

			
			
		return $errors;
	}


}