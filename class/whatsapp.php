<?php

function whatsApp($number, $text, $img = '')
{


    $mobile = substr($number, -9);
    $mobile = '94' . (int)$mobile;

    $curl = curl_init();
    curl_setopt_array($curl, array(
     CURLOPT_URL => 'http://api.colorbiz.org/api/whatsapp.php',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>'{
       "mobile_no": "'.$mobile.'",
       "message":"'. $text .'",
       "send_id":"CLOUD ARM",
       "img":"'.$img.'"
   }',
     CURLOPT_HTTPHEADER => array(
       'Content-Type: application/json',
       'Cookie: PHPSESSID=th8bf8ggv4bnjagb09bla4iga2'
     ),
   ));
   
   $response = curl_exec($curl);
   
   curl_close($curl);
   return $response;
}


