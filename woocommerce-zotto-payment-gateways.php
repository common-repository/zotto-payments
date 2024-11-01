<?php
/*
Plugin Name: Zotto Payments
Plugin URI: http://zotto.io/
Description: Payment Gateway By Zotto.
Version: 1.0.0
Author: Zotto
Author URI: 
License: GPLv2
*/

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'zotto_register_plugin_links', 10, 2 );
function zotto_register_plugin_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {

	}
	return $links;
}



/* WooCommerce fallback notice. */
function woocommerce_zotto_fallback_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Custom Payment Gateways depends on the last version of %s to work!', 'wcCpg' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
}

/* Load functions. */
function zotto_payment_gateway_load() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
        add_action( 'admin_notices', 'woocommerce_zotto_fallback_notice' );
        return;
    }
   
    function wc_zotto_add_gateway( $methods ) {
        $methods[] = 'WC_Zotto_Card_Payment_Gateway';
        $methods[] = 'WC_Zotto_Bank_Payment_Gateway';
        return $methods;
    }
	add_filter( 'woocommerce_payment_gateways', 'wc_zotto_add_gateway' );
	
	
    // Include the WooCommerce Custom Payment Gateways classes.
    require_once plugin_dir_path( __FILE__ ) . 'bank/class-wc-zotto_payment_gateway_bank.php';
    require_once plugin_dir_path( __FILE__ ) . 'card/class-wc-zotto_payment_gateway_card.php';
}

add_action( 'plugins_loaded', 'zotto_payment_gateway_load', 0 );



/* Adds custom settings url in plugins page. */

function zotto_action_links( $links ) {
    $settings = array(
		'settings' => sprintf(
		'<a href="%s">%s</a>',
		admin_url( 'admin.php?page=woocommerce_settings&tab=payment_gateways' ),
		__( 'Payment Gateway', 'wcCpg' )
		)
    );

    return array_merge( $settings, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'zotto_action_links' );


?>