<?php

class Cointopay
{
  public $code;
  public $title;
  public $enabled;

  private $merchant_id;
  private $security_code;


  function cointopay()
  {
	include(DIR_FS_CATALOG.'lang/'.$_SESSION['language'].'/modules/payment/cointopay.php');
    $this->code             = 'cointopay';
    $this->title            = MODULE_PAYMENT_COINTOPAY_TEXT_TITLE;
    $this->description      = MODULE_PAYMENT_COINTOPAY_TEXT_DESCRIPTION;
    $this->merchant_id      = 'MODULE_PAYMENT_COINTOPAY_MERCHANT_ID';
    $this->security_code    = 'MODULE_PAYMENT_COINTOPAY_SECURITY_CODE';
    $this->enabled          = ((MODULE_PAYMENT_COINTOPAY_STATUS == 'True') ? true : false);
	$this->sort_order = 'MODULE_PAYMENT_COINTOPAY_SORT_ORDER';
	$this->logo_url     = '/images/icons/payment/'.'cointopay.png';

  }
/// @brief collect data and create a array with wirecard checkout page infos
    function _isInstalled($c) {
        $result = xtc_db_query("SELECT count(*) FROM ".TABLE_CONFIGURATION." WHERE configuration_key = 'MODULE_PAYMENT_{$c}_STATUS'");
        $resultRow = $result->fetch_row();
        return $resultRow[0];
    }
  function javascript_validation()
  {
    return false;
  }

  function selection()
  {
    //return array('id' => $this->code, 'module' => $this->title);
	return array ('id' => $this->code, 'module' => $this->title, 'description' => $this->info, 'logo_url' => $this->logo_url);
  }

  function pre_confirmation_check()
  {
    return false;
  }

  function confirmation()
  {
    return false;
  }

  function process_button()
  {
    return false;
  }

  function before_process()
  {
    return false;
  }

  function after_process()
  {
    global $insert_id, $db, $order;

    $info = $order->info;

    $configuration = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='STORE_NAME' limit 1");
    //$products = xtc_db_query("select oc.products_id, oc.products_quantity, pd.products_name from " . TABLE_ORDERS_PRODUCTS . " as oc left join " . TABLE_PRODUCTS_DESCRIPTION . " as pd on pd.products_id=oc.products_id  where orders_id=" . intval($insert_id));
	$products = $_SESSION['cart']->get_products();
//print_r($products);die;
    $description = array();
	foreach($products as $product){
      $description[] = $product['products_quantity'] . ' × ' . $product['products_name'];
    }

   $callback = xtc_href_link('cointopay_callback.php', 'token=' . MODULE_PAYMENT_COINTOPAY_CALLBACK_SECRET, 'SSL' );


    $params = array(
      'order_id'         => $insert_id,
      'price'            => number_format($info['total'], 2, '.', ''),
      'currency'         => $info['currency'],
      'callback_url'     => $this->flash_encode($callback),
      'cancel_url'       => $this->flash_encode($callback),
      'success_url'      => xtc_href_link('checkout_success'),
      'title'            => $configuration->fields['configuration_value'] . ' Order #' . $insert_id,
      'description'      => join($description, ', ')
    );
  
    require_once(dirname(__FILE__) . "/cointopay/init.php");
    require_once(dirname(__FILE__) . "/cointopay/version.php");

    $order = \Cointopay\Merchant\Order::createOrFail($params, array(), array(
      'merchant_id' => MODULE_PAYMENT_COINTOPAY_MERCHANT_ID,
      'security_code' => MODULE_PAYMENT_COINTOPAY_SECURITY_CODE,
      'user_agent' => 'Cointopay - Gambio Extension v' . COINTOPAY_ZENCART_EXTENSION_VERSION));

    $_SESSION['cart']->reset(true);
    xtc_redirect($order->shortURL);

    return false;
  }

  function check()
  {
      global $db;

      if (!isset($this->_check)) {
          $check_query  = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_COINTOPAY_STATUS'");
          $this->_check = xtc_db_num_rows($check_query);

      }

      return $this->_check;
  }

  function install()
  {
    global $messageStack;

    if (defined('MODULE_PAYMENT_COINTOPAY_STATUS')) {
      $messageStack->add_session('Cointopay module already installed.', 'error');
      xtc_redirect(xtc_href_link(FILENAME_MODULES, 'set=payment&module=cointopay', 'NONSSL'));

      return 'failed';
    }
    $callbackSecret = md5('xtc_' . mt_rand());
	$paid_status =  xtc_db_query("select `orders_status_id` FROM " . TABLE_ORDERS_STATUS . " WHERE `orders_status_id` = '5'");
    if(xtc_db_num_rows($paid_status)  == 0){
      xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('5', '1', 'Paid')");
	  xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('5', '2', 'Bezahlt')");
    }
    $failed_status =  xtc_db_query("select `orders_status_id` FROM " . TABLE_ORDERS_STATUS . " WHERE `orders_status_id` = '6'");
    if(xtc_db_num_rows($failed_status)  == 0){
      xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('6', '1', 'Failed')");
	  xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('6', '2', 'Gescheitert')");
    }
    $paidnotenouty_status =  xtc_db_query("select `orders_status_id` FROM " . TABLE_ORDERS_STATUS . " WHERE `orders_status_id` = '7'");
    if(xtc_db_num_rows($paidnotenouty_status)  == 0){
      xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('7', '1', 'Paidnotenough')");
	  xtc_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('7', '2', 'Nicht genug bezahlt')");
    }
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_COINTOPAY_STATUS', 'False', '6', '0', 'gm_cfg_select_option(array(\'True\', \'False\'), ', now())");
	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_COINTOPAY_ALLOWED', '0', '6', '0', 'xtc_get_zone_class_title', 'xtc_cfg_pull_down_zone_classes(', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COINTOPAY_MERCHANT_ID', '0', '6', '0', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COINTOPAY_SECURITY_CODE', '0', '6', '0', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_COINTOPAY_PAID_STATUS_ID', '5', '6', '0', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_COINTOPAY_FAILED_STATUS_ID', '6', '6', '6', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_COINTOPAY_PAIDNOTENOUGH_STATUS_ID', '7', '6', '6', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
    xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added, use_function) values ('MODULE_PAYMENT_COINTOPAY_CALLBACK_SECRET', '$callbackSecret', '6', '6', now(), 'cointopay_censorize')");
  }

  function remove()
  {
    xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE 'MODULE\_PAYMENT\_COINTOPAY\_%'");
    xtc_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '5'");
    xtc_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '6'");
    xtc_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '7'");
  }

  function keys()
  {
    return array(
      'MODULE_PAYMENT_COINTOPAY_STATUS',
	  'MODULE_PAYMENT_COINTOPAY_ALLOWED',
      'MODULE_PAYMENT_COINTOPAY_MERCHANT_ID',
      'MODULE_PAYMENT_COINTOPAY_SECURITY_CODE',
      'MODULE_PAYMENT_COINTOPAY_PAID_STATUS_ID',
      'MODULE_PAYMENT_COINTOPAY_FAILED_STATUS_ID',
	  'MODULE_PAYMENT_COINTOPAY_PAIDNOTENOUGH_STATUS_ID',
    );
  }
  public function flash_encode ($input)
   {
       return rawurlencode(utf8_encode($input));
   }
}

function cointopay_censorize($value) {
  return "(hidden for security reasons)";
}
