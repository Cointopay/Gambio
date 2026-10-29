<?php
require('includes/application_top.php');

$token = MODULE_PAYMENT_COINTOPAY_CALLBACK_SECRET;
if ($token == '' || $_GET['token'] != $token){
    echo 'Token: ' . $_GET['token'] . ' do not match';exit;
}

$order_id = $_REQUEST['CustomerReferenceNr'];
$gkey = MODULE_PAYMENT_COINTOPAY_MERCHANT_ID;
$back_url  = xtc_href_link('index');
if(isset($_GET['ConfirmCode']))
{
    $data = [
        'mid' => $gkey,
        'TransactionID' =>  $_GET['TransactionID'] ,
        'ConfirmCode' => $_GET['ConfirmCode']
    ];
	$transactionData = fn_cointopay_transactiondetail($data);
	if(200 !== $transactionData['status_code']){
		echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">'. $transactionData['message'].'</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
	}
	else{
		if($transactionData['data']['Security'] != $_GET['ConfirmCode']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! ConfirmCode doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif($transactionData['data']['CustomerReferenceNr'] != $_GET['CustomerReferenceNr']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! CustomerReferenceNr doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif($transactionData['data']['TransactionID'] != $_GET['TransactionID']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! TransactionID doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif(isset($_GET['AltCoinID']) && $transactionData['data']['AltCoinID'] != $_GET['AltCoinID']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! AltCoinID doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif(isset($_GET['MerchantID']) && $transactionData['data']['MerchantID'] != $_GET['MerchantID']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! MerchantID doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif(isset($_GET['CoinAddressUsed']) && $transactionData['data']['coinAddress'] != $_GET['CoinAddressUsed']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! coinAddress doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif(isset($_GET['SecurityCode']) && $transactionData['data']['SecurityCode'] != $_GET['SecurityCode']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! SecurityCode doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif(isset($_GET['inputCurrency']) && $transactionData['data']['inputCurrency'] != $_GET['inputCurrency']){
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! inputCurrency doesn\'t match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		elseif (
		 $transactionData['data']['Status'] !== $_REQUEST['status']
		&& !isset($_REQUEST['cancel'])
		&& !(
			strtolower($_REQUEST['status']) === 'paid'
			&& isset($_REQUEST['notenough'])
			&& $_REQUEST['notenough'] == 1
		)
		&& !(
			strtolower( $transactionData['data']['Status']) === 'expired'
			&& strtolower($_REQUEST['status']) === 'failed'
		)
	) {
			echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Data mismatch! status doesn\'t match. Your order status is '.$transactionData['data']['Status'].'</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}
		
	}
    $response = validateOrder($data);
    if(is_string($response)) {
		echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">'.$response.'</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
	}
    if (
    $response['Status'] !== $_REQUEST['status']
    && !isset($_REQUEST['cancel'])
    && !(
        strtolower($_REQUEST['status']) === 'paid'
        && isset($_REQUEST['notenough'])
        && $_REQUEST['notenough'] == 1
    )
    && !(
        strtolower($response['Status']) === 'expired'
        && strtolower($_REQUEST['status']) === 'failed'
    )
) {
        echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">We have detected different order status. Your order status is '.$response['Status'].'</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
    }

    if($response['CustomerReferenceNr'] == $_REQUEST['CustomerReferenceNr'])
    {
		
        $order = xtc_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . intval($order_id) . "' limit 1");
		$order_row = xtc_db_fetch_array($order);
        if (!$order || !$order_row['orders_id']) {
		   echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Order #' . $order_id . ' does not exists.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
	       echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
		}

        $redirect_url = '';
		$res_msg = '';
		if (isset($_REQUEST['cancel']) && $_REQUEST['cancel'] == 1 && $response['Status'] == 'waiting') {
            $ctp_order_status = 99;
            $res_msg = '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Payment Cancelled notification from Cointopay.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		//$redirect_url  = xtc_href_link('index');
        }elseif($_REQUEST['status']== 'paid' && $_REQUEST['notenough'] == 0 )
        {
            $ctp_order_status = MODULE_PAYMENT_COINTOPAY_PAID_STATUS_ID;
			$res_msg = '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:green;">Payment Successful.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/success.png" />';
			$res_msg .= '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			
          //  $redirect_url  = xtc_href_link('checkout_success?thankyou=Payment Successfull');
        }elseif ($_REQUEST['status']== 'paid' && $_REQUEST['notenough'] == 1) {
            $ctp_order_status = MODULE_PAYMENT_COINTOPAY_PAIDNOTENOUGH_STATUS_ID;
            $res_msg = '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Payment failed notification from Cointopay because not enough.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
			$res_msg .= '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
		//$redirect_url  = xtc_href_link('index');
        }elseif ($_REQUEST['status']== 'failed') {
            $ctp_order_status = MODULE_PAYMENT_COINTOPAY_FAILED_STATUS_ID;
            $res_msg = '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Payment failed notification from Cointopay.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		$res_msg .= '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
		//$redirect_url  = xtc_href_link('index');
        }elseif ($_REQUEST['status'] == 'expired') {
            $ctp_order_status = MODULE_PAYMENT_COINTOPAY_FAILED_STATUS_ID;
            $res_msg = '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;text-align:center;"><h1 style="text-align:center;color:red;">Payment '.$_REQUEST['status'].' notification from Cointopay.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
		$res_msg .= '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
		//$redirect_url  = xtc_href_link('index');
        }
        else{
            $ctp_order_status = NULL;
            $redirect_url  = xtc_href_link(FILENAME_CHECKOUT_PAYMENT);
        }
        //udate order status
        if ($ctp_order_status)
            xtc_db_query("update ". TABLE_ORDERS. " set orders_status = " . $ctp_order_status . " where orders_id = ". intval($order_id));

        $date =  (new \DateTime())->format('Y-m-d H:i:s');
        if($_REQUEST['status']== 'paid' && $_REQUEST['notenough'] == 0){


            xtc_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('$order_id', '$ctp_order_status', '$date', '1', 'Payment completed notification from Cointopay')");
        }

        if($_REQUEST['status']== 'paid' && $_REQUEST['notenough'] == 1){

            xtc_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('$order_id', '$ctp_order_status', '$date', '1', 'Payment failed notification from Cointopay because notenough')");
        }

        if($_REQUEST['status']== 'failed'){

            xtc_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('$order_id', '$ctp_order_status', '$date', '1', 'Payment failed notification from Cointopay')");
        }

        if($_REQUEST['status']== 'expired'){

            xtc_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('$order_id', '$ctp_order_status', '$date', '1', 'Payment failed notification from Cointopay')");
        }
		if ($res_msg == '') {
			xtc_redirect($redirect_url);
		} else {
			echo $res_msg;
			exit;
		}
    }
    else
    {
        echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;"><h1 style="text-align:center;color:red;">order id not match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
         echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
    }
}
else
{
    echo '<div style="width:80%;display:table;margin:10% auto 0 auto;padding:6% 20px;background-color:#dadada;"><h1 style="text-align:center;color:red;">ConfirmCode not match.</h1><img style="width: 100px; margin: 40px auto 20px;"  src="'.GM_HTTP_SERVER . DIR_WS_CATALOG . 'includes/modules/payment/cointopay/img/cross.png" />';
     echo '<a class="btn btn-default btn-block" href="'.$back_url.'" title="Go to home" style="
				display: block;
				background-color: #3F51B5;
				padding: 10px 38px;
				color: #fff;
				text-transform: uppercase;
				text-decoration: none;
				width: 100px!important;
				max-width: unset;
				/* clear: both; */
				margin: 20px auto 0;
			line-height: 25px;">
								Go to home
							</a></div>';
			exit;
}


function  validateOrder($data)
{
    $params = array(
        "authentication:1",
        'cache-control: no-cache',
    );
    $ch = curl_init();
    curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://app.cointopay.com/v2REAPI?',
            //CURLOPT_USERPWD => $this->apikey,
            CURLOPT_POSTFIELDS => 'MerchantID='.$data['mid'].'&Call=QA&APIKey=_&output=json&TransactionID='.$data['TransactionID'].'&ConfirmCode='.$data['ConfirmCode'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $params,
            CURLOPT_USERAGENT => 1,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC
        )
    );
    $response = curl_exec($ch);
    $results = json_decode($response, true);
    return $results;
}
function  fn_cointopay_transactiondetail($data)
{
       $params = array(
       "authentication:1",
       'cache-control: no-cache',
       );
       $ch = curl_init();
       curl_setopt_array($ch, array(
       CURLOPT_URL => 'https://app.cointopay.com/v2REAPI?',
       //CURLOPT_USERPWD => $this->apikey,
       CURLOPT_POSTFIELDS => 'Call=Transactiondetail&MerchantID='.$data['mid'].'&output=json&ConfirmCode='.$data['ConfirmCode'].'&APIKey=a',
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_SSL_VERIFYPEER => false,
       CURLOPT_HTTPHEADER => $params,
       CURLOPT_USERAGENT => 1,
       CURLOPT_HTTPAUTH => CURLAUTH_BASIC
       )
       );
       $response = curl_exec($ch);
       $results = json_decode($response, true);
       /*if($results->CustomerReferenceNr)
       {
           return $results;
       }*/
       return $results;
}

