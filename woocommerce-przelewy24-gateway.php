<?php

/* Plugin Name: WooCommerce Przelewy24 Gateway
 * Plugin URI: Przelewy24 Gateway for WooCommerce
 * Description: Przelewy24 Gatway for WooCommerce
 * Version: 1.0
 * Author: Krzysztof Grabania
 * Author URI: http://grabania.pl
 * Text Domain: wc-p24
 * Domain Path: /languages
 */

define('P24_DIR', plugin_dir_path(__FILE__));
define('P24_URL', plugin_dir_url(__FILE__));

if (!class_exists('Przelewy24')) {
	class Przelewy24 {
		public function __construct() {
			add_action('plugins_loaded', array($this, 'init_gateway'));
			add_filter( 'woocommerce_payment_gateways', array($this, 'payment_gateways'));
		}
		
		public function init_gateway() {
			require_once P24_DIR . 'includes/class-payment-gateway-przelewy24.php';
		}
		
		public function payment_gateways($gateways) {
			$gateways[] = 'WC_Gateway_Przelewy24_a';
			
			return $gateways;
		}
	}
	
	global $p24;
	
	$p24 = new Przelewy24();
}