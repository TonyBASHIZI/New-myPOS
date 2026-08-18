<?php require views_path('partials/header');?>

	<style>
		
		.hide{
			display: none;
		}

		@keyframes appear{

			0%{opacity: 0;transform: translateY(-100px);}
			100%{opacity: 1;transform: translateY(0px);}
		}

	</style>
	<div class="d-flex">
		<div style="min-height:600px;" class="shadow-sm col-7 p-4">
			
			<div class="input-group mb-3"><h3> Products </h3>
			  <input onkeyup="check_for_enter_key(event)" oninput="search_item(event)" type="text" class="ms-4 form-control js-search" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1" autofocus>
			  <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
			</div>

			<div onclick="add_item(event)" class="js-products d-flex" style="flex-wrap: wrap;height: 90%;overflow-y: scroll;">
				
				
			</div>
		</div>
		

		<div class="col-5 bg-light p-4 pt-2">
			
			<div><center><h3>Cart <div class="js-item-count badge bg-primary rounded-circle">0</div></h3></center></div>
			
			<div class="table-responsive" style="height:400px;overflow-y: scroll;">
				<div class="input-group mb-3">
				    <input type="number" class="form-control js-order-id-input" placeholder="Order ID">
				    <button type="button" onclick="load_order()" class="btn btn-secondary">
				        <i class="fa fa-arrow-right"></i> Go
				    </button>
				</div>
				<table class="table table-striped table-hover">
					<tr>
						<th>Image</th><th>Description</th><th>Prices</th>
					</tr>
					
					<tbody class="js-items">

  	 			 
	 				</tbody>
				</table>
			</div>

			<div class="js-gtotal alert alert-danger" style="font-size:30px">Total amount : $0.00</div>
			<div class="">
				<!-- <button onclick="show_modal('amount-paid')" class="btn btn-success my-2 w-100 py-4">Validate sales</button> -->
				<div class="d-grid gap-2">

			    <button onclick="validate_cart_before_payment()" class="btn btn-success py-3">
			        <i class="fa fa-shopping-cart"></i> Validate Sales
			    </button>

			    <button onclick="save_order()" class="btn btn-warning py-3">
			        <i class="fa fa-clipboard-list"></i> Save Order
			    </button>

			    <button onclick="clear_all()" class="btn btn-primary">
			        Clean
			    </button>

			</div>
			</div>
			<div class="mb-3">
    <label for="sale-date" class="form-label">Sales Date :</label>
    <input type="date" id="sale-date" class="form-control js-sale-date">
</div>

		</div>
	</div>	

<!--modals-->

	<!--enter amount modal-->
	<div role="close-button" onclick="hide_modal(event,'amount-paid')" class="js-amount-paid-modal hide" style="animation: appear .5s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 4;">

		<div style="width:500px;min-height:200px;background-color:white;padding:10px;margin:auto;margin-top:100px">
			<h4>Checkout <button role="close-button" onclick="hide_modal(event,'amount-paid')" class="btn btn-danger float-end p-0 px-2">X</button></h4>
			<br>
			<input onkeyup="if(event.keyCode == 13)validate_amount_paid(event)"  type="text" class="js-amount-paid-input form-control" placeholder="Enter amount paid">
			<br>
			<button role="close-button" onclick="hide_modal(event,'amount-paid')"  class="btn btn-secondary">Cancel</button>
			<button onclick="validate_amount_paid(event)" class="btn btn-primary float-end">Validate</button>
		</div>
	</div>
	<!--end enter amount modal-->

	<!--change modal-->
	<div role="close-button" onclick="hide_modal(event,'change')" class="js-change-modal hide" style="animation: appear .5s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 1050;">

		<div style="width:500px;min-height:200px;background-color:white;padding:10px;margin:auto;margin-top:100px">
			<h4>Change: <button role="close-button" onclick="hide_modal(event,'change')" class="btn btn-danger float-end p-0 px-2">X</button></h4>
			<br>
			<div class="js-change-input form-control text-center" style="font-size:60px">0.00</div>
			<br>
			<center><button role="close-button" onclick="hide_modal(event,'change')" class="js-btn-close-change btn btn-lg btn-secondary">Continue</button></center>
		</div>
	</div>
	<!--end change modal-->

	
<!--end modals-->

<!--toast notification-->
<div class="js-toast hide" style="animation: appear .4s ease; position: fixed; top: 20px; right: 20px; z-index: 1060; min-width: 320px; max-width: 400px;">
    <div class="js-toast-box shadow-lg rounded p-3 d-flex align-items-start" style="background-color: white; border-left: 6px solid #28a745;">
        <div class="js-toast-icon me-3" style="font-size: 28px; color: #28a745;">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="flex-grow-1">
            <div class="js-toast-title fw-bold" style="font-size: 15px;">Success</div>
            <div class="js-toast-message text-muted" style="font-size: 13px;"></div>
        </div>
        <button role="close-button" onclick="hide_toast()" class="btn-close ms-2"></button>
    </div>
</div>
<!--end toast notification-->

<!--confirm modal-->
<div role="close-button" onclick="hide_modal(event,'confirm')" class="js-confirm-modal hide" style="animation: appear .3s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 1050;">

    <div style="width:420px;min-height:150px;background-color:white;padding:20px;margin:auto;margin-top:150px;border-radius:6px;">
        <div class="js-confirm-message" style="font-size:16px;margin-bottom:20px;"></div>
        <div class="text-end">
            <button role="close-button" onclick="hide_modal(event,'confirm')" class="btn btn-secondary me-2">Cancel</button>
            <button onclick="confirm_yes()" class="btn btn-warning">Confirm</button>
        </div>
    </div>
</div>
<!--end confirm modal-->

<script>
		function load_order()
	{
	    var order_id = document.querySelector(".js-order-id-input").value.trim();

	    if(order_id == "" || isNaN(order_id))
	    {
	        show_toast("error", "Invalid ID", "Please enter a valid numeric order id.");
	        return;
	    }

	    send_data({
	        data_type: "load_order",
	        order_id: order_id
	    });
	}
</script>

<script>
	var CONFIRM_CALLBACK = null;

function show_confirm(message, callback)
{
    CONFIRM_CALLBACK = callback;
    var mydiv = document.querySelector(".js-confirm-modal");
    mydiv.querySelector(".js-confirm-message").innerHTML = message;
    mydiv.classList.remove("hide");
}

function confirm_yes()
{
    var mydiv = document.querySelector(".js-confirm-modal");
    mydiv.classList.add("hide");

    if(typeof CONFIRM_CALLBACK == "function")
    {
        CONFIRM_CALLBACK();
    }
    CONFIRM_CALLBACK = null;
}
</script>

<script>
	var TOAST_TIMEOUT = null;

function show_toast(type, title, message)
{
    var toast = document.querySelector(".js-toast");
    var box   = toast.querySelector(".js-toast-box");
    var icon  = toast.querySelector(".js-toast-icon");

    toast.querySelector(".js-toast-title").innerHTML = title;
    toast.querySelector(".js-toast-message").innerHTML = message;

    if(type == "error")
    {
        box.style.borderLeftColor = "#dc3545";
        icon.style.color = "#dc3545";
        icon.innerHTML = '<i class="fa fa-times-circle"></i>';
    }else{
        box.style.borderLeftColor = "#28a745";
        icon.style.color = "#28a745";
        icon.innerHTML = '<i class="fa fa-check-circle"></i>';
    }

    toast.classList.remove("hide");
    toast.style.animation = "appear .4s ease";

    clearTimeout(TOAST_TIMEOUT);
    TOAST_TIMEOUT = setTimeout(hide_toast, 3500);
}

function hide_toast()
{
    var toast = document.querySelector(".js-toast");
    toast.style.animation = "disappear .4s ease";
    setTimeout(function(){
        toast.classList.add("hide");
    }, 350);
}
</script>

<script>

	// Définit la date du jour par défaut au chargement
window.onload = function() {
    var today = new Date().toISOString().split('T')[0];
    document.querySelector(".js-sale-date").value = today;
}

	
	var PRODUCTS 	= [];
	var ITEMS 		= [];
	var BARCODE 	= false;
	var GTOTAL  	= 0;
	var CHANGE  	= 0;
	var RECEIPT_WINDOW = null;
	var BALANCE = 0; 
	var CURRENT_ORDER_ID = null;
	var main_input = document.querySelector(".js-search");

	function search_item(e){

		var text = e.target.value.trim();
	 
		var data = {};
		data.data_type = "search";
		data.text = text;

		send_data(data);
	}

	function send_data(data)
	{

		var ajax = new XMLHttpRequest();

		ajax.addEventListener('readystatechange',function(e){

			if(ajax.readyState == 4){

				
				if(ajax.status == 200)
				{
					if(ajax.responseText.trim() != ""){
						handle_result(ajax.responseText);
					}else{
						if(BARCODE){
							alert("that item was not found");
						}
					}
				}else{

					console.log("An error occured. Err Code:"+ajax.status+" Err message:"+ajax.statusText);
					console.log(ajax);
				}

				//clear main input if enter was pressed
				if(BARCODE){
					main_input.value = "";
					main_input.focus();
				}

				BARCODE = false;

			}
			
		});

		ajax.open('post','index.php?pg=ajax',true);
		ajax.send(JSON.stringify(data));
	}

	function handle_result(result){
		
		//console.log(result);
		var obj = JSON.parse(result);
		if(typeof obj != "undefined"){
			//valid json
			if(obj.data_type == "search")
			{
				var mydiv = document.querySelector(".js-products");
				mydiv.innerHTML = "";
				PRODUCTS = [];
				var mydiv = document.querySelector(".js-products");
				if(obj.data != "")
				{
					
					PRODUCTS = obj.data;
					for (var i = 0; i < obj.data.length; i++) {
						
						mydiv.innerHTML += product_html(obj.data[i],i);
					}
					if(BARCODE && PRODUCTS.length == 1){
						add_item_from_index(0);
					}
				}
			}else if(obj.data_type == "save_order")
				{
				    show_toast("success", "Order Saved", obj.data + " — Order No: " + obj.order_no);
				    ITEMS = [];
				    refresh_items_display();
				    send_data({
				        data_type: "search",
				        text: ""
				    });
				}else if(obj.data_type == "load_order")
				{
				    if(!obj.data || obj.data.length == 0)
				    {
				        show_toast("error", "Order Not Found", "No items found for that order id.");
				        return;
				    }

				    CURRENT_ORDER_ID = obj.order_id;   // <-- ADD THIS LINE

				    for (var i = 0; i < obj.data.length; i++) {
				        var row = obj.data[i];
				        var productInStock = PRODUCTS.find(p => p.id == row.product_id);

				        if(!productInStock) continue;

				        var existing = ITEMS.find(it => it.id == productInStock.id);
				        if(existing)
				        {
				            existing.qty += parseFloat(row.qty);
				        }else{
				            var temp = JSON.parse(JSON.stringify(productInStock));
				            temp.qty = parseFloat(row.qty);
				            ITEMS.push(temp);
				        }
				    }

				    refresh_items_display();
				    show_toast("success", "Order Loaded", "Order #" + obj.order_no + " added to cart.");
				    document.querySelector(".js-order-id-input").value = "";
				}
							
		}
	}


	function validate_cart_before_payment() {
    
    for (var i = 0; i < ITEMS.length; i++) {
        // On cherche le produit correspondant dans la liste globale PRODUCTS
        // (Ou on compare avec la propriété max_qty si tu l'as stockée)
        let productInStock = PRODUCTS.find(p => p.id == ITEMS[i].id);

        if (productInStock) {
            if (ITEMS[i].qty > productInStock.qty) {
                alert("Error Stock : '" + ITEMS[i].description + "' Stock available is " + productInStock.qty + " Item but you put Qty " + ITEMS[i].qty + " in cart, edit Qty and try again");
                
                return false; // Bloque la validation
            }
        }
    }
    
    // Si tout est ok, on affiche le modal de paiement
    show_modal('amount-paid');
}

function product_html(data, index) {
    // 1. Logique du Shop
    var shopID = data.shop ? data.shop : "0";
    let badgeColor = "bg-secondary"; 
    
    if(data.shop == 1) badgeColor = "bg-primary"; 
    if(data.shop == 2) badgeColor = "bg-success"; 
    if(data.shop == 3) badgeColor = "bg-warning text-dark"; 
    if(data.shop == 4) badgeColor = "bg-danger";  
    if(data.shop == 5) badgeColor = "bg-info text-dark"; 

    // 2. Logique du Badge de Stock (on définit qtyBadge ici)
    // Initialisation par défaut (si aucune condition n'est remplie)
let qtyBadge = "bg-secondary"; 

if (data.qty <= 5) {
    // Produits <= 5 en rouge
    qtyBadge = "bg-danger fw-bold"; 
} else if (data.qty >= 1 && data.qty <= 9) {
    // Produits entre 1 et 9 en jaune (Note: le <=5 passera en rouge avant)
    qtyBadge = "bg-warning text-dark fw-bold"; 
} else if (data.qty >= 10 && data.qty <= 30) {
    // Produits >= 10 et <= 30 en bleu
    qtyBadge = "bg-primary"; 
} else if (data.qty > 30) {
    // Produits > 30 en vert
    qtyBadge = "bg-success"; 
}


    return `
        <!--card-->
        <div class="card m-2 border-0 mx-auto shadow-sm" style="min-width: 190px; max-width: 190px; position: relative;">
            <a href="#">
                <img index="${index}" src="${data.image}" class="w-100 rounded border shadow-sm">
            </a>

            <!-- Badge Shop en haut à droite -->
            <span class="badge ${badgeColor} position-absolute" style="top: 8px; right: 8px; font-size: 11px; z-index: 10;">
                Shop: ${shopID}
            </span>

            <div class="p-2">
                <div class="text-muted" style="font-size: 14px; height: 38px; overflow: hidden;">${data.description}</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <b style="font-size:18px">$${data.amount}</b>
                     
                    <!-- Badge de Stock Dynamique -->
                    <span class="badge ${qtyBadge} shadow-sm" style="font-size: 11px; padding: 5px 8px;">
                        <i class="fa fa-boxes"></i> Stock: ${data.qty}
                    </span>
                </div>
            </div>
        </div>
        <!--end card-->
    `;
}


	function item_html(data,index)
	{

		return `
			<!--item-->
			<tr>
				<td style="width:110px"><img src="${data.image}" class="rounded border" style="width:100px;height:100px"></td>
				<td class="text-primary">
					${data.description}

					<div class="input-group my-3" style="max-width:150px">
					  <span index="${index}" onclick="change_qty('down',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-primary"></i></span>
					  <input index="${index}" onblur="change_qty('input',event)" type="text" class="form-control text-primary" placeholder="1" value="${data.qty}" >
					  <span index="${index}" onclick="change_qty('up',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-primary"></i></span>
					</div>

				</td>
				<td style="font-size:20px">
					<b>$${data.amount}</b>
					<button onclick="clear_item(${index})" class="float-end btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
				</td>
			</tr>
			<!--end item-->
			`;
				
	}

	
		function add_item_from_index(index) {
    // 1. Vérifier si le produit est déjà en rupture avant même de commencer
    if(PRODUCTS[index].qty <= 0) {
        alert("Désolé, ce produit est en rupture de stock !");
        return;
    }

    // 2. Vérifier si l'article existe déjà dans le panier (ITEMS)
    for (var i = ITEMS.length - 1; i >= 0; i--) {
        if(ITEMS[i].id == PRODUCTS[index].id) {
            
            // VERIFICATION : Est-ce que l'ajout dépasse le stock disponible ?
            if(ITEMS[i].qty + 1 > PRODUCTS[index].qty) {
                alert("Action impossible : Stock maximum atteint (" + PRODUCTS[index].qty + " dispos).");
                return;
            }

            ITEMS[i].qty += 1;
            refresh_items_display();
            return;
        }
    }

    // 3. Si c'est un nouvel ajout dans le panier
    var temp = JSON.parse(JSON.stringify(PRODUCTS[index])); // On clone l'objet
    temp.qty = 1; // On commence à 1 dans le panier

    ITEMS.push(temp);
    refresh_items_display();
}

	function add_item(e)
	{

		if(e.target.tagName == "IMG"){
			var index = e.target.getAttribute("index");

			add_item_from_index(index);
		}
	}

	function refresh_items_display()
	{

		var item_count = document.querySelector(".js-item-count");
		item_count.innerHTML = ITEMS.length;

		var items_div = document.querySelector(".js-items");
		items_div.innerHTML = "";
		var grand_total = 0;

		for (var i = ITEMS.length - 1; i >= 0; i--) {

			items_div.innerHTML += item_html(ITEMS[i],i);
			grand_total += (ITEMS[i].qty * ITEMS[i].amount);
		}
		
		var gtotal_div = document.querySelector(".js-gtotal");
		gtotal_div.innerHTML = "Total: $" + grand_total.toFixed(2);
		GTOTAL = grand_total;

	}

	function clear_all()
	{

		if(!confirm("Etes vous sure de vouloir supprimer les produits du pannier??!!"))
			return;

		ITEMS = [];
		refresh_items_display();

	}
	
	function clear_item(index)
	{

		if(!confirm("Remove item??!!"))
			return;

		ITEMS.splice(index,1);
		refresh_items_display();

	}

	function change_qty(direction,e)
	{

		var index = e.currentTarget.getAttribute("index");
		if(direction == "up")
		{
			ITEMS[index].qty += 1;
		}else
		if(direction == "down")
		{
			ITEMS[index].qty -= 1;
		}else{

			ITEMS[index].qty = parseInt(e.currentTarget.value);
		}

		//make sure its not less than 1
		if(ITEMS[index].qty < 1)
		{
			ITEMS[index].qty = 1;
		}

		refresh_items_display();
	}

	function check_for_enter_key(e)
	{

		if(e.keyCode == 13)
		{
			BARCODE = true;
			search_item(e);
		}
	}

	function show_modal(modal)
	{

		if(modal == "amount-paid"){

			if(ITEMS.length == 0){

				alert("Veuiller ajouter un produit dans le pannier");
				return;
			}
			var mydiv = document.querySelector(".js-amount-paid-modal");
			mydiv.classList.remove("hide");

			var amount_paid_input = mydiv.querySelector(".js-amount-paid-input");
            amount_paid_input.value = GTOTAL.toFixed(2); // Affiche le total formaté

			amount_paid_input.focus();
		}else
		if(modal == "change"){
 
			var mydiv = document.querySelector(".js-change-modal");
			mydiv.classList.remove("hide");

			mydiv.querySelector(".js-change-input").innerHTML = CHANGE;
			mydiv.querySelector(".js-btn-close-change").focus();
		}
		

	}
	
	function hide_modal(e,modal)
{
    if(e == true || e.target.getAttribute("role") == "close-button")
    {
        if(modal == "amount-paid"){
            document.querySelector(".js-amount-paid-modal").classList.add("hide");
        }else
        if(modal == "change"){
            document.querySelector(".js-change-modal").classList.add("hide");
        }else
        if(modal == "confirm"){
            document.querySelector(".js-confirm-modal").classList.add("hide");
            CONFIRM_CALLBACK = null; // cancel clears the pending action
        }
    }
}

function validate_amount_paid(e) {
    var amount_input = e.currentTarget.parentNode.querySelector(".js-amount-paid-input");
    var amount = amount_input.value.trim();
	var sale_date = document.querySelector(".js-sale-date").value;
    
    if(amount == "") {
        alert("Please enter a valid amount");
        amount_input.focus();
        return;
    }

    amount = parseFloat(amount);
    
    // Calcul du Rendu (CHANGE) et de la Dette (BALANCE)
    var diff = amount - GTOTAL;
    var current_balance = 0;

    if(diff >= 0) {
        // Le client a payé assez ou plus
        CHANGE = diff.toFixed(2);
        current_balance = 0;
    } else {
        // Le client n'a pas assez payé : on crée une balance positive
        CHANGE = 0;
        current_balance = Math.abs(diff).toFixed(2); // Le montant manquant (ex: 1$)
    }

    hide_modal(true, 'amount-paid');
    
    // On ne montre le modal "Change" que s'il y a de la monnaie à rendre
    if(diff > 0) {
        document.querySelector(".js-change-input").innerHTML = CHANGE;
        show_modal('change');
    }

    // Préparation des données simplifiées
    var ITEMS_NEW = [];
    for (var i = 0; i < ITEMS.length; i++) {
        var tmp = {};
        tmp.id = ITEMS[i]['id'];
        tmp.qty = ITEMS[i]['qty'];
        ITEMS_NEW.push(tmp);
    }

    // Envoi des données au PHP (Ajout de balance)
    send_data({
    data_type: "checkout",
    text: ITEMS_NEW,
    amount_paid: amount,
    balance: current_balance,
    date: sale_date,
    order_id: CURRENT_ORDER_ID
});

  // REMOVE this whole save_order() block from inside validate_amount_paid()


    // Impression du ticket (On affiche la balance sur le reçu)
    print_receipt({
        company: 'My POS',
        amount: amount,
        change: CHANGE,
        balance: current_balance,
        gtotal: GTOTAL,
        data: ITEMS
    });

    // Nettoyage
    ITEMS = [];
    refresh_items_display();
    amount_input.value = "";

    // Recharger les produits
    send_data({
        data_type: "search",
        text: ""
    });
}
// ADD it as its own top-level function, e.g. right after validate_amount_paid's closing brace:
function save_order()
{
    if(ITEMS.length == 0)
    {
        show_toast("error", "Empty cart", "Please add products first.");
        return;
    }

    for(var i = 0; i < ITEMS.length; i++)
    {
        let productInStock = PRODUCTS.find(p => p.id == ITEMS[i].id);
        if(productInStock && ITEMS[i].qty > productInStock.qty)
        {
            show_toast("error", "Stock issue", "Insufficient stock for: " + ITEMS[i].description);
            return;
        }
    }

    show_confirm("Save this order?", function(){

        var sale_date = document.querySelector(".js-sale-date").value;

        var ITEMS_NEW = [];
        for(var i = 0; i < ITEMS.length; i++)
        {
            ITEMS_NEW.push({
                id: ITEMS[i].id,
                qty: ITEMS[i].qty
            });
        }

        send_data({
            data_type: "save_order",
            text: ITEMS_NEW,
            date: sale_date
        });
    });
}


	function print_receipt(obj)
	{
		var vars = JSON.stringify(obj);

		RECEIPT_WINDOW = window.open('index.php?pg=print&vars='+vars,'printpage',"width=500px;");

		setTimeout(close_receipt_window,2000);
		
	}
 
 	function close_receipt_window()
 	{
 		RECEIPT_WINDOW.close();
 	}

	send_data({

		data_type:"search",
		text:""
	});

	function finaliser_la_vente() {
    
    var data = {};
    data.data_type = "checkout";
    data.text = ITEMS; // Ton tableau d'articles du panier

    // On envoie les données au serveur
    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function() {
        if(ajax.readyState == 4 && ajax.status == 200) {
            var obj = JSON.parse(ajax.responseText);
            if(obj.data_type == "checkout") {
                alert("Vente enregistrée avec succès !");
                clear_all(); // Vide le panier
                location.reload(); // Rafraîchit pour mettre à jour les stocks
            }
        }
    });

    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify(data));
}



</script>

<?php require views_path('partials/footer');?>
