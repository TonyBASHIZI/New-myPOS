<?php 

	class Categorie extends Model
	{
		protected $table = "categories";

		protected $allowed_columns = [

			'id',
			'designation',
			'description'
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


 ?>