<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
defined("ABSPATH") ? "":die();

// capture ajax data
$raw_data = file_get_contents("php://input");
if(!empty($raw_data))
{
	$OBJ = json_decode($raw_data,true);
	if(is_array($OBJ))
	{
		/** RECHERCHE PRODUIT **/
		if($OBJ['data_type'] == "search")
		{
			$productClass = new Product();
			if(!empty($OBJ['text']))
			{
				$barcode = $OBJ['text'];
				$text = "%".$OBJ['text']."%";
				$query = "select * from products where description like :find || barcode = :barcode order by id desc";
				$rows = $productClass->query($query,['find'=>$text,'barcode'=>$barcode]);
			}else{
				$rows = $productClass->getAll();
			}
			
			if($rows){
				foreach ($rows as $key => $row) {
					$rows[$key]['description'] = strtoupper($row['description']);
					$rows[$key]['image'] = crop($row['image']);
					
				}
				$info['data_type'] = "search";
				$info['data'] = $rows;
				echo json_encode($info);
			}
		}

		/** CHECKOUT (VENTE NORMALE) **/
		elseif($OBJ['data_type'] == "checkout")
		{
			$data = $OBJ['text'];
			$receipt_no = get_receipt_no();
			$user_id = auth("id");
			$custom_date = isset($OBJ['date']) ? $OBJ['date'] : date("Y-m-d");
    		$final_date = $custom_date . " " . date("H:i:s"); ;
			$balance = isset($OBJ['balance']) ? (float)$OBJ['balance'] : 0;

			 $custom_date = isset($OBJ['date']) ? $OBJ['date'] : date("Y-m-d");
    	$final_date = $custom_date . " " . date("H:i:s"); 

			$db = new Database();

			if(!empty($OBJ['order_id']))
				{
				    $cashier_id = auth("id");
				    $db->query("UPDATE orders SET status = 'Approved', ref_user = :ref_user WHERE id = :id", [
				        'ref_user' => $cashier_id,
				        'id'       => $OBJ['order_id']
				    ]);
				}

			foreach ($data as $row) {
				$query = "select * from products where id = :id limit 1";
				$check = $db->query($query,['id'=>$row['id']]);

				if(is_array($check))
				{
					$check = $check[0];
					$qty = $row['qty'];

					$arr = [];
					$arr['barcode']     = $check['barcode'];
					$arr['description'] = $check['description'];
					$arr['amount']      = $check['amount'];
					$arr['qty']         = $qty;
					$arr['total']       = $qty * $check['amount'];
					$arr['receipt_no']  = $receipt_no;
					$arr['date']        = $final_date;
					$arr['user_id']     = $user_id;
					$arr['balance']     = $balance; 

					$query = "insert into sales (barcode,receipt_no,description,qty,amount,total,date,user_id,balance) values (:barcode,:receipt_no,:description,:qty,:amount,:total,:date,:user_id,:balance)";
					$db->query($query,$arr);

					$db->query("update products set views = views + 1 where id = :id limit 1",['id'=>$check['id']]);
					$db->query("UPDATE products SET qty = qty - :qty WHERE id = :id LIMIT 1",['qty'=>$qty, 'id'=>$check['id']]);
				}
			}

			$info['data_type'] = "checkout";
			$info['data'] = "items saved successfully!";
			echo json_encode($info);

		}elseif($OBJ['data_type'] == "load_order")
			{
			    $order_id = $OBJ['order_id'];
			    $db = new Database();
			    $order = $db->query("SELECT * FROM orders WHERE id = :id LIMIT 1", ['id' => $order_id]);
			    if(!is_array($order) || count($order) == 0)
			    {
			        $info['data_type'] = "load_order";
			        $info['data'] = false;
			        $info['message'] = "Order not found";
			        echo json_encode($info);
			        die();
			    }
			    $items = $db->query("SELECT product_id, qty FROM order_items WHERE order_id = :order_id", ['order_id' => $order_id]);
			    $info['data_type'] = "load_order";
			    $info['data'] = is_array($items) ? $items : [];
			    $info['order_no'] = $order[0]['order_no'];
			    $info['order_id'] = $order[0]['id'];   // <-- ADD THIS ONE LINE
			    echo json_encode($info);
			    die();
			}

		elseif($OBJ['data_type'] == "save_order")
{
    $data = $OBJ['text'];

    if(!is_array($data) || count($data) == 0)
    {
        $info['data_type'] = "save_order";
        $info['data'] = "Panier vide";
        echo json_encode($info);
        die();
    }

    $user_id   = auth("id");
    $order_no  = "ORD" . date("YmdHis");
    $sale_date = isset($OBJ['date']) && $OBJ['date'] != "" ? $OBJ['date'] . " " . date("H:i:s") : date("Y-m-d H:i:s");

    $db = new Database();

    // Pull all product rows once, and build order_items data in a single pass
    $order_items = [];
    $grand_total = 0;

    foreach($data as $row)
    {
        $check = $db->query("SELECT * FROM products WHERE id = :id LIMIT 1", ['id' => $row['id']]);
        if(is_array($check) && count($check) > 0)
        {
            $check = $check[0];
            $line_total = $row['qty'] * $check['amount'];
            $grand_total += $line_total;

            $order_items[] = [
                'product_id' => $check['id'],
                'qty'        => $row['qty'],
                'price'      => $check['amount'],
                'total'      => $line_total
            ];
        }
    }

    if(count($order_items) == 0)
    {
        $info['data_type'] = "save_order";
        $info['data'] = "Aucun produit valide dans la commande";
        echo json_encode($info);
        die();
    }

    // Enregistrer la commande
    $db->query("
        INSERT INTO orders(order_no,created_by,ref_user,total,status,created_at)
        VALUES(:order_no,:created_by,:ref_user,:total,'Pending',:created_at)
    ",[
        'order_no'   => $order_no,
        'created_by' => $user_id,
        'ref_user'   => $user_id,
        'total'      => $grand_total,
        'created_at' => $sale_date
    ]);

    $order_id = $db->lastInsertId();

    foreach($order_items as $item)
    {
        $item['order_id'] = $order_id;
        $db->query("
            INSERT INTO order_items(order_id,product_id,qty,price,total)
            VALUES(:order_id,:product_id,:qty,:price,:total)
        ", $item);
    }

    $info['data_type'] = "save_order";
    $info['data'] = "Commande enregistrée avec succès";
    $info['order_no'] = $order_no;
    echo json_encode($info);
    die();
}

		/** RAPPORT AU BOSS (VOIR BOSS) **/
				/** RAPPORT AU BOSS (VOIR BOSS) **/
		elseif($OBJ['data_type'] == "save_boss_report")
		{
			$db = new Database(); 
			
			$arr = [];
			$arr['ref_sales']             = $OBJ['sale_id'];
			$arr['productname']           = $OBJ['productname'];
			$arr['montant_total_a_payer'] = $OBJ['montant_reel'];
			$arr['montant_reduction']     = $OBJ['montant_boss'];
			$arr['detail']                = $OBJ['note'];
			$arr['ref_user']              = auth("id");
            $arr['date_creation']         = date("Y-m-d H:i:s");

			// CORRECTION : Les noms des paramètres doivent correspondre exactement aux clés du tableau $arr
			$query = "insert into voirboss (ref_sales, productname, montant_total_a_payer, montant_reduction, detail, ref_user, date_creation) 
                      values (:ref_sales, :productname, :montant_total_a_payer, :montant_reduction, :detail, :ref_user, :date_creation)";
			
			$db->query($query, $arr);

			// CORRECTION : URL complète de l'API Keccel
			$param1 = "243977756737";
			$param2 = "PBCARSoil Boss: Vente #" . $arr['ref_sales'] . " (" . $arr['productname'] . "). Reel: " . $arr['montant_total_a_payer'] . "$, Paye: " . $arr['montant_reduction'] . "$.";
			
			$url = "https://api.keccel.com". urlencode($param1) . "&message=". urlencode($param2);
			
            @file_get_contents($url);

			$info['data_type'] = "save_boss_report";
			$info['data'] = "Voir boss added !";
            
            if (ob_get_length()) ob_clean(); 
			echo json_encode($info);
			die(); 
			
		}elseif($OBJ['data_type'] == "save_boss_edit")
		{
			$db = new Database(); 
			
			$arr = [];
			$arr['id'] = $OBJ['id'];
			$arr['productname']           = $OBJ['productname'];
			$arr['montant_total_a_payer'] = $OBJ['montant_reel'];
			$arr['montant_reduction']     = $OBJ['montant_boss'];
			$arr['detail']                = $OBJ['note'];
			$arr['ref_user']              = auth("id");
            $arr['date_creation']         = date("Y-m-d H:i:s");

			// CORRECTION : Les noms des paramètres doivent correspondre exactement aux clés du tableau $arr
			$query = "UPDATE voirboss 
          SET 
              productname = :productname,
              montant_total_a_payer = :montant_total_a_payer,
              montant_reduction = :montant_reduction,
              detail = :detail,
              ref_user = :ref_user,
              date_creation = :date_creation
          WHERE id = :id";

            $db->query($query, $arr);

			// CORRECTION : URL complète de l'API Keccel
			$param1 = "243973697114";
			$param2 = "PBCARSoil Boss: Vente #" . $arr['ref_sales'] . " (" . $arr['productname'] . "). Reel: " . $arr['montant_total_a_payer'] . "$, Paye: " . $arr['montant_reduction'] . "$.";
			
			$url = "https://api.keccel.com". urlencode($param1) . "&message=". urlencode($param2);
			
            @file_get_contents($url);

			$info['data_type'] = "save_boss_edit";
			$info['data'] = "Edit Voir boss done !";
            
            if (ob_get_length()) ob_clean(); 
			echo json_encode($info);
			die(); 
		}

		elseif($OBJ['data_type'] == "add_depense")
{
    $db = new Database();
     $arr = [
        'montant'       => $OBJ['montant'],
        'motif_depense' => $OBJ['motif'],
        'user_id'       => auth("id"), // On prend l'ID de l'utilisateur connecté
        'date_depense'  => date("Y-m-d H:i:s")
    ];
   $query = "insert into depenses (montant, motif_depense, user_id, date_depense) 
              values (:montant, :motif_depense, :user_id, :date_depense)";
    
    $db->query($query, $arr);

    $info['data_type'] = "add_depense";
    $info['data'] = "Dépense de " . $arr['montant'] . "$ enregistrée !";
    
    if (ob_get_length()) ob_clean();
    echo json_encode($info);
    die();
}

elseif($OBJ['data_type'] == "edit_depense")
{
    $db = new Database();
    $arr = [
        'id_depense'    => $OBJ['id_depense'],
        'montant'       => $OBJ['montant'],
        'motif_depense' => $OBJ['motif'],
    ];

    $query = "UPDATE depenses SET montant = :montant, motif_depense = :motif_depense WHERE id_depense = :id_depense LIMIT 1";
    $db->query($query, $arr);

    $info['data_type'] = "edit_depense";
    $info['data'] = "Dépense mise à jour !";
    
    if (ob_get_length()) ob_clean();
    echo json_encode($info);
    die();
}

elseif($OBJ['data_type'] == "transfer_stock")
{
    $db = new Database();
    
    // On prépare les données pour la table 'transfers' uniquement
    $arr = [];
    $arr['product_id']    = $OBJ['id_source'];  // L'ID du produit
    $arr['from_location'] = 2;                  // Source fixée au Shop 2 (Bar)
    $arr['to_location']   = (int)$OBJ['to_shop']; // Shop destination choisi
    $arr['qty']           = (int)$OBJ['qty'];    // Quantité transférée
    $arr['user_id']       = auth("id");         // Utilisateur connecté
    $arr['date_transfert'] = date("Y-m-d H:i:s");

    // INSERTION UNIQUEMENT DANS LA TABLE TRANSFERS
    $query = "insert into transfers (product_id, from_location, to_location, qty, user_id, date_transfert) 
              values (:product_id, :from_location, :to_location, :qty, :user_id, :date_transfert)";
    
    $db->query($query, $arr);

    // Réponse JSON pour le JavaScript
    $info['data_type'] = "transfer_stock";
    $info['data'] = "Historique de transfert enregistré (le stock reste inchangé).";
    
    echo json_encode($info);
    die();
}




	}
}
