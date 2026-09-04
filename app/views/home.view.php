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
	<style>
		.checkout-modal-box {
		    width: 440px;
		    background: #fff;
		    border-radius: 14px;
		    padding: 0;
		    margin: auto;
		    margin-top: 90px;
		    overflow: hidden;
		    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
		}
		.checkout-modal-header {
		    background: linear-gradient(135deg, #2c3e50, #34495e);
		    color: #fff;
		    padding: 18px 24px;
		}
		.checkout-modal-body {
		    padding: 22px 24px;
		}
		.checkout-summary-row {
		    display: flex;
		    justify-content: space-between;
		    font-size: 14px;
		    color: #6c757d;
		    padding: 4px 0;
		}
		.checkout-total-row {
		    display: flex;
		    justify-content: space-between;
		    font-size: 22px;
		    font-weight: 700;
		    color: #27ae60;
		    border-top: 1px dashed #dee2e6;
		    margin-top: 8px;
		    padding-top: 10px;
		}
		.checkout-rate-note {
		    font-size: 12px;
		    color: #856404;
		    background: #fff8e1;
		    border: 1px solid #ffe69c;
		    border-radius: 8px;
		    padding: 8px 12px;
		    margin-top: 14px;
		    display: flex;
		    align-items: center;
		    gap: 8px;
		}
		.checkout-tva-note {
		    font-size: 11px;
		    color: #adb5bd;
		    text-align: right;
		    margin-top: -4px;
		}
</style>
	
	<style>
	@media print {
	    body * {
	        visibility: hidden;
	    }
	    #printable-receipt, #printable-receipt * {
	        visibility: visible;
	    }
	    #printable-receipt {
	        display: block !important;
	        position: absolute;
	        left: 0;
	        top: 0;
	        width: 320px;
	        font-family: 'Courier New', monospace;
	        font-size: 13px;
	    }
	}
	</style>

	
	<div id="printable-receipt" style="display:none;"></div>

	<div class="modal fade" id="customerModal" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content" style="border-radius:12px;">
	      <div class="modal-header">
	        <h5 class="modal-title"><i class="fa fa-star text-warning"></i> Customer Loyalty</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      </div>
	      <div class="modal-body">

	        <label class="form-label small text-muted">Phone Number</label>
	        <div class="input-group mb-3">
	            <input type="text" class="form-control js-customer-phone" placeholder="e.g. 243812345678">
	            <button type="button" class="btn btn-secondary" onclick="lookup_customer()">
	                <i class="fa fa-search"></i> Check
	            </button>
	        </div>

	        <!-- shown when phone is found -->
	        <div class="js-customer-found d-none alert alert-success">
	            <div><b class="js-found-name"></b></div>
	            <div>Current points: <b class="js-found-points"></b></div>
	        </div>

	        <!-- shown when phone is NOT found -->
	        <div class="js-customer-new d-none">
	            <label class="form-label small text-muted">Name</label>
	            <input type="text" class="form-control mb-2 js-customer-name" placeholder="Customer name" onkeyup="if(event.keyCode == 13) register_customer()">
	            <button type="button" class="btn btn-success w-100" onclick="register_customer()">
	                <i class="fa fa-user-plus"></i> Register & Attach
	            </button>
	        </div>

	      </div>
	      <div class="modal-footer">
	       
			        <div class="modal-footer">
			    <button type="button" class="btn btn-outline-danger" onclick="remove_customer_from_sale()">Remove customer</button>
			    <button type="button" class="btn btn-primary" onclick="continue_to_payment()">
			        <i class="fa fa-arrow-right"></i> Continue to Payment
			    </button>
			</div>
	      </div>
	    </div>
	  </div>
</div>
<div class="modal fade" id="printOrderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="border-radius:12px;">
      <div class="modal-body text-center py-4">
        <i class="fa fa-check-circle text-success" style="font-size:40px;"></i>
        <h5 class="mt-2">Order Saved</h5>
        <p class="text-muted small" id="printOrderNo"></p>
        <a href="#" target="_blank" id="printOrderLink" class="btn btn-dark w-100">
            <i class="fa fa-print"></i> Print Order
        </a>
      </div>
    </div>
  </div>
</div>
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

			    <button type="button" class="btn btn-outline-dark py-2 mb-2 w-100" data-bs-toggle="modal" data-bs-target="#customerModal">
				    <i class="fa fa-star"></i> <span class="js-customer-label">Add Customer (Loyalty)</span>
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
	<div role="close-button" onclick="hide_modal(event,'amount-paid')" class="js-amount-paid-modal hide" style="animation: appear .5s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 1050;">

    <div class="checkout-modal-box">

        <div class="checkout-modal-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-cash-register me-2"></i>Checkout</h5>
            <button role="close-button" onclick="hide_modal(event,'amount-paid')" class="btn-close btn-close-white"></button>
        </div>

        <div class="checkout-modal-body">

            <div class="checkout-summary-row">
                <span>Subtotal</span>
                <span class="js-checkout-subtotal">$0.00</span>
            </div>
            <div class="checkout-summary-row">
                <span>TVA (16%)</span>
                <span class="js-checkout-tva">$0.00</span>
            </div>
            <div class="checkout-tva-note">TVA included in total</div>

            <label class="form-label small text-muted mt-3 mb-1">Payment Method</label>
			<select class="form-control js-payment-method">
			    <option value="Cash">Cash</option>
			    <option value="Mobile Money">Mobile Money</option>
			</select>

			<div class="mt-3 p-3" style="background:#f8f9fa;border-radius:8px;" id="pointsPaySection" style="display:none;">
				    <div class="d-flex justify-content-between align-items-center mb-2">
				        <span class="small fw-bold"><i class="fa fa-star text-warning"></i> Pay with Points</span>
				        <span class="small text-muted">Available: <b class="js-available-points">0</b> pts</span>
				    </div>
				    <div class="input-group input-group-sm mb-2">
				        <span class="input-group-text">Points to use</span>
				        <input type="number" class="form-control js-points-to-use" min="0" placeholder="0">
				        <span class="input-group-text js-points-value-preview">= $0.00</span>
				    </div>
				    <button type="button" class="btn btn-sm btn-warning w-100" onclick="request_points_otp()">
				        <i class="fa fa-key"></i> Send OTP to Confirm
				    </button>
				</div>

            <div class="checkout-total-row">
                <span>Total Due</span>
                <span class="js-gtotal-display">$0.00</span>
            </div>

            <label class="form-label small text-muted mt-3 mb-1">Amount Paid</label>
            <input onkeyup="if(event.keyCode == 13)validate_amount_paid(event)" type="text" class="js-amount-paid-input form-control form-control-lg" placeholder="Enter amount paid">

            <div class="checkout-rate-note">
                <i class="fa fa-circle-info"></i>
                <span>Paying in Francs? Use the current exchange rate to convert before entering the amount.</span>
            </div>

        </div>

        <div class="modal-footer">
            <button role="close-button" onclick="hide_modal(event,'amount-paid')" class="btn btn-secondary">Cancel</button>
            <button onclick="validate_amount_paid(event)" class="btn btn-primary">
                <i class="fa fa-check me-1"></i>Validate
            </button>
        </div>

    </div>
</div>
	<!--end enter amount modal-->

	<div class="modal fade" id="otpModal" tabindex="-1">
		  <div class="modal-dialog modal-dialog-centered modal-sm">
		    <div class="modal-content" style="border-radius:12px;">
		      <div class="modal-header">
		        <h5 class="modal-title"><i class="fa fa-key me-2"></i>Enter OTP</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		      </div>
		      <div class="modal-body">
		        <p class="small text-muted mb-2">Points: <b class="js-otp-points"></b> = <b class="js-otp-amount"></b></p>
		        <input type="text" class="form-control text-center" style="letter-spacing:4px;font-size:20px;" maxlength="6" id="otpCodeInput" placeholder="------">
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
		        <button type="button" class="btn btn-primary" onclick="verify_points_otp()">Verify & Apply</button>
		      </div>
		    </div>
		  </div>
		</div>

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

				    if(obj.order_id)
				    {
				        window.open("index.php?pg=order-receipt&id=" + obj.order_id, "_blank");
				    }

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

    if(ITEMS.length == 0)
    {
        show_toast("error", "Empty cart", "Please add products before validating.");
        return false;
    }

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
    
    // Si tout est ok, on ouvre le popup client AVANT le paiement
    var customerModal = new bootstrap.Modal(document.getElementById('customerModal'));
    customerModal.show();
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
		        show_toast("error", "Empty cart", "Please add products first.");
		        return;
		    }
		    var mydiv = document.querySelector(".js-amount-paid-modal");
		    mydiv.classList.remove("hide");

		    var amount_paid_input = mydiv.querySelector(".js-amount-paid-input");
		    amount_paid_input.value = GTOTAL.toFixed(2);

		    var TVA_RATE = 0.16;
		    var subtotal = GTOTAL / (1 + TVA_RATE);
		    var tva_amount = GTOTAL - subtotal;

		    mydiv.querySelector(".js-checkout-subtotal").innerHTML = "$" + subtotal.toFixed(2);
		    mydiv.querySelector(".js-checkout-tva").innerHTML = "$" + tva_amount.toFixed(2);
		    mydiv.querySelector(".js-gtotal-display").innerHTML = "$" + GTOTAL.toFixed(2);

		    
		    refresh_points_section();

		    amount_paid_input.focus();
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
    var amount_input = document.querySelector(".js-amount-paid-input");
    var amount = amount_input.value.trim();
    var sale_date = document.querySelector(".js-sale-date").value;
    
    if(amount == "") {
        alert("Please enter a valid amount");
        amount_input.focus();
        return;
    }

    var amount_due_in_cash = GTOTAL - (POINTS_APPLIED_AMOUNT || 0);

	var diff = amount - amount_due_in_cash;
	var current_balance = 0;
	if(diff >= 0) {
	    CHANGE = diff.toFixed(2);
	    current_balance = 0;
	} else {
	    CHANGE = 0;
	    current_balance = Math.abs(diff).toFixed(2);
	}
    hide_modal(true, 'amount-paid');
    
    if(diff > 0) {
        document.querySelector(".js-change-input").innerHTML = CHANGE;
        show_modal('change');
    }

    var ITEMS_NEW = [];
    for (var i = 0; i < ITEMS.length; i++) {
        var tmp = {};
        tmp.id = ITEMS[i]['id'];
        tmp.qty = ITEMS[i]['qty'];
        ITEMS_NEW.push(tmp);
    }

    var payment_method = document.querySelector(".js-payment-method").value;

    // NEW — inline ajax call that WAITS for the server's response before printing
    var checkout_ajax = new XMLHttpRequest();
    checkout_ajax.addEventListener('readystatechange', function(){
        if(checkout_ajax.readyState == 4 && checkout_ajax.status == 200)
        {
            var checkout_result = JSON.parse(checkout_ajax.responseText);

            print_receipt({
                company: 'My POS',
                amount: amount,
                change: CHANGE,
                balance: current_balance,
                gtotal: GTOTAL,
                data: ITEMS,
                points_used: PENDING_POINTS,
                points_amount: POINTS_APPLIED_AMOUNT,
                customer: checkout_result.customer
            });

            ITEMS = [];
            refresh_items_display();
            amount_input.value = "";
            remove_customer_from_sale();
            PENDING_POINTS = 0;
            POINTS_APPLIED_AMOUNT = 0;

            send_data({
                data_type: "search",
                text: ""
            });
        }
    });
    checkout_ajax.open('post', 'index.php?pg=ajax', true);
    checkout_ajax.send(JSON.stringify({
        data_type: "checkout",
        text: ITEMS_NEW,
        amount_paid: amount,
        balance: current_balance,
        date: sale_date,
        order_id: CURRENT_ORDER_ID,
        customer_phone: CURRENT_CUSTOMER_PHONE,
        payment_method: payment_method,
        points_used: PENDING_POINTS,
        points_amount: POINTS_APPLIED_AMOUNT
    }));
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
    var TVA_RATE = 0.16;
    var subtotal = obj.gtotal / (1 + TVA_RATE);
    var tva = obj.gtotal - subtotal;

    var itemsHtml = '';
    obj.data.forEach(function(item){
        var line_total = item.qty * item.amount;
        itemsHtml += '<tr>'
            + '<td>' + item.description + '</td>'
            + '<td style="text-align:center;">' + item.qty + '</td>'
            + '<td style="text-align:right;">$' + line_total.toFixed(2) + '</td>'
            + '</tr>';
    });

    var balanceHtml = '';
    if(obj.balance && parseFloat(obj.balance) > 0)
    {
        balanceHtml = '<div style="text-align:center;font-weight:bold;border:1px solid #000;padding:4px;margin-top:8px;">'
            + 'BALANCE DUE: $' + parseFloat(obj.balance).toFixed(2) + '</div>';
    }

    var pointsHtml = '';
    if(obj.points_used && obj.points_used > 0)
    {
        pointsHtml = '<div style="display:flex;justify-content:space-between;color:#b8860b;">'
            + '<span>Paid with Points (' + obj.points_used + ' pts)</span>'
            + '<span>-$' + parseFloat(obj.points_amount).toFixed(2) + '</span>'
            + '</div>';
    }

    // ADD THIS BLOCK — this is Point 3, added right here
    var customerHtml = '';
    if(obj.customer)
    {
        customerHtml = `
            <div style="border-bottom:1px dashed #000;padding-bottom:8px;margin-bottom:8px;font-size:11px;">
                <div><b>Customer:</b> ${obj.customer.name}</div>
                <div><b>Phone:</b> ${obj.customer.phone}</div>
                <div><b>Points Balance:</b> ${parseFloat(obj.customer.points).toFixed(2)} pts</div>
            </div>
        `;
    }
    // END ADDED BLOCK

    var html = `
        <div style="text-align:center;border-bottom:2px dashed #000;padding-bottom:10px;margin-bottom:10px;">
            <h2 style="margin:0;">${obj.company}</h2>
            <div style="font-size:11px;">${new Date().toLocaleString()}</div>
            <div style="font-size:11px;">Receipt #${obj.receipt_no || '-'}</div>
        </div>

        ${customerHtml}

        <table style="width:100%;border-collapse:collapse;margin-bottom:10px;">
            <thead>
                <tr style="border-bottom:1px solid #000;">
                    <th style="text-align:left;font-size:11px;">Item</th>
                    <th style="text-align:center;font-size:11px;">Qty</th>
                    <th style="text-align:right;font-size:11px;">Total</th>
                </tr>
            </thead>
            <tbody>${itemsHtml}</tbody>
        </table>

        <div style="border-top:1px dashed #000;padding-top:6px;font-size:12px;">
            <div style="display:flex;justify-content:space-between;">
                <span>Subtotal</span><span>$${subtotal.toFixed(2)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span>TVA (16%)</span><span>$${tva.toFixed(2)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:bold;border-top:1px solid #000;margin-top:6px;padding-top:6px;">
                <span>TOTAL</span><span>$${parseFloat(obj.gtotal).toFixed(2)}</span>
            </div>
        </div>

        <div style="border-top:1px dashed #000;margin-top:8px;padding-top:8px;font-size:12px;">
            ${pointsHtml}
            <div style="display:flex;justify-content:space-between;">
                <span>Amount Paid</span><span>$${parseFloat(obj.amount).toFixed(2)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span>Change</span><span>$${parseFloat(obj.change).toFixed(2)}</span>
            </div>
        </div>

        ${balanceHtml}

        ${customerHtml}

        <div style="text-align:center;margin-top:14px;padding-top:10px;border-top:2px dashed #000;font-size:11px;">
            Thank you for your purchase!
        </div>
    `;

    document.getElementById('printable-receipt').innerHTML = html;
    window.print();
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

	<script>
		var CURRENT_CUSTOMER_PHONE = null;
var CURRENT_CUSTOMER_NAME  = null;

function lookup_customer()
{
    var phone = document.querySelector(".js-customer-phone").value.trim();
    if(phone == "")
    {
        show_toast("error", "Missing phone", "Please enter a phone number.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);

            if(obj.found)
            {
                CURRENT_CUSTOMER_PHONE = phone;
                CURRENT_CUSTOMER_NAME  = obj.name;

                document.querySelector(".js-found-name").innerHTML = obj.name;
                document.querySelector(".js-found-points").innerHTML = parseFloat(obj.points).toFixed(2) + " pts";
                document.querySelector(".js-customer-found").classList.remove("d-none");
                document.querySelector(".js-customer-new").classList.add("d-none");

                update_customer_label();
            }else{
                document.querySelector(".js-customer-found").classList.add("d-none");
                document.querySelector(".js-customer-new").classList.remove("d-none");
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({ data_type: "lookup_customer", phone: phone }));
}

function register_customer()
{
    var phone = document.querySelector(".js-customer-phone").value.trim();
    var name  = document.querySelector(".js-customer-name").value.trim();

    if(phone == "" || name == "")
    {
        show_toast("error", "Missing info", "Phone and name are both required.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);

            if(obj.success)
            {
                CURRENT_CUSTOMER_PHONE = phone;
                CURRENT_CUSTOMER_NAME  = name;
                update_customer_label();
                show_toast("success", "Customer Registered", name + " added to loyalty program.");

                var modalEl = document.getElementById('customerModal');
                bootstrap.Modal.getInstance(modalEl).hide();
            }else{
                show_toast("error", "Registration failed", obj.message || "Please try again.");
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({ data_type: "register_customer", phone: phone, name: name }));
}

function remove_customer_from_sale()
{
    CURRENT_CUSTOMER_PHONE = null;
    CURRENT_CUSTOMER_NAME  = null;
    update_customer_label();
    document.querySelector(".js-customer-phone").value = "";
    document.querySelector(".js-customer-name").value = "";
    document.querySelector(".js-customer-found").classList.add("d-none");
    document.querySelector(".js-customer-new").classList.add("d-none");
}

function update_customer_label()
{
    var label = document.querySelector(".js-customer-label");
    label.innerHTML = CURRENT_CUSTOMER_PHONE
        ? CURRENT_CUSTOMER_NAME + " (" + CURRENT_CUSTOMER_PHONE + ")"
        : "Add Customer (Loyalty)";
}
	
function continue_to_payment()
{
    var customerModalEl = document.getElementById('customerModal');
    var customerModal = bootstrap.Modal.getInstance(customerModalEl);
    if(customerModal) customerModal.hide();

    // wait for the modal's fade-out transition before opening the next one
    setTimeout(function(){
        show_modal('amount-paid');
    }, 300);
}

function remove_customer_from_sale()
{
    CURRENT_CUSTOMER_PHONE = null;
    CURRENT_CUSTOMER_NAME  = null;
    update_customer_label();
    document.querySelector(".js-customer-phone").value = "";
    document.querySelector(".js-customer-name").value = "";
    document.querySelector(".js-customer-found").classList.add("d-none");
    document.querySelector(".js-customer-new").classList.add("d-none");
}

// call this whenever the checkout modal opens (inside show_modal('amount-paid'))
function refresh_points_section()
{
    var section = document.getElementById('pointsPaySection');

    if(CURRENT_CUSTOMER_PHONE)
    {
        section.style.display = 'block';

        var ajax = new XMLHttpRequest();
        ajax.addEventListener('readystatechange', function(){
            if(ajax.readyState == 4 && ajax.status == 200)
            {
                var obj = JSON.parse(ajax.responseText);
                var points = obj.found ? parseFloat(obj.points) : 0;

                document.querySelector(".js-available-points").innerText = points.toFixed(2);

                var pointsInput = document.querySelector(".js-points-to-use");
                pointsInput.value = points;

                // trigger the $ preview update manually, since setting .value doesn't fire 'input'
                var dollarValue = points / 50;
                document.querySelector(".js-points-value-preview").innerText = "= $" + dollarValue.toFixed(2);


            }
        });
        ajax.open('post', 'index.php?pg=ajax', true);
        ajax.send(JSON.stringify({ data_type: "lookup_customer", phone: CURRENT_CUSTOMER_PHONE }));
    }else{
        section.style.display = 'none';
    }
}

document.querySelector(".js-points-to-use").addEventListener('input', function(){
    var points = parseFloat(this.value) || 0;
    var dollarValue = points / 50; // 50 points = $1
    document.querySelector(".js-points-value-preview").innerText = "= $" + dollarValue.toFixed(2);
});

var PENDING_POINTS = 0;
var PENDING_POINTS_AMOUNT = 0;

function show_otp_input_modal(points, amount)
{
    PENDING_POINTS = points;
    PENDING_POINTS_AMOUNT = amount;
    document.querySelector(".js-otp-points").innerText = points;
    document.querySelector(".js-otp-amount").innerText = "$" + parseFloat(amount).toFixed(2);
    document.getElementById('otpCodeInput').value = "";

    var modal = new bootstrap.Modal(document.getElementById('otpModal'));
    modal.show();
}

var POINTS_APPLIED_AMOUNT = 0; // this reduces what's owed in cash at checkout

function verify_points_otp()
{
    var code = document.getElementById('otpCodeInput').value.trim();

    if(code.length != 6)
    {
        show_toast("error", "Invalid code", "Enter the 6-digit code.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                POINTS_APPLIED_AMOUNT = obj.amount_covered;
                show_toast("success", "Points Applied", "$" + parseFloat(obj.amount_covered).toFixed(2) + " covered by points.");
                bootstrap.Modal.getInstance(document.getElementById('otpModal')).hide();

                // reduce the amount-paid input by the covered amount, so cashier only collects the remainder
                var remaining = Math.max(0, GTOTAL - POINTS_APPLIED_AMOUNT);
                document.querySelector(".js-amount-paid-input").value = remaining.toFixed(2);
            }else{
                show_toast("error", "Verification Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "verify_points_otp",
        phone: CURRENT_CUSTOMER_PHONE,
        code: code
    }));
}



var POINTS_OTP_PENDING = false;

function request_points_otp()
{
    var points = parseFloat(document.querySelector(".js-points-to-use").value) || 0;

    if(!CURRENT_CUSTOMER_PHONE)
    {
        show_toast("error", "No customer", "Attach a customer to the sale first.");
        return;
    }
    if(points <= 0)
    {
        show_toast("error", "Invalid amount", "Enter how many points to use.");
        return;
    }

    var ajax = new XMLHttpRequest();
    ajax.addEventListener('readystatechange', function(){
        if(ajax.readyState == 4 && ajax.status == 200)
        {
            var obj = JSON.parse(ajax.responseText);
            if(obj.success)
            {
                POINTS_OTP_PENDING = true;
                show_toast("success", "OTP Sent", "Ask the customer for the code sent to their phone.");
                show_otp_input_modal(points, obj.amount_covered);
            }else{
                show_toast("error", "Failed", obj.message);
            }
        }
    });
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.send(JSON.stringify({
        data_type: "request_points_otp",
        phone: CURRENT_CUSTOMER_PHONE,
        points: points
    }));
}

 document.querySelector(".js-customer-phone").addEventListener('keyup', function(e){
    if(e.keyCode == 13)
    {
        var foundBox = document.querySelector(".js-customer-found");
        if(!foundBox.classList.contains('d-none'))
        {
            // customer already found from a previous Enter — this Enter continues
            continue_to_payment();
        }else{
            // not found yet — this Enter does the lookup
            lookup_customer();
        }
    }
});

 function show_print_order_prompt(order_id, order_no)
{
    document.getElementById('printOrderNo').innerText = "Order #" + order_no;
    document.getElementById('printOrderLink').href = "index.php?pg=order-receipt&id=" + order_id;

    var modal = new bootstrap.Modal(document.getElementById('printOrderModal'));
    modal.show();
}


</script>

<?php require views_path('partials/footer');?>
