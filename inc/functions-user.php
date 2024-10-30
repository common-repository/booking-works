<?php
	
	add_action( 'show_user_profile', 'wpbw_extra_user_profile_fields' );
	add_action( 'edit_user_profile', 'wpbw_extra_user_profile_fields' );
	
	if(!function_exists('wpbw_save_extra_user_profile_fields')){
	function wpbw_extra_user_profile_fields( $user ) { 
	
	if(wpbw_valid_user($user->ID) && wpbw_posting_fee_required()){
?>
	
		<table class="form-table">
		<tr>
			<th><label for="address"><?php _e("Deactivate Product Posting Fee"); ?></label></th>
			<td>
				<input type="checkbox" name="ppf_status" value="true" id="address" <?php checked( get_the_author_meta( 'ppf_status', $user->ID ) == true ); ?> class="regular-text" /><br />

			</td>
		</tr>
		</table>
<?php 
	}
	}
	}
		
	add_action( 'personal_options_update', 'wpbw_save_extra_user_profile_fields' );
	add_action( 'edit_user_profile_update', 'wpbw_save_extra_user_profile_fields' );
	
	if(!function_exists('wpbw_save_extra_user_profile_fields')){
	function wpbw_save_extra_user_profile_fields( $user_id ) {
		
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; 
		}
		if(wpbw_valid_user($user_id) && wpbw_posting_fee_required()){

		    $ppf_status = wpbw_sanitize_bw_data($_POST['ppf_status']);

			update_user_meta( $user_id, 'ppf_status', $ppf_status );
		}
	}	
	}
	
	if(!function_exists('wpbw_posting_fee_required_from_user')){
	function wpbw_posting_fee_required_from_user($user_id=0){
		$user_id = ($user_id?$user_id:get_current_user_id());
		
		return (get_the_author_meta( 'ppf_status', $user_id ) == true);
	}
	}
	
	if(!function_exists('wpbw_posting_fee_required')){
	function wpbw_posting_fee_required(){
		return (wpbw_options('wp_ca_posting', 'free')=='paid');
	}
	}
	
	if(!function_exists('wpbw_posting_post_status')){
	function wpbw_posting_post_status($post_id){
		
		$return = false;
		//pree($post_id);
		$sale_id = get_post_meta($post_id, 'recent_subsription', true);
		//$expected_subsription = get_post_meta($post_id, 'expected_subsription', true);
		//delete_post_meta($post_id, 'recent_subsription');
		//pree($post_id.'-'.$sale_id);
		if($sale_id!='' && is_numeric($sale_id)){
		
			$order_data = wc_get_order( $sale_id );
			
			if(!empty($order_data)){
				$order_date = $order_data->get_date_created();//order_date;
				$return = true;
			}
			
		}elseif($sale_id=='' && get_post_status($post_id)=='draft'){
			/*$customer_orders = get_posts( array(
				'numberposts' => -1,
				'meta_key'    => '_customer_user',
				'meta_value'  => get_current_user_id(),
				'post_type'   => wc_get_order_types(),
				'post_status' => array('wc-processing', 'wc-completed')//array_keys( wc_get_order_statuses() ),
			) );
			//pree(array_keys( wc_get_order_statuses() ));
			//pree($customer_orders);
			//pree($post_id.'-'.$sale_id);
			if(!empty($customer_orders)){
				foreach($customer_orders as $customer_order){

					$order_data = wc_get_order( $customer_order->ID );
					//pree($order_data);
					if($order_data->get_total()==wpbw_options('wp_ca_posting_fee', '0')){
						//delete_post_meta($customer_order->ID, 'redeemed');
						$redeemed = get_post_meta($customer_order->ID, 'redeemed', true);
						//pree($redeemed);
						//exit;
						if(!$redeemed){							
							update_post_meta($post_id, 'recent_subsription', $customer_order->ID);
							update_post_meta($customer_order->ID, 'redeemed', true);
							wp_update_post( array('ID'=>$post_id, 'post_status'=>'publish') );
							$return = wpbw_posting_post_status($post_id);
						}
					}
					
				}
			}*/
			//pree($customer_orders);exit;
		}
		
		return $return;
	}
	}
		
	if(!function_exists('wpbw_valid_user_allowed')){
	function wpbw_valid_user_allowed($product_id, $user_id=0){
		
		$return = false;
		
		$user_id = ($user_id?$user_id:get_current_user_id());
	
		
	
		if(
				$product_id
			&&
				wpbw_valid_user($user_id) 
			&&
				(
						(								
							
							(
									wpbw_posting_fee_required() //REQUIRED FROM ALL
								//&&
									//wpbw_posting_fee_required_from_user($user_id) //REQUIRED FROM THIS USER AS WELL
								&&
									wpbw_posting_post_status($product_id) //PAID THE FEE OR NOT
							)	
						)
						
					||
						(
								!wpbw_posting_fee_required() //OR SIMPLY NOT REQUIRED FROM ANYBODY
							||
								wpbw_posting_fee_required_from_user($user_id) //OR NOT REQUIRED FFROM THIS SPECIFIC USER
						)
				)
		){
			$return = true;
		}
		
		return $return;
		
	}
	}
	
	add_action('wp', 'wpbw_scripts_prior_lb_load');
	
	if(!function_exists('wpbw_scripts_prior_lb_load')){
	function wpbw_scripts_prior_lb_load(){
		
		global $post;

		if(isset($_GET['ids'])){

		    $get_ids = wpbw_sanitize_bw_data($_GET['ids']);

            if(is_numeric($get_ids) && isset($post->ID) && $post->ID==1541 && wpbw_is_user_item($get_ids)){

                /*if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                        WC()->cart->set_quantity( $cart_item_key, 0 );
                    }
                }*/
                WC()->cart->empty_cart();
                WC()->cart->add_to_cart($post->ID, 1);
                update_post_meta($get_ids, 'expected_subsription', $post->ID);
                $_SESSION['expected_subsription'] = array($post->ID, $get_ids);
                wp_redirect(wc_get_checkout_url());

                exit;
            }

        }

//		$get_ids =



	}
	}
	
	