<?php

namespace LokusWP\Commerce\Shortcodes;

class Order_History {

	public function __construct() {
		add_shortcode( 'lwcommerce_order_history', [ $this, 'render' ] );
	}

	public function render( $atts ) {
		// extract(shortcode_atts(array(
		//     'product_ids' => false,
		// ), $atts));

		wp_enqueue_style( "lokuswp-grid" );

		ob_start();

		require_once LWC_PATH . 'src/templates/presenter/customer/history.php';

		return ob_get_clean();
	}
}
