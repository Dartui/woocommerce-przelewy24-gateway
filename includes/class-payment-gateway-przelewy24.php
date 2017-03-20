<?php

class WC_Gateway_Przelewy24_a extends WC_Payment_Gateway {
	public function __construct() {
		$this->id                 = 'przelewy24_a';
		$this->icon               = P24_URL . 'assets/images/przelewy24.png';
		$this->title              = $this->get_option('title', 'Przelewy24');
		$this->method_title       = $this->get_option('title', 'Przelewy24');
		$this->method_description = $this->get_option('description', 'test');
		$this->supports           = array('products', 'refunds');

		$this->init_form_fields();
		$this->init_settings();

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_api_wc_gateway_' . $this->id, array($this, 'check_response'));
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'     => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => 'Aktywuj bramkę Przelewy24',
				'default' => 'yes',
			),
			'title'       => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Cheque Payment', 'woocommerce'),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'   => __('Customer Message', 'woocommerce'),
				'type'    => 'textarea',
				'default' => '',
			),
		);
	}

	public function process_payment($order_id) {
		$order = wc_get_order($order_id);

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __('Awaiting cheque payment', 'woocommerce'));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url($order),
		);
	}

	public function process_refund($order_id, $amount = NULL, $reason = '') {
		return true;
	}

	public function check_response() {

	}
}