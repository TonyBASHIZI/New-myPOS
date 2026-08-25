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
    $final_date = $custom_date . " " . date("H:i:s");
    $balance = isset($OBJ['balance']) ? (float)$OBJ['balance'] : 0;
    $payment_method = isset($OBJ['payment_method']) ? $OBJ['payment_method'] : 'Cash';

    $db = new Database();

    if(!empty($OBJ['order_id']))
    {
        $cashier_id = auth("id");
        $db->query("UPDATE orders SET status = 'Approved', ref_user = :ref_user WHERE id = :id", [
            'ref_user' => $cashier_id,
            'id'       => $OBJ['order_id']
        ]);
    }
    if(!empty($OBJ['points_used']) && $OBJ['points_used'] > 0)
		{
		    $db->query(
		        "UPDATE customers SET points = points - :points WHERE phone = :phone",
		        ['points' => $OBJ['points_used'], 'phone' => $OBJ['customer_phone']]
		    );
		}
    $grand_total = 0; // NEW — accumulate as we go

    foreach ($data as $row) {
        $query = "select * from products where id = :id limit 1";
        $check = $db->query($query,['id'=>$row['id']]);
        if(is_array($check))
        {
            $check = $check[0];
            $qty = $row['qty'];
            $line_total = $qty * $check['amount'];
            $grand_total += $line_total; // NEW — add this line's total to the running sum

            $arr = [];
            $arr['barcode']     = $check['barcode'];
            $arr['description'] = $check['description'];
            $arr['amount']      = $check['amount'];
            $arr['qty']         = $qty;
            $arr['total']       = $line_total;
            $arr['receipt_no']  = $receipt_no;
            $arr['date']        = $final_date;
            $arr['user_id']     = $user_id;
            $arr['balance']     = $balance;
            $arr['payment_method'] = $payment_method;
            $query = "insert into sales (barcode,receipt_no,description,qty,amount,total,date,user_id,balance,payment_method) values (:barcode,:receipt_no,:description,:qty,:amount,:total,:date,:user_id,:balance,:payment_method)";
            $db->query($query,$arr);
            $db->query("update products set views = views + 1 where id = :id limit 1",['id'=>$check['id']]);
            $db->query("UPDATE products SET qty = qty - :qty WHERE id = :id LIMIT 1",['qty'=>$qty, 'id'=>$check['id']]);
        }
    }

    // MOVED here, now that $grand_total is actually populated
    if(!empty($OBJ['customer_phone']))
    {
        $points_earned = $grand_total * 0.05;
        $db->query(
            "UPDATE customers SET points = points + :points WHERE phone = :phone",
            ['points' => $points_earned, 'phone' => $OBJ['customer_phone']]
        );
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
			}elseif($OBJ['data_type'] == "receive_stock")
		{
		    $product_id = $OBJ['product_id'];
		    $qty        = (float)$OBJ['qty'];
		    $note       = isset($OBJ['note']) ? trim($OBJ['note']) : '';
		    $user_id    = auth("id");
		    $db = new Database();

		    if($qty <= 0)
		    {
		        $info['success'] = false;
		        $info['message'] = "Quantity must be greater than 0";
		        echo json_encode($info);
		        die();
		    }

		    $db->query(
		        "INSERT INTO stock_received(product_id,qty_received,note,received_by) VALUES(:product_id,:qty,:note,:user_id)",
		        ['product_id' => $product_id, 'qty' => $qty, 'note' => $note, 'user_id' => $user_id]
		    );

		    $db->query(
		        "UPDATE products SET qty = qty + :qty WHERE id = :id",
		        ['qty' => $qty, 'id' => $product_id]
		    );

		    $info['success'] = true;
		    $info['message'] = "Stock received successfully";
		    echo json_encode($info);
		    die();
		}elseif($OBJ['data_type'] == "update_stock_received")
			{
			    $id      = $OBJ['id'];
			    $new_qty = (float)$OBJ['qty'];
			    $note    = isset($OBJ['note']) ? trim($OBJ['note']) : '';

			    if($new_qty <= 0)
			    {
			        $info['success'] = false;
			        $info['message'] = "Quantity must be greater than 0";
			        echo json_encode($info);
			        die();
			    }

			    $db = new Database();
			    $existing = $db->query("SELECT * FROM stock_received WHERE id = :id LIMIT 1", ['id' => $id]);

			    if(!is_array($existing) || count($existing) == 0)
			    {
			        $info['success'] = false;
			        $info['message'] = "Entry not found";
			        echo json_encode($info);
			        die();
			    }

			    $existing = $existing[0];
			    $old_qty = $existing['qty_received'];
			    $diff = $new_qty - $old_qty; // could be positive or negative

			    $db->beginTransaction();
			    try {
			        $db->query(
			            "UPDATE stock_received SET qty_received = :qty, note = :note WHERE id = :id",
			            ['qty' => $new_qty, 'note' => $note, 'id' => $id]
			        );

			        $db->query(
			            "UPDATE products SET qty = qty + :diff WHERE id = :product_id",
			            ['diff' => $diff, 'product_id' => $existing['product_id']]
			        );

			        $db->commit();
			        $info['success'] = true;
			        $info['message'] = "Updated successfully";
			    } catch (Exception $e) {
			        $db->rollBack();
			        $info['success'] = false;
			        $info['message'] = $e->getMessage();
			    }

			    echo json_encode($info);
			    die();
			}
			elseif($OBJ['data_type'] == "delete_stock_received")
			{
			    $id = $OBJ['id'];
			    $db = new Database();

			    $existing = $db->query("SELECT * FROM stock_received WHERE id = :id LIMIT 1", ['id' => $id]);

			    if(!is_array($existing) || count($existing) == 0)
			    {
			        $info['success'] = false;
			        $info['message'] = "Entry not found";
			        echo json_encode($info);
			        die();
			    }

			    $existing = $existing[0];

			    $db->beginTransaction();
			    try {
			        $db->query(
			            "UPDATE products SET qty = qty - :qty WHERE id = :product_id",
			            ['qty' => $existing['qty_received'], 'product_id' => $existing['product_id']]
			        );

			        $db->query("DELETE FROM stock_received WHERE id = :id", ['id' => $id]);

			        $db->commit();
			        $info['success'] = true;
			        $info['message'] = "Deleted successfully";
			    } catch (Exception $e) {
			        $db->rollBack();
			        $info['success'] = false;
			        $info['message'] = $e->getMessage();
			    }

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
}elseif($OBJ['data_type'] == "lookup_customer")
{
    $phone = trim($OBJ['phone']);
    $db = new Database();

    $customer = $db->query("SELECT * FROM customers WHERE phone = :phone LIMIT 1", ['phone' => $phone]);

    if(is_array($customer) && count($customer) > 0)
    {
        $info['found']  = true;
        $info['name']   = $customer[0]['name'];
        $info['points'] = $customer[0]['points'];
    }else{
        $info['found'] = false;
    }
    echo json_encode($info);
    die();
}
elseif($OBJ['data_type'] == "register_customer")
{
    $phone = trim($OBJ['phone']);
    $name  = trim($OBJ['name']);
    $db = new Database();

    $existing = $db->query("SELECT id FROM customers WHERE phone = :phone LIMIT 1", ['phone' => $phone]);
    if(is_array($existing) && count($existing) > 0)
    {
        $info['success'] = false;
        $info['message'] = "This phone number is already registered.";
        echo json_encode($info);
        die();
    }

    $db->query("INSERT INTO customers(phone,name,points) VALUES(:phone,:name,0)", [
        'phone' => $phone,
        'name'  => $name
    ]);

    $info['success'] = true;
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
}elseif($OBJ['data_type'] == "save_cash_closing")
	{
	    $user_id = auth("id");
	    $closing_date = isset($OBJ['closing_date']) ? $OBJ['closing_date'] : date('Y-m-d');
	    $counted_cash = (float)$OBJ['counted_cash'];
	    $note = isset($OBJ['note']) ? trim($OBJ['note']) : '';
	    $db = new Database();

	    // Block duplicate closing for the same cashier + same date
	    $existing = $db->query("
	        SELECT id FROM cash_closings
	        WHERE user_id = :user_id AND closing_date = :closing_date
	        LIMIT 1
	    ", ['user_id' => $user_id, 'closing_date' => $closing_date]);

	    if(is_array($existing) && count($existing) > 0)
	    {
	        $info['success'] = false;
	        $info['message'] = "You've already closed this date. Contact an admin if this needs correction.";
	        echo json_encode($info);
	        die();
	    }

	    $cash_result = $db->query("
	        SELECT COALESCE(SUM(total),0) AS total
	        FROM sales
	        WHERE user_id = :user_id AND DATE(date) = :closing_date AND payment_method = 'Cash'
	    ", ['user_id' => $user_id, 'closing_date' => $closing_date]);
	    $expected_cash = $cash_result[0]['total'];

	    $mobile_result = $db->query("
	        SELECT COALESCE(SUM(total),0) AS total
	        FROM sales
	        WHERE user_id = :user_id AND DATE(date) = :closing_date AND payment_method = 'Mobile Money'
	    ", ['user_id' => $user_id, 'closing_date' => $closing_date]);
	    $expected_mobile = $mobile_result[0]['total'];

	    $difference = $counted_cash - $expected_cash;

	    $db->query("
	        INSERT INTO cash_closings(user_id, closing_date, expected_cash, expected_mobile, counted_cash, difference, note)
	        VALUES(:user_id, :closing_date, :expected_cash, :expected_mobile, :counted_cash, :difference, :note)
	    ", [
	        'user_id' => $user_id,
	        'closing_date' => $closing_date,
	        'expected_cash' => $expected_cash,
	        'expected_mobile' => $expected_mobile,
	        'counted_cash' => $counted_cash,
	        'difference' => $difference,
	        'note' => $note
	    ]);

	    $info['success'] = true;
	    $info['expected_cash'] = $expected_cash;
	    $info['difference'] = $difference;
	    echo json_encode($info);
	    die();
	}elseif($OBJ['data_type'] == "request_points_otp")
{
	    $phone  = trim($OBJ['phone']);
	    $points = (float)$OBJ['points'];
	    $db = new Database();

	    $customer = $db->query("SELECT * FROM customers WHERE phone = :phone LIMIT 1", ['phone' => $phone]);
	    if(!is_array($customer) || count($customer) == 0)
	    {
	        $info['success'] = false;
	        $info['message'] = "Customer not found";
	        echo json_encode($info);
	        die();
	    }
	    $customer = $customer[0];

	    if($points > $customer['points'])
	    {
	        $info['success'] = false;
	        $info['message'] = "Not enough points. Available: " . $customer['points'];
	        echo json_encode($info);
	        die();
	    }

	    $amount_covered = $points / 50; // 50 points = $1
	    $otp_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
	    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

	    $db->query("
	        INSERT INTO points_otp(phone, otp_code, points_to_use, amount_covered, verified, expires_at)
	        VALUES(:phone, :otp_code, :points, :amount, 0, :expires_at)
	    ", [
	        'phone' => $phone,
	        'otp_code' => $otp_code,
	        'points' => $points,
	        'amount' => $amount_covered,
	        'expires_at' => $expires_at
	    ]);



	   $sms_response = send_sms("243824218304", "Your verification code is: " . $otp_code . ". Valid for 5 minutes.");

	    
	    $info['success'] = true;
	    $info['amount_covered'] = $amount_covered;
	    echo json_encode($info);
	    die();

	}elseif($OBJ['data_type'] == "verify_points_otp")
{
    $phone = trim($OBJ['phone']);
    $code  = trim($OBJ['code']);
    $db = new Database();

    $otp = $db->query("
        SELECT * FROM points_otp
        WHERE phone = :phone AND otp_code = :code AND verified = 0 AND expires_at >= NOW()
        ORDER BY id DESC LIMIT 1
    ", ['phone' => $phone, 'code' => $code]);

    if(!is_array($otp) || count($otp) == 0)
    {
        $info['success'] = false;
        $info['message'] = "Invalid or expired code";
        echo json_encode($info);
        die();
    }
    $otp = $otp[0];

    $db->query("UPDATE points_otp SET verified = 1 WHERE id = :id", ['id' => $otp['id']]);

    $info['success'] = true;
    $info['amount_covered'] = $otp['amount_covered'];
    $info['points_to_use'] = $otp['points_to_use'];
    echo json_encode($info);
    die();
}


 
  }
}
