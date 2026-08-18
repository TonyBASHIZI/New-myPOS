<?php

function sendSMS($clientnum,$total){

          $param1 = $clientnum;
        //   $param2 = "VOTRE COMMANDE EST RECUE NOUS ALLONS VOUS CONTACTER LE TOTAL EST DE: " .$total. "USD";
             $param2 = "TOTAL MONEY COLLECTED  TODAY" .$total. "USD , MERCI";
          
          // Initialize cURL session
          $ch = curl_init();
          
          // URL to call
          $url = "https://api.keccel.com/sms/v1/message.asp?token=K54GTBD3RWUTCUK&from=BIAKUUZA&to=". urlencode($param1) . "&message=". urlencode($param2);
          
          // Set cURL options
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // To return the response as a string
          
          // Execute the cURL request
          $response = curl_exec($ch);
          
          // Check if the request was successful
          if ($response === false) {
          // echo "cURL Error: " . curl_error($ch);
          } else {
          // echo "Response: " . $response;
          }
          
          // Close cURL session
          curl_close($ch);
          
          }




?>