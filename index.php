<?php
/*
	Plugin Name: Booking Works
	Plugin URI: https://profiles.wordpress.org/fahadmahmood/#content-plugins
	Description: A great plugin to manage bookings and calendar related functions.
	Version: 1.0.3
	Author: Fahad Mahmood
	Author URI: https://www.androidbubbles.com
    Text Domain: booking-works
	License: GPL2

	This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This WordPress plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License	along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
		/**
	 * Check if WooCommerce is active
	 **/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		
		
		global $wp_ca_type, $wp_ca_pages, $wp_ca_data, $wp_ca_pro, $wp_ca_activated, $wp_ca_dir,
               $wp_ca_url, $wp_ca_required_plugins, $wp_ca_logo, $wp_ca_multiple, $poi_acf, $wp_lb_inbox, $wp_fu_plugin,
               $ns_add_product, $wc_vendors_status, $wc_vendors_edit_product, $wp_ca_booking_details, $wp_ca_wishlist,
                $wp_ca_template_list, $bw_premium_copy;





		$bw_premium_copy = 'https://shop.androidbubbles.com/product/booking-works';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439507566747';//

		if(!function_exists('wpbw_options')){
		function wpbw_options($key='', $default=array()){			
			$ret = '';
			$wp_ca_options = get_option('wp_ca_options', $default);
		
			if(is_array($wp_ca_options) && array_key_exists($key, $wp_ca_options))
			$ret = $wp_ca_options[$key];
			
			return $ret;
		}
		}
		
		
		$wp_ca_type = wpbw_options('wp_ca_type', 'virtual'); //print_r($wp_ca_type);
		$wp_ca_multiple = (wpbw_options('wp_ca_multiple', 'single')=='multiple'); //print_r(wpbw_options('wp_ca_multiple'));print_r($wp_ca_multiple==true);
		
		
		
		$poi_acf = in_array( 'poi-acf-for-wp/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		$wp_lb_inbox = in_array( 'inbox/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		
			
		

		$wp_ca_logo = wpbw_options('logo');//
				
		
		$wp_ca_required_plugins = array(
			'inbox'=>'inbox/index.php',
			'ns-add-product-frontend'=>'ns-add-product-frontend/ns-frontend-add-product-page.php',
			'wordpress-bootstrap-css'=>'wordpress-bootstrap-css/hlt-bootstrapcss.php',
			'better-font-awesome'=>'better-font-awesome/better-font-awesome.php',
			'custom-permalinks'=>'custom-permalinks/custom-permalinks.php',
			'frontend-uploader'=>'frontend-uploader/frontend-uploader.php',
			'wc-vendors'=>'wc-vendors/class-wc-vendors.php',
			'woocommerce-security-deposits'=>'woocommerce-security-deposits/woocommerce-security-deposits.php',
			'yith-woocommerce-wishlist'=>'yith-woocommerce-wishlist/init.php'
		);

        $wp_ca_template_list = array(

            'default' => __('Default', 'booking-works'),
            'alpha' => __('Alpha', 'booking-works'),
            'beta' => __('Beta', 'booking-works'),

        );


		$wp_fu_plugin = in_array( $wp_ca_required_plugins['frontend-uploader'], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		$ns_add_product = in_array( $wp_ca_required_plugins['ns-add-product-frontend'], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		$wc_vendors_status = in_array( $wp_ca_required_plugins['wc-vendors'], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		
		$wp_ca_wishlist = in_array( $wp_ca_required_plugins['yith-woocommerce-wishlist'], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )	;
		
		if($wc_vendors_status){
			$wc_vendors_options = get_option('wc_prd_vendor_options', array());
			$wc_vendors_edit_product = (isset($wc_vendors_options['can_edit_published_products']) ? $wc_vendors_options['can_edit_published_products'] : '');
			
		}

		//WP-CA-PRODUCTS-BY-USERS
		//WP-CA-PRODUCT-TYPES
		$wp_ca_pages = array(
			'Add Product' => '[WP-CA-PRODUCT-TYPES][ns-add-product]',//Add Item
			'Booking Tracking' => '[WP-BOOKING-TRACKING]',
			'My Sales' => '[WP-CA-SALES-BY-USERS]',

		);
		$wp_ca_pages['My Items'] = '[WP-CA-PRODUCTS-BY-USERS]';

		switch($wp_ca_type){
			case 'virtual':
			
			break;
			default:
			
			break;
		}
				
		
		$wp_ca_activated = false;
		$wp_ca_all_plugins = get_plugins();
		$wp_ca_plugins_activated = apply_filters( 'active_plugins', get_option( 'active_plugins' ));
		
		
		
		if(array_key_exists('woocommerce/woocommerce.php', $wp_ca_all_plugins) && in_array('woocommerce/woocommerce.php', $wp_ca_plugins_activated)){
			$wp_ca_activated = true;
		}
		
		
		
		$wp_ca_data = get_plugin_data(__FILE__);
		
		
		$wp_ca_dir = dirname( __FILE__ );
		$wp_ca_url = plugin_dir_url( __FILE__ );





        $wp_ca_pro_file = $wp_ca_dir . '/pro/bw-pro.php';
		
		
		$wp_ca_pro =  file_exists($wp_ca_pro_file);
		
		include_once $wp_ca_dir . '/inc/functions.php';
		include_once $wp_ca_dir . '/classes/WP_CA_METABOX.php';


		add_action('add_meta_boxes', ['WP_CA_METABOX', 'add']);
		add_action('save_post', ['WP_CA_METABOX', 'save']);

		
		
		if($wp_ca_pro)
		include_once($wp_ca_pro_file);
		
		
		
		

		

		
	}



	if(is_admin()){

		add_action( 'admin_menu', 'wpbw_admin_menu' );	
		$plugin = plugin_basename(__FILE__); 
		if(function_exists('wpbw_plugin_linx')){
			add_filter("plugin_action_links_$plugin", 'wpbw_plugin_linx' );	
		}		
	}