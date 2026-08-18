<?php 


/**
 * products class
 */
class Depense extends Model
{
	
	protected $table = "depenses";

	protected $allowed_columns = [

				'id_depense',
				'montant',
				'motif_depense',
				'user_id',
				'date_depense',
				
			];


 	public function validate($data,$id=null)
   		{
   			$errors=[];
   			//check description
   			if (empty($data['designationCategorie'])) {
   				$errors['designationCategorie'] = "Product designation is required";
   			}elseif (!preg_match('/[a-zA-Z0-9 _\-\&\(\)]+/',$data['description'])) {
   				$errors['designationCategorie'] = "Only letters allowed";
   			}
   		}

}