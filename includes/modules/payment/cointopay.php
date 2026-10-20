<?php
/**
 * Cointopay payment module for Gambio GX.
 * Configuration storage: gx_configurations
 */

defined('GM_HTTP_SERVER') or define('GM_HTTP_SERVER', HTTP_SERVER);

class cointopay
{
    public $code = 'cointopay';
    public $title, $description, $enabled;
    public $tmpOrders = true;
    public $tmpStatus = 0;
    public $sort_order = 0;
    public $info = '';
    public $order_status = 0;

    protected $_check = null;

    /**
     * Initialize the payment module.
     */
    public function __construct()
    {
        $languageManager = MainFactory::create_object(
            'LanguageTextManager',
            [],
            true
        );

        $language = isset($_SESSION['language'])
            ? $_SESSION['language']
            : 'english';

        $languageManager->init_from_lang_file(
            'lang/' . $language . '/modules/payment/cointopay.php'
        );

        $prefix = 'MODULE_PAYMENT_COINTOPAY_';

        $this->title = defined($prefix . 'TEXT_TITLE')
            ? constant($prefix . 'TEXT_TITLE')
            : 'Cointopay';

        $this->description = defined($prefix . 'TEXT_DESCRIPTION')
            ? constant($prefix . 'TEXT_DESCRIPTION')
            : 'Pay using Cointopay';

        $this->info = defined($prefix . 'TEXT_INFO')
            ? constant($prefix . 'TEXT_INFO')
            : '';

        $this->sort_order = defined($prefix . 'SORT_ORDER')
            ? (int) constant($prefix . 'SORT_ORDER')
            : 0;

        $this->enabled = defined($prefix . 'STATUS')
            && filter_var(
                constant($prefix . 'STATUS'),
                FILTER_VALIDATE_BOOLEAN
            );

        $this->order_status = defined($prefix . 'PAID_STATUS_ID')
            ? (int) constant($prefix . 'PAID_STATUS_ID')
            : 0;
    }

    /**
     * Original nine configuration fields only.
     */
    public function _configuration()
    {
		return [
            'STATUS' => [
                'value' => 'false',
                'type'  => 'switcher',
            ],
            'ALLOWED' => [
                'value' => '',
            ],
            'MERCHANT_ID' => [
                'value' => '',
            ],
            'SECURITY_CODE' => [
                'value' => '',
            ],
            'PAID_STATUS_ID' => [
                'value' => '',
                'type'  => 'order-status',
            ],
            'FAILED_STATUS_ID' => [
                'value' => '',
                'type'  => 'order-status',
            ],
            'PAIDNOTENOUGH_STATUS_ID' => [
                'value' => '',
                'type'  => 'order-status',
            ],
            'SORT_ORDER' => [
                'value' => '0',
            ],
        ];
    }

    public function check()
    {
        if ($this->_check === null) {
            $query = xtc_db_query(
                "SELECT `value`
                 FROM `gx_configurations`
                 WHERE `key` = 'configuration/MODULE_PAYMENT_COINTOPAY_STATUS'
                 LIMIT 1"
            );

            $this->_check = xtc_db_num_rows($query);
        }

        return $this->_check;
    }

    /**
     * Register configuration fields.
     * Existing settings are preserved.
     */
    public function install()
    {
        $config = $this->_configuration();
        $sortOrder = 0;

        $callbackSecret = md5('xtc_' . mt_rand());
		$config['CALLBACK_SECRET'] = array('type' => 'cointopay_censorize', 'value' =>$callbackSecret);
        foreach ($config as $key => $data) {
            $fullKey = 'configuration/MODULE_PAYMENT_COINTOPAY_' . $key;
            $escapedKey = addslashes($fullKey);

            $exists = xtc_db_query(
                "SELECT `key`
                 FROM `gx_configurations`
                 WHERE `key` = '" . $escapedKey . "'
                 LIMIT 1"
            );

            if (xtc_db_num_rows($exists) === 0) {
                $value = isset($data['value'])
                    ? addslashes((string) $data['value'])
                    : '';

                $type = empty($data['type'])
                    ? 'NULL'
                    : "'" . addslashes($data['type']) . "'";

                xtc_db_query(
                    "INSERT INTO `gx_configurations`
                    (`key`, `value`, `sort_order`, `type`, `last_modified`)
                    VALUES (
                        '" . $escapedKey . "',
                        '" . $value . "',
                        " . (int) $sortOrder . ",
                        " . $type . ",
                        NOW()
                    )"
                );
            }

            $sortOrder++;
        }

        $this->_check = null;
    }

    public function remove()
    {
        xtc_db_query(
            "DELETE FROM `gx_configurations`
             WHERE `key` IN ('"
            . implode("', '", array_map('addslashes', $this->keys()))
            . "')"
        );

        $this->_check = null;
    }

    /**
     * Return the original nine Gambio configuration keys.
     */
    public function keys()
    {
        $configKeys = array_keys($this->_configuration());
        $keys = [];

        foreach ($configKeys as $key) {
            $keys[] = 'configuration/MODULE_PAYMENT_COINTOPAY_' . $key;
        }

        return $keys;
    }

    public function isInstalled()
    {
        foreach ($this->keys() as $key) {
            if (!defined($key)) {
                return false;
            }
        }

        return true;
    }

    public function update_status()
    {
        if (!$this->enabled) {
            $this->enabled = false;
        }
    }

    public function javascript_validation()
    {
        return false;
    }

    public function selection()
    {
        return [
            'id'          => $this->code,
            'module'      => $this->title,
            'description' => $this->info,
            'fields'      => [],
        ];
    }

    public function pre_confirmation_check()
	  {
		return false;
	  }

	public function confirmation()
	  {
		return false;
	  }

	public function process_button()
	  {
		return false;
	  }
	  
    public function before_process()
    {
        return false;
    }

   function after_process()
  {
    return;
  }
  
  public function payment_action()
    {
         global $db, $order, $insert_id;
	//$insert_id = $GLOBALS['insert_id'];

    $info = $order->info;
    $configuration = xtc_db_query("SELECT value
FROM `gx_configurations`
WHERE `key` = 'configuration/STORE_NAME'
LIMIT 1");
    $shopname = xtc_db_fetch_array($configuration);
	
	$products = $_SESSION['cart']->get_products();
    $description = array();
	foreach($products as $product){
      $description[] = $product['products_quantity'] . ' × ' . $product['products_name'];
    }

   $callback = xtc_href_link('cointopay_callback.php', 'token=' .MODULE_PAYMENT_COINTOPAY_CALLBACK_SECRET, 'SSL' );
    //die($insert_id);


    $params = array(
      'order_id'         => $insert_id,
      'price'            => number_format($info['total'], 2, '.', ''),
      'currency'         => $info['currency'],
      'callback_url'     => $this->flash_encode($callback),
      'cancel_url'       => $this->flash_encode($callback),
      'success_url'      => xtc_href_link('checkout_success'),
      'title'            => $shopname['value'] . ' Order #' . $insert_id,
      'description'      => ''
    );
  
    require_once(dirname(__FILE__) . "/cointopay/init.php");
    require_once(dirname(__FILE__) . "/cointopay/version.php");

    $order = \Cointopay\Merchant\Order::createOrFail($params, array(), array(
      'merchant_id' => MODULE_PAYMENT_COINTOPAY_MERCHANT_ID,
      'security_code' => MODULE_PAYMENT_COINTOPAY_SECURITY_CODE,
      'user_agent' => 'Cointopay - Gambio Extension v1.1'));

    $_SESSION['cart']->reset(true);
    xtc_redirect($order->shortURL);
    }

    public function get_error()
    {
        return false;
    }
  public function flash_encode ($input)
   {
       return rawurlencode(utf8_encode($input));
   }
       
}

MainFactory::load_origin_class('cointopay');
