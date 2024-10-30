<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
	global $wp_ca_tables, $wp_ca_messenger, $wp_ca_pages, $wp_ca_booking_users_ids;
	
	$wp_ca_messenger = $wp_ca_booking_users_ids = array();
	
	$wp_ca_tables = array(
	
		'calendars_management' => array('ca_id'=>'ignore', 'ca_dated'=>'now', 'ca_start_date'=>'', 'ca_end_date'=>'', 'ca_type'=>'', 'ca_object_id'=>'', 'ca_order_attached'=>'', 'ca_user_id'=>'logged_in_user', 'ca_status'=>'active', 'ca_slot_year'=>'', 'ca_slot_month'=>'', 'ca_slot_day'=>'', 'ca_slot_hour'=>'', 'ca_slot_minute'=>'', 'ca_duration_number'=>'', 'ca_duration_type'=>'', 'ca_user_remarks'=>'', 'ca_custom_label'=>'','ca_add_on'=>'add_on',  'ca_blog_id'=>'blog_id')
	
	);

	if(!function_exists('wpbw_pre')){
	function wpbw_pre($data){
			if(isset($_GET['debug'])){
				wpbw_pree($data);
			}
		}
	}
	if(!function_exists('wpbw_pree')){
	function wpbw_pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';

		}
	}
	
	
	function wpbw_confirm_contract(){
		$resp = array('msg'=>'');
		$post_data = wpbw_sanitize_bw_data($_POST);
		extract($post_data);
		if($ticket_number>0){
			unset($post_data['action']);
			$data = $post_data;
			
			$qty_count = 0;
			
			foreach($data as $pkey=>$pitems){
				if(substr($pkey, 0, strlen('participant-name-'))=='participant-name-'){
					if(!empty(array_filter($pitems, 'strlen'))){
						$qty_count++;
					}else{
						unset($data[$pkey]);
					}
				}
			}
			update_option('contract_snapshot_'.$ticket_number, serialize($post_data));
			
			if($qty_count>1){
				
				$c_array = array('ca_type'=>'wc_product', 'ca_user_id'=>get_current_user_id(), 'ca_id'=>$data['ticket_number'], 'ca_blog_id'=>get_current_blog_id());
					//pree($c_array);
				$existing_booking_param = wpbw_get_actions('calendars_management', $c_array, true);
				if(!empty($existing_booking_param)){
					global $woocommerce;
					$resp['msg'] = 'redirect';
					$woocommerce->cart->empty_cart();
					foreach($existing_booking_param as $existing_booking_item){
						$woocommerce->cart->add_to_cart($existing_booking_item->ca_object_id, $qty_count);
					}
				}
			}
		}
		echo json_encode($resp);
		exit;
	}
	add_action( 'wp_ajax_wpbw_confirm_contract', 'wpbw_confirm_contract' );

	if(!function_exists('wpbw_sanitize_bw_data')){
	function wpbw_sanitize_bw_data( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?wpbw_sanitize_bw_data($val):stripslashes(sanitize_text_field( $val )));
			}			
		}else{
			$new_input = stripslashes(sanitize_text_field($input));			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = esc_url($new_input);
			}			
		}	
		return $new_input;
	}	
	}
	
	if(!function_exists('wpbw_options_update')){
	function wpbw_options_update(){
		

		
		if(!empty($_POST) && isset($_POST['wp_ca_options'])){
			
			$wp_ca_options = get_option('wp_ca_options', array());
			//pree($wp_ca_options);exit;
			
			if (isset( $_POST['wp_ca_settings_field2'] )){
				if (! wp_verify_nonce( $_POST['wp_ca_settings_field2'], 'wp_ca_settings_action2' ) 
				) {
				
				   _e('Sorry, your nonce did not verify.', 'booking-works');
				   exit;
				
				} else {
					//pree($_POST['wp_ca_options']);exit;
					$wp_ca_options = wpbw_sanitize_bw_data($_POST['wp_ca_options']);
					foreach($wp_ca_options as $k=>$v){
						$wp_ca_options[$k] = $v;
					}
					//pree($wp_ca_options);exit;
					update_option('wp_ca_options', $wp_ca_options);
					
					
				}				
			}elseif (isset( $_POST['wp_ca_settings_field3'] )){
				if (! wp_verify_nonce( $_POST['wp_ca_settings_field3'], 'wp_ca_settings_action3' ) 
				) {
				
				   _e('Sorry, your nonce did not verify.', 'booking-works');
				   exit;
				
				} else {
					//pree($_POST['wp_ca_options']);exit;
					$wp_ca_options = wpbw_sanitize_bw_data($_POST['wp_ca_options']);
					foreach($wp_ca_options as $k=>$v){
						$wp_ca_options[$k] = $v;
					}
					//pree($wp_ca_options);exit;
					update_option('wp_ca_options', $wp_ca_options);
					
					
				}					
			}			
			

		}
	}
	}
	
	if(!function_exists('wpbw_setup')){
	function wpbw_setup(){
		global $wpdb, $wp_ca_pages;


		
		$queries = array();
		$queries['calendars_management']= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."calendars_management` (
					  `ca_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `ca_dated` int(11) DEFAULT NULL,
					  `ca_start_date` int(11) DEFAULT NULL,
					  `ca_end_date` int(11) DEFAULT NULL,					  
					  `ca_type` varchar(255) DEFAULT NULL,
					  `ca_object_id` bigint(20) DEFAULT NULL,
					  `ca_order_attached` int(11) NOT NULL,
					  `ca_user_id` int(11) DEFAULT NULL,
					  `ca_status` varchar(255) DEFAULT NULL,
					  `ca_slot_year` int(11) DEFAULT NULL,
					  `ca_slot_month` char(2) DEFAULT NULL,
					  `ca_slot_day` char(2) DEFAULT NULL,
					  `ca_slot_hour` text NOT NULL,
					  `ca_slot_minute` int(11) DEFAULT NULL,
					  `ca_duration_number` int(11) DEFAULT '1',
					  `ca_duration_type` char(1) DEFAULT 'd',					  
					  `ca_user_remarks` text DEFAULT NULL,
					  `ca_custom_label` varchar(255) DEFAULT NULL,
					  `ca_add_on`  longtext DEFAULT NULL,
					  `ca_blog_id` int(11) DEFAULT NULL,
					  PRIMARY KEY (`ca_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
					
		if(!empty($queries)){
			foreach($queries as $key=>$query){
				if(get_option($key, false)){
					
				}else{
					$wpdb->query($query);
					update_option($key, true);
				}
			}
		}
		
		if(!empty($wp_ca_pages)){
			foreach($wp_ca_pages as $title => $content){
				$page = get_page_by_title( $title, ARRAY_A, 'page' );
				//pree($page);
				if(empty($page) && isset($_GET['wp_ca_setup'])){
					
					$my_page = array(
					  'post_title'    => $title,
					  'post_content'  => $content,
					  'post_status'   => 'publish',
					  'post_type' => 'page'
					);					 

					wp_insert_post( $my_page );					
				}
			}
			
			if(isset($_GET['wp_ca_setup'])){
				wp_redirect('options-general.php?page=wc_ca_settings');exit;				
			}
		}
		
		wpbw_options_update();


	}
	}
	
	add_action('admin_init', 'wpbw_setup');
	
	
	if(!function_exists('wpbw_underscore')){
	function wpbw_underscore($str){
		return str_replace(array(' ', '_'), '_',  strtolower($str));
	}
	}
	
	if(!function_exists('wpbw_get_pages')){
	function wpbw_get_pages($reverse=false){
		global $wp_ca_pages;
		
		$ret = array();
		
		if(!empty($wp_ca_pages)){
			$i = 0;
			foreach($wp_ca_pages as $title => $content){ $i++;
				$page = get_page_by_title( $title, ARRAY_A, 'page' );
				
				//pree($page);
				
				$t = 'X-'.$i;
				$e = 'N-'.$i;
				$d = 'D-'.$i;
				if(empty($page))
				$ret[$e] = $title;
				else{
					switch($page['post_status']){
						case 'publish':
							$ret[$page['ID']] = $title;
						break;
						case 'draft':
							$ret[$d] = $title;
						break;
						default:
							$ret[$t] = $title;
						break;
					}
				}
			}
		}
		
		
		if($reverse){
			if(!empty($ret)){
				$nr = array();
				foreach($ret as $id=>$title){
					$slug = wpbw_underscore($title);
					$nr[$slug] = $id;
				}
				$ret = $nr;
			}
		}
		
		return $ret;
		
	}
	}
	
	if(!function_exists('wpbw_get_actions')){
	function wpbw_get_actions($table, $where=array(), $last=false){
		
		global $wpdb, $wp_ca_tables;
		
		$ret = array();
	
		$where_clause = array();
		
		
		
		if(!empty($where)){
			foreach($where as $field=>$value){
				if(array_key_exists($field, $wp_ca_tables[$table])){
					$where_clause[$field] = $value;
				}
			}
		}		
		
		if(!empty($where_clause)){
			
			$where_arr = array();
			
			foreach($where_clause as $k=>$v){
				$where_arr[] = "`".$k."`='".$v."'";						
			}				
			$query = 'SELECT * FROM `'.$wpdb->prefix.$table.'` WHERE '.implode(' AND ', $where_arr).' ';


			
			if($last){
				$id = current(array_keys($wp_ca_tables[$table]));
				$query .= 'ORDER BY '.$id.' DESC LIMIT 1';
			}
			//echo $query;exit;
			if($query!=''){
				$ret = $wpdb->get_results($query);
				//echo $query;
			}			
		}	
		
		return $ret;	
		
	}
	}
	
	if(!function_exists('wpbw_get_actions_plus')){
	function wpbw_get_actions_plus($table, $where=array(), $last=false){
		
		global $wpdb, $wp_ca_tables;
		
		$ret = array();
	
		$where_clause = array();
		
		
		
		if(!empty($where)){
			foreach($where as $field=>$value){
				if(array_key_exists($field, $wp_ca_tables[$table])){
					$where_clause[$field] = $value;
				}elseif(strpos($field, '|') || strpos($field, '+')){
					$where_clause[$field] = $value;
				}
			}
		}		
		
		if(!empty($where_clause)){
			
			$where_arr = array();
			
			foreach($where_clause as $k=>$v){
				
				$operator = '';
				$o = explode('|', $k);
				$a = explode('+', $k);
				
				if(count($o)>1){
					$k = $o;
					$operator = ' OR ';
				}
				
				if(count($a)>1){
					$k = $a;
					$operator = ' AND ';
				}
				
				if(is_array($k) && count($k)>1){
					
					$where_inner = array();
					
					foreach($k as $h)
					$where_inner[] = "`".$h."`='".$v."'";	
					
					$where_arr[] = '('.implode($operator, $where_inner).')';
					
				}else{
					$where_arr[] = "`".$k."`='".$v."'";
				}
				
				
				
			}				
			$query = 'SELECT * FROM `'.$wpdb->prefix.$table.'` WHERE '.implode(' AND ', $where_arr).' ';
			
			if($last){
				$id = current(array_keys($wp_ca_tables[$table]));
				$query .= 'ORDER BY '.$id.' DESC LIMIT 1';
			}
			//echo $query;exit;
			if($query!=''){
				$ret = $wpdb->get_results($query);
				//echo $query;
			}			
		}	
		
		return $ret;	
		
	}	
	}
	
	if(!function_exists('wpbw_actions')){
	function wpbw_actions($table, $data=array(), $where=array()){

		global $wpdb, $wp_ca_tables;
		
		$fields = array();
		$where_clause = array();
		if(array_key_exists($table, $wp_ca_tables)){
			foreach($wp_ca_tables[$table] as $field=>$default){

					
					if(!empty($where) && !array_key_exists($field, $data)){
						continue;
					}
					
					switch($default){						
						default:
						case 'active':
							$fields[$field] = (isset($data[$field])?$data[$field]:$default);
						break;
						case 'ignore':
						
						break;
						case 'now':
							$fields[$field] = time();
						break;
						case 'logged_in_user':
							$fields[$field] = get_current_user_id();
						break;	
						case 'blog_id':
							$fields[$field] = get_current_blog_id();
						break;	
						case '*':
							
						break;									
					}
					
				
				
			}
			
			if(!empty($where)){
				foreach($where as $field=>$value){
					if(array_key_exists($field, $wp_ca_tables[$table])){
						$where_clause[$field] = $value;
					}
				}
			}
			
			if(!empty($fields)){
				
				$query = '';
				
				$query_arr = array();
				
				foreach($fields as $k=>$v){
					$query_arr[] = "`".$k."`='".$v."'";						
				}		
				//pree($where_clause);exit;
				if(empty($where_clause)){
					$query = 'INSERT INTO `'.$wpdb->prefix.$table.'` SET '.implode(',', $query_arr);
				}else{
						
					$where_arr = array();
					
					foreach($where_clause as $k=>$v){
						$where_arr[] = "`".$k."`='".$v."'";						
					}	
					$query = 'UPDATE `'.$wpdb->prefix.$table.'` SET '.implode(',', $query_arr).' WHERE '.implode(' AND ', $where_arr);
				}
				//echo $query;exit;
				if($query!=''){
					$wpdb->query($query);
					//echo $query;
				}
				
			}
			
		}
		
		
		
	}
	}
	
	if(!function_exists('wpbw_enqueue_scripts')){
	function wpbw_enqueue_scripts() {
		
		global $wpdb, $post, $woocommerce, $wp_ca_multiple, $product;


		$is_product_edit = is_object($post) && $post->post_type == 'product' && isset($_GET['action']) && $_GET['action'] == 'edit';
		$is_on_amdin = is_admin() && ((isset($_GET['page']) && $_GET['page'] == 'wc_ca_settings') || $is_product_edit);

		if(!$is_on_amdin && is_admin()){

		    return;
		}




		
			
		
		wp_enqueue_style('wp-ca-fontawesome-styles', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), date('Ymdhi'));
		
		if((is_user_logged_in() && !is_admin()) || (is_admin() && isset($_GET['page']) && $_GET['page']=='wc_ca_settings')){
			wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('Ymdhi'));
			wp_enqueue_script(
				'bootstrap',
				plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
				array('jquery')
			);
		}
		
		wp_enqueue_style('wp-ca-common-styles', plugins_url('css/common-style.css', dirname(__FILE__)), array(), date('Ymdhi'));


		

		wp_enqueue_script(
			'wp-ca-moment-scripts',
			plugins_url('js/moment.js', dirname(__FILE__)),
			array('jquery')
		);

		wp_enqueue_script(
			'wp-html2canvas-scripts',
			plugins_url('js/html2canvas.min.js', dirname(__FILE__)),
			array('jquery')
		);

		wp_enqueue_script(
			'wp-pdf-scripts',
			plugins_url('js/jspdf.js', dirname(__FILE__)),
			array('jquery')
		);
		
		wp_enqueue_script(
			'wp-ca-common-scripts',
			plugins_url('js/common-scripts.js', dirname(__FILE__)),
			array('jquery'),
			date('Yhmi')
		);	
		
		
		wp_dequeue_style('ns-option-css-page');
		wp_dequeue_style('ns-option-css-add-prod-page');
		
		wp_enqueue_style('ns-option-css-page', plugins_url('css/ns-option-css-page.css?t='.date('Ymdhi'), dirname(__FILE__)), array(), date('Ymdhi'));
		wp_enqueue_style('ns-option-css-custom-page', plugins_url('css/ns-option-css-custom-page.css?t='.date('Ymdhi'), dirname(__FILE__)), array(), date('Ymdhi'));	
		
		$checkout_url = '';
		$cart_url = '';
		if(!is_admin()){
			$checkout_url = wc_get_checkout_url();
			$cart_url = wc_get_cart_url();
		}
		
		$rid = (isset($_GET['rid'])?wpbw_sanitize_bw_data($_GET['rid']):0);
		$rid_valid = wpbw_is_user_order($rid);
		
		//pree($post->post_author);
		
		
		if(isset($post->ID)){
			$wp_ca_product_sub_type = get_post_meta($post->ID, '_wp_ca_product_sub_type', true);
			switch($wp_ca_product_sub_type){
				case 'on_demand_service':
					$wp_ca_multiple = ($wp_ca_multiple?false:$wp_ca_multiple);										
				break;
			}
			
		}
		
		$wp_ca_array = array(
								'ajaxurl' => admin_url( 'admin-ajax.php' ),								
								'this_url' => admin_url( 'options-general.php?page=wc_ca_settings' ),
				                'wp_ca_tab' => (isset($_GET['t'])?wpbw_sanitize_bw_data($_GET['t']):'0'),
								'wp_ca_rid' => $rid,
								'wp_ca_valid_rid' => $rid_valid,
								'wp_ca_page_id' => (isset($post->ID)?$post->ID:get_the_ID()),
								'wp_ca_wc_product_id' => (isset($post->ID)?$post->ID:get_the_ID()),
								'wp_ca_profile_id' => (isset($post->post_author)?$post->post_author:''),//get_the_author_meta( 'ID' ),
								'wp_ca_product_type' => '',
								'wp_ca_limit_alerts' => array(
									'h' => __('You can book maximum %d slots.'),
									'd' => __(''),
									'w' => __(''),
								),
								'wp_ca_edit' => isset($_GET['edit']),
								'wp_ca_login_url' => wp_login_url(),
								'checkout_url' => $checkout_url,
								'cart_url' => $cart_url,
								'wp_ca_ymd' => '{}',
								'wp_ca_loaded_ym' => date('m-Y'),
								'wp_ca_multiple'=>$wp_ca_multiple?'true':'false',								
								'currency_symbol'=>get_woocommerce_currency_symbol(),

								'wp_ca_not_vendor' => (is_user_logged_in() && class_exists('WCV_Vendors')?(!WCV_Vendors::is_pending( get_current_user_id()) && !WCV_Vendors::is_vendor( get_current_user_id() )):false)
							);
		//pree($wp_ca_array);
		if($wp_ca_array['wp_ca_wc_product_id']){
			$wp_ca_array['wp_ca_ymd'] = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], 'wp_ca_ymd', true);
			
			$wp_ca_array['wp_ca_in_cart'] = wpbw_woo_in_cart($wp_ca_array['wp_ca_wc_product_id'])?'true':'false';
			
		}
		//pree($wp_ca_array);exit;
							
		
		$wp_ca_array['wp_ca_wc_product_details'] = get_post($wp_ca_array['wp_ca_wc_product_id'], ARRAY_A);
		
		$wp_ca_array['wp_ca_wc_product_details'] = (empty($wp_ca_array['wp_ca_wc_product_details'])?array():$wp_ca_array['wp_ca_wc_product_details']);
		
		
		$get_product = wc_get_product( $wp_ca_array['wp_ca_wc_product_id'] );
		
		
		
		
		
		$product_details = array();
		$product_details['currency'] = get_woocommerce_currency_symbol();
		if(!empty($get_product)){			
			$product_details['price'] = $get_product->get_price();
			$product_details['wp_ca_product_type'] = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], '_wp_ca_product_type', true);
			$product_details['wp_ca_product_sub_type'] = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], '_wp_ca_product_sub_type', true);
		}
		
		
		
		$wp_ca_array['wp_ca_wc_product_details'] = array_merge($wp_ca_array['wp_ca_wc_product_details'], $product_details);
		
		$wp_ca_array['wp_ca_wc_product_details']['wp_ca_duration_type'] = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], 'wp_ca_duration_type', true);
		
		$_wp_ca_duration_type = $wp_ca_array['wp_ca_wc_product_details']['wp_ca_duration_type'];
		
		$_wp_ca_duration_type = (isset($_wp_ca_duration_type[0])?$_wp_ca_duration_type[0]:'');
		
		$_wp_ca_number_of = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], 'wp_ca_number_of', true);
		
		$_wp_ca_number_of = (isset($_wp_ca_number_of[0])?$_wp_ca_number_of[0]:'');
		
		
		
		//pree($wp_ca_array);exit;
		
		if($rid_valid){
		

			$order_data = wc_get_order( $rid );
			
			$items = $order_data->get_items();

			if($get_product->is_type( 'variable' )){
					//pree($rid_valid);
					
					$wc_product_variation_ids = array();
					
					foreach( $items as $item_key => $item_values ){
						
						$wc_product_variation_ids[$item_values->get_product_id()] = ($item_values->get_variation_id());
						
					}
					$_wp_ca_number_of = ($_wp_ca_number_of[$wc_product_variation_ids[$wp_ca_array['wp_ca_wc_product_id']]]);
					
					$_wp_ca_duration_type = 'h';//($_wp_ca_duration_type[$wc_product_variation_ids[$wp_ca_array['wp_ca_wc_product_id']]]);
					//pree($wc_product_variation_ids);		
					//exit;			
			}else{
				
					$_wc_valid_product_id = true;
					
					foreach( $items as $item_key => $item_values ){
						
						if($item_values->get_product_id()!=$wp_ca_array['wp_ca_wc_product_id']){
							$_wc_valid_product_id = false;
						}
						
					}
					if($_wc_valid_product_id){
						$_wp_ca_number_of = ($_wp_ca_number_of[$wp_ca_array['wp_ca_wc_product_id']]);
						$_wp_ca_duration_type = ($_wp_ca_duration_type[$wp_ca_array['wp_ca_wc_product_id']]);
					}
									
			}
			
		}
		
		$_wp_ca_number_of = (is_array($_wp_ca_number_of)?end($_wp_ca_number_of):$_wp_ca_number_of);
		$wp_ca_array['wp_ca_wc_product_details']['wp_ca_number_of'] = $_wp_ca_number_of;
		
		$_wp_ca_duration_type = (is_array($_wp_ca_duration_type)?end($_wp_ca_duration_type):$_wp_ca_duration_type);

		//pree($_wp_ca_duration_type);exit;
		
		$wp_ca_array['wp_ca_wc_product_details']['wp_ca_duration_type'] = wpbw_duration_text($_wp_ca_duration_type, $_wp_ca_number_of);
		
		$wp_ca_array['wp_ca_wc_product_details']['hours_enabled'] = get_post_meta($wp_ca_array['wp_ca_wc_product_id'], '_hours_enabled', true)?'true':'false';
		
		
		
		//pree($wp_ca_array);exit;
		
		wp_localize_script( 'wp-ca-common-scripts', 'wp_ca', $wp_ca_array );
		
		
		if(is_admin()){
		
			wp_enqueue_style('wp-ca-admin-styles', plugins_url('css/admin-style.css', dirname(__FILE__)), array(), date('Ymhi'));
			
			wp_enqueue_script(
				'wp-ca-admin-scripts',
				plugins_url('js/admin-scripts.js', dirname(__FILE__)),
				array('jquery'),
				date('Ymdhi')
			);			
			
			
			wp_dequeue_style('ns-option-css-a-page');
			wp_dequeue_style('ns-option-css-page');
			wp_dequeue_script( 'ns-option-js-page');
		
		}else{
			
			wp_enqueue_style('wp-ca-front-styles', plugins_url('css/front-style.css', dirname(__FILE__)), array(), date('Ymhi'));
			
			wp_enqueue_script(
				'wp-ca-front-scripts',
				plugins_url('js/front-scripts.js?t='.date('Ymhi'), dirname(__FILE__)),
				array('jquery')
			);				
		}
	}
	}
	
	add_action( 'admin_enqueue_scripts', 'wpbw_enqueue_scripts', 99 );	
	add_action( 'wp_enqueue_scripts', 'wpbw_enqueue_scripts', 99 );	
		
	if(!function_exists('wpbw_next_prev')){
	function wpbw_next_prev($month, $year){
				
		
		
		$date = mktime( 0, 0, 0, $month, 1, $year );
		$next = strftime( '%m-%Y', strtotime( '+1 month', $date ) );
		$prev = strftime( '%m-%Y', strtotime( '-1 month', $date ) );
		

		
		$ret = array(
			
			'ca_next' => $next,
			'ca_prev' => $prev,
			'ca_now' => $month.'-'.$year,
			'ca_now_text' => wpbw_to_text($month.'-'.$year),
			
		);
		
		
		
		return $ret;
		
	}
	}
	
	if(!function_exists('wpbw_to_text')){
	function wpbw_to_text($month_year){
		
		list($monthNum, $year) = explode('-', $month_year);
		
		$monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
		
		return ($monthName.' '.$year);
	}
	}
	
	
	if(!function_exists('wpbw_get_all_bookings')){
	function wpbw_get_all_bookings($object_id){
		
		global $wp_ca_multiple;
		
		$all_bookings = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_object_id'=>$object_id, 'ca_status'=>'booked', 'ca_blog_id'=>get_current_blog_id()));
		
		//pree($all_bookings);
		
		$bookings_arr = array();
		$bookings_by_user_arr = array();
		if(!empty($all_bookings)){
			foreach($all_bookings as $booking_row){
				
				$date_one = new DateTime(wpbw_get_start_dt($booking_row->ca_order_attached));
				
				$date_two = new DateTime(wpbw_get_end_dt($booking_row->ca_order_attached));
				
				//pree($date_one);pree($date_two);
				
				if($wp_ca_multiple){

					$ymd = $booking_row->ca_slot_year.$booking_row->ca_slot_month.$booking_row->ca_slot_day;
					$bookings_arr[] = $ymd;
					
					$bookings_by_user_arr[$booking_row->ca_user_id]['timestamp'][] = $ymd;
					$bookings_by_user_arr[$booking_row->ca_user_id]['order_id'][] = $booking_row->ca_order_attached;
					$bookings_by_user_arr[$booking_row->ca_user_id]['hour_slots'][$ymd] = maybe_unserialize($booking_row->ca_slot_hour);
					
				}else{
				
					$interval = new DateInterval('P1D');
					$daterange = new DatePeriod($date_one, $interval ,$date_two);
					
					foreach($daterange as $date){
						$bookings_arr[] = $date->format("Ymd");
						$bookings_by_user_arr[$booking_row->ca_user_id]['timestamp'][] = $date->format("Ymd");
						$bookings_by_user_arr[$booking_row->ca_user_id]['order_id'][] = $booking_row->ca_order_attached;
					}
					
				}
									
			}
		}	
		//pree($bookings_by_user_arr);exit;
		return array('bookings_arr' => $bookings_arr, 'bookings_by_user_arr' => $bookings_by_user_arr);	
	}
	}
	
	if(!function_exists('wpbw_booking_users')){
	function wpbw_booking_users($ids=array(), $data=array()){
		
		//pree($data);
		
		$ret = array();
		
		$args = array(
			'blog_id'      => get_current_blog_id(),
			'include'      => $ids,
			'exclude'      => array(),
			'orderby'      => 'login',
			'order'        => 'ASC',
			'fields'       => 'all',

		); 
		
		
		$get_users = get_users( $args );
		
		if(!empty($get_users)){
			foreach($get_users as $author){
				$ret[$author->ID]['link'] = get_author_posts_url($author->ID);
				$ret[$author->ID]['order_links'] = isset($ret[$author->ID]['order_links']) && is_array( $ret[$author->ID]['order_links'])? $ret[$author->ID]['order_links']:array();
				$ret[$author->ID]['order_hours'] = isset($ret[$author->ID]['order_hours']) && is_array( $ret[$author->ID]['order_hours'])? $ret[$author->ID]['order_hours']:array();
				
				
				if(!empty($data[$author->ID]['order_id'])){
					
					foreach($data[$author->ID]['order_id'] as $o=>$order_id){
						
						$data[$author->ID]['hour_slots'] = isset($data[$author->ID]['hour_slots']) && is_array($data[$author->ID]['hour_slots'])?$data[$author->ID]['hour_slots']:array();
													
						$data[$author->ID]['hour_slots'] = array_filter($data[$author->ID]['hour_slots'], function($x){ return (is_array($x) && !empty($x)); } );
						
						
						
						$ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]] = isset($ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]]) && is_array( $ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]])? $ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]]:array();
						
						if(!array_key_exists($order_id, $ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]]))
						$ret[$author->ID]['order_links'][$data[$author->ID]['timestamp'][$o]][$order_id] = get_edit_post_link($order_id);
						
						
						
						$ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]] = isset($ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]]) && is_array( $ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]])? $ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]]:array();
						
						
						if(!array_key_exists($order_id, $ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]]))
						$ret[$author->ID]['order_hours'][$data[$author->ID]['timestamp'][$o]] = $data[$author->ID]['hour_slots'][$data[$author->ID]['timestamp'][$o]] ?? array();
						
						
					}
				
				}
				$ret[$author->ID]['avatar'] = get_avatar_url($author->ID);
				$ret[$author->ID]['display_name'] = ($author->display_name?$author->display_name:$author->user_login);
			}
		}
		
		return $ret;

	}
	}
	
	/* draws a calendar */
	if(!function_exists('wpbw_draw_calendar')){
	function wpbw_draw_calendar($month, $year, $nav=true, $post=array()){
		
		global $wp_ca_booking_users_ids, $wp_ca_multiple;
		
		
		if(empty($post)){
			global $post;
		}
		
		$hours_available = get_post_meta($post->ID, 'hours_available', true);
		$is_owner = (wpbw_is_user_item($post->ID));
		$get_bookings_arr = wpbw_get_all_bookings($post->ID);
		extract($get_bookings_arr);
		
		//pree($get_bookings_arr);exit;
		//pree($bookings_by_user_arr);
		
		$booking_users_ids = array_keys($bookings_by_user_arr);
		$wp_ca_booking_users_ids = $booking_users_ids;
		
		$booking_users = wpbw_booking_users($booking_users_ids, $bookings_by_user_arr);
		
		//pree($booking_users);exit;
			
		$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_object_id'=>$post->ID, 'ca_user_id'=>get_current_user_id(), 'ca_status'=>'active', 'ca_blog_id'=>get_current_blog_id()), !$wp_ca_multiple);
		
		//pree($existing_booking);
		
		$is_edit = ((isset($_GET['edit'])) && $is_owner);
		
		$ebookings = array();
		
		//pree($month);
		if(!empty($existing_booking)){
			
			/*if($wp_ca_multiple){*/
				
				foreach($existing_booking as $ebookings_item){
					
					$ebookings_item = (array)$ebookings_item;
					$ebookings_item['ca_slot_hour'] = maybe_unserialize($ebookings_item['ca_slot_hour']);				
					$ebookings_item['ca_slot_hour'] = (is_array($ebookings_item['ca_slot_hour'])?$ebookings_item['ca_slot_hour']:array());	
										
					$ebnow = ($ebookings_item['ca_slot_day']*1).'-'.$ebookings_item['ca_slot_month'].'-'.$ebookings_item['ca_slot_year'];				
					
					$ebookings[$ebnow] = $ebookings_item;				
					
				}
				
			/*}else{
			
			
				$existing_booking = current($existing_booking);
				$existing_booking = (array)$existing_booking;					
				
				$existing_booking['ca_slot_hour'] = maybe_unserialize($existing_booking['ca_slot_hour']);				
				$existing_booking['ca_slot_hour'] = (is_array($existing_booking['ca_slot_hour'])?$existing_booking['ca_slot_hour']:array());	
				
				extract($existing_booking);		
				
				
				$ca_slot_now = $ca_slot_day.'-'.$ca_slot_month.'-'.$ca_slot_year;
			
			}*/
				
		}	
		//pree($ebookings);
		/*[ca_id] => 14
            [ca_dated] => 1516241215
            [ca_start_date] => 0
            [ca_end_date] => 0
            [ca_type] => wc_product
            [ca_object_id] => 796
            [ca_order_attached] => 0
            [ca_user_id] => 15
            [ca_status] => active
            [ca_slot_year] => 2018
            [ca_slot_month] => 01
            [ca_slot_day] => 24
            [ca_slot_hour] => 
            [ca_slot_minute] => 0
            [ca_duration_number] => 0
            [ca_duration_type] => A
            [ca_user_remarks] => 
            [ca_custom_label] => 
            [ca_blog_id] => 1*/
		//pree($month);
		
		$next_prev = wpbw_next_prev($month, $year);
		
		extract($next_prev);
		
		list($month_now, $year_now) = explode('-', $ca_now);
		
		/* draw table */

		$calendar = '';
		
		if($nav){


			
		$calendar .= '<div class="calendars-nav-panel"><a class="calendars-prev btn btn-info btn-xs" data-set="'.$ca_prev.'">Previous</a><a class="calendars-now hide" data-set="'.$ca_now.'">';
		
		
		
		$calendar .= wpbw_to_text($ca_now);
		
		$calendar .= '</a>';
		
		
		
		$calendar .= '<div class="dropdown">
		  <button class="btn btn-info btn-xs dropdown-toggle calendars-now-btn" type="button" data-toggle="dropdown">'.wpbw_to_text($ca_now).'
		  <span class="caret"></span></button>
		  <ul class="dropdown-menu">';
		
		for($m=1; $m<=12; $m++){
		
		list($tmonth, $tyear) = explode(' ', wpbw_to_text($m.'-'.$year_now));
		
		
		
		$calendar .= '<li class="'.(($m.'-'.$year_now==$ca_now)?'active':'').'"><a data-year="'.$tyear.'" data-month_t="'.$tmonth.'" data-month_n="'.$m.'" class="calendars-now" data-set="'.($m.'-'.$year_now).'">'.$tmonth.' '.$tyear.'</a></li>';
		
		}
			
		$calendar .= '
		  </ul>
		</div>';		
		
		$calendar .= '<a class="calendars-next btn btn-info btn-xs" data-set="'.$ca_next.'">'.__('Next', 'booking-works').'</a></div>';
		$calendar .= '<div class="wp_ca_loader"><div class="wp_ca_loader_anim"></div></div> ';
		
		}
		
		$calendar .= '<table cellpadding="0" cellspacing="0" class="wp_ca_table1 wp_ca_tables" data-month="'.$month_now.'" data-year="'.$year_now.'">';
	
		/* table headings */
		$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$calendar.= '<thead><tr class="calendar-row">';
		//$calendar.= '<th class="calendar-day-head"></th>';
		$calendar.= '<th class="calendar-day-head">'.implode('</th><th class="calendar-day-head">',$headings).'</th></tr></thead>';
		$calendar.= '<tfoot><tr class="calendar-row"><th class="calendar-day-foot">'.implode('</th><th class="calendar-day-foot">',$headings).'</th></tr></tfoot>';
	
		/* days and weeks vars now ... */
		$running_day = date('w', mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t', mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();
		
	
		/* row for week one */
		$calendar.= '<tbody><tr class="calendar-row">';
	
		
	
		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			//if($x==0)
			//$calendar.= '<th class="calendar-week-head"></th>';
			
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		endfor;
	
		$now = new DateTime();
		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			
			
			//echo $list_day.'-'.$month.'-'.$year.'<br />';

			$ymd = date('Ymd', mktime(0,0,0,$month,$list_day,$year));
			

			
			$this_date = new DateTime($list_day.'-'.$month.'-'.$year);
			
			$past = ($this_date<$now);					
		
			$day_class = '';
			
			if($list_day==date('d') && $month==date('m') && $year==date('Y')){
				$day_class = ' today';
			}
			//pree($ebookings);exit;
			$dmy_str = $list_day.'-'.$month.'-'.$year;
			//pree($dmy_str);
			//pree($ebookings);
			if(array_key_exists($dmy_str, $ebookings)){
				$day_class =  'booked cot-'.$ebookings[$dmy_str]['ca_order_attached'];

				
			}
			if($past){
				$day_class .= ' past';
			}

			if($is_edit){
				$day_class .= ' edit';
			}
			
			//pree($ymd);
			//pree($bookings_arr);
			if(in_array($ymd, $bookings_arr)){
				$day_class .= ' confirmed';
			}else{
				$day_class .= ' normal';
			}
			
		
			//pree($ymd);

			
			$calendar.= '<td class="calendar-day '.$day_class.'" data-num="'.$list_day.'">';
				/* add in the day number */
				$calendar.= '<div class="day-number">'.$list_day.'</div>';
	
				/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
				
				if(($wp_ca_multiple || $is_owner) && stristr($day_class, 'confirmed') && !empty($booking_users)){
					//pree($booking_users);
					foreach($booking_users as $uid=>$data){
						//pree($data);
						
						if(in_array($ymd, $bookings_by_user_arr[$uid]['timestamp'])){
							
							foreach($data['order_links'] as $oymd=>$odata){
							
								
							
								if($oymd==$ymd){
										
									if($is_owner){			
										
										$calendar.= '<p><a href="'.current($odata).'" title="'.$data['display_name'].'" target="_blank"><img src="'.$data['avatar'].'" alt="'.$data['display_name'].'" /></a></p>';
										
									}elseif($wp_ca_multiple){
										
										$hours_selected = (isset($bookings_by_user_arr[$uid]['hour_slots'][$ymd])?$bookings_by_user_arr[$uid]['hour_slots'][$ymd]:array());
										if(!empty($hours_selected)){
											//pree($hours_selected);
											//$calendar.= '<span>'.implode('</span><span>', $hours_selected).'</span>';
											$total_booked = round((count($hours_selected)/count($hours_available))*100);
											
											$calendar.= '<span>'.implode('</span><span>', $hours_selected).'</span>';
											$calendar.= '<b><i style="width:'.$total_booked.'%"></i></b>';
										}
									}
								}
									
					
								
								
							
							}
						}
					}
				}else{
					$calendar.= str_repeat('<p> </p>',2);
				}
				
				
				
				
			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;
	
		/* finish the rest of the days in the week */
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;
	
		/* final row */
		$calendar.= '</tr></tbody>';
	
		/* end the table */
		$calendar.= '</table>';
		
		if($is_owner){
			$calendar.= '<div class="wp_ca_calendar_items col col-md-12">';
			$calendar.= __('Click the days on the Calendar you wish to make your services unavailable', 'booking-works');
			$calendar.= '</div>';
		}
		
		if($nav){
			//$ebookings = current($ebookings);
			$calendar.= wpbw_calendar_items($ebookings);//$existing_booking
		}
		
		/* all done, return result */
		return $calendar;
	}
	}
	
	add_shortcode('WP-CALENDARS', 'wpbw_calendars');
	
	if(!function_exists('wpbw_tracking')){
	function wpbw_tracking(){
		
		$existing_booking = array();
		
		$postid = 0;
		
		if(!empty($_POST) && isset($_POST['wp_ca_tracking_number'])){
					
			if ( 
				! isset( $_POST['wp_ca_tracking_field'] ) 
				|| ! wp_verify_nonce( $_POST['wp_ca_tracking_field'], 'wp_ca_tracking_action' ) 
			) {
			
			   _e('Sorry, your nonce did not verify.', 'booking-works');
			   exit;
			
			} else {
				
				$postid = wpbw_sanitize_bw_data($_POST['wp_ca_tracking_number']);
				
				
				//pree($existing_booking);exit;
				
			}
			
		}elseif(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id']>0){
			$postid = wpbw_sanitize_bw_data($_GET['id']);

		}
		
		if($postid>0){
		
			$existing_booking = wpbw_get_actions_plus('calendars_management', array('ca_type'=>'wc_product', 'ca_order_attached|ca_id'=>$postid, 'ca_blog_id'=>get_current_blog_id()));
		
		}
					
		if(!empty($existing_booking)){
			global $wp_ca_dir;
			
			//pree($existing_booking);
			
			$current_booking = current($existing_booking);
			
			wpbw_generate_ticket_by_item($current_booking->ca_id, 'ca_id', $existing_booking);
			
		}else{
		
		
		$wp_ca_pages_updated = wpbw_get_pages(true);		
						
?>
	<div class="row wp_ca_booking_tracking">
    	<div class="col col-md-12 nopadding">
        <form method="post" action="<?php echo get_permalink($wp_ca_pages_updated['booking_tracking']); ?>">
        <?php wp_nonce_field( 'wp_ca_tracking_action', 'wp_ca_tracking_field' ); ?>

        	<input type="text" placeholder="<?php _e('Enter your booking number or order ID to track', 'booking-works'); ?>" name="wp_ca_tracking_number" value="<?php echo isset($_GET['id'])?wpbw_sanitize_bw_data($_GET['id']):''; ?>" />
            <input type="submit" value="Go" class="btn btn-primary" />
            <?php if(!is_user_logged_in()): ?>
            <small><?php _e('If you are not logged in so you cannot track the complete details', 'booking-works'); ?></small>
            <?php endif; ?>
            
            
		</form>            
        </div>
    </div>
<?php		
		}
	}
	}
	
	add_shortcode('WP-BOOKING-TRACKING', 'wpbw_tracking');
	
	if(!function_exists('wpbw_calendars')){
	function wpbw_calendars(){
		
		return;
		echo wpbw_draw_calendar(date('m'),date('Y'));
	
	}
	}
	
	if(!function_exists('wpbw_calendar_items')){
	function wpbw_calendar_items($existing_bookings = array()){
		//pree($existing_booking);
		global $wp_ca_dir, $post, $wpdb, $poi_acf;
		
		
		ob_start();
		
		
					
		
		$is_owner = (wpbw_is_user_item($post->ID));	
		
		$is_edit = (isset($_GET['edit']) && $is_owner);	
		
		
		$ca_user_remarks = '';
		
		//pree($existing_booking);
						
		if(!empty($existing_bookings)){
			$existing_booking = current($existing_bookings);
			extract($existing_booking);			
		}
				
		//pree($existing_booking);exit;
		include($wp_ca_dir.'/templates/wp_ca_calendar_items.php');
		$out1 = ob_get_contents();
		ob_end_clean();
		return $out1;
	}
	}
	
	if(!function_exists('wpbw_admin_menu')){
	function wpbw_admin_menu()
	{
		global $wp_ca_data;
		
		$title = $wp_ca_data['Name'];

		add_options_page($title, $title, 'install_plugins', 'wc_ca_settings', 'wpbw_settings' );
		

	}
	}
	
	if(!function_exists('wpbw_settings')){
	function wpbw_settings(){ 



		if ( !current_user_can( 'install_plugins' ) )  {



			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );



		}



		global $wp_ca_dir, $wp_ca_type, $wp_ca_multiple; 

		
		include($wp_ca_dir.'/inc/wp_ca_settings.php');
		

	}
	}
	
	
	if(!function_exists('wpbw_recursive_unset')){
	function wpbw_recursive_unset(&$array) {
		
		if(!is_array($value) && !is_numeric($value))
		unset($array[$value]);
		
		
		foreach ($array as &$value) {
			if (is_array($value)) {
				wpbw_recursive_unset($value);
			}else{
			}
		}
	}
	}
	
	if(!function_exists('wpbw_next_prev_ajax')){
	function wpbw_next_prev_ajax(){

	    $post_id = wpbw_sanitize_bw_data($_POST['pid']);
		
		$post = get_post($post_id);
		
		if(empty($post)){
			echo json_encode(array('msg'=>false));
			exit;
		}
		
		
		
		if(isset($_POST['edit']) && wpbw_is_user_item($post->ID)){
			//pree($_POST['ymd']);
			$ymd = wpbw_sanitize_bw_data($_POST['ymd']);//wp_ca_recursive_unset($_POST['ymd']);
			//pree($ymd);
			update_post_meta($post->ID, 'wp_ca_ymd', $ymd);
					
		}
		
		$month = wpbw_sanitize_bw_data($_POST['month']);
		$year = wpbw_sanitize_bw_data($_POST['year']);
		$next_prev = wpbw_next_prev($month, $year);
		extract($next_prev);
		list($month, $year) = explode('-', $ca_now);		
		$next_prev['calendar'] = wpbw_draw_calendar($month, $year, false, $post);
		echo json_encode($next_prev);
		exit;
		
	}
	}
	
	add_action( 'wp_ajax_wp_ca_next_prev', 'wpbw_next_prev_ajax' );
	add_action( 'wp_ajax_nopriv_wp_ca_next_prev', 'wpbw_next_prev_ajax' );
	
	if(!function_exists('wpbw_update_chunks')){
	function wpbw_update_chunks(){
		$ret = array('msg'=>true);
		
		
		
		$postid = 0;
		 
		if(isset($_POST['postid']) && is_numeric($_POST['postid']) && $_POST['postid']>0){
			$postid = wpbw_sanitize_bw_data($_POST['postid']);
			$valid_postid = wpbw_is_user_item($postid);
		}
		
		if($postid>0 && $valid_postid){
			$type = wpbw_sanitize_bw_data($_POST['type']);
			$my_post = array('ID'=>$postid);
			$product_data = array();
			$updated_text = wpbw_sanitize_bw_data($_POST['updated_text']);
			//pree($updated_text);
			if($updated_text!=''){
			
				switch($type){
					case 'post_title':
						$my_post['post_title'] = $updated_text;
					break;
					case 'post_content':
						$my_post['post_content'] = $updated_text;
					break;
					case 'product_price':		
						//pree($updated_text);
						$updated_text = floatval(preg_replace("/[^-0-9\.]/","",$updated_text));
						//pree($updated_text);
						if(is_numeric($updated_text) || is_float($updated_text)){	
							update_post_meta($my_post['ID'], '_regular_price', (float)$updated_text);
							update_post_meta($my_post['ID'], '_price', (float)$updated_text);					
						}
					break;
					case 'product_sale_price':		
						//pree($updated_text);
						$updated_text = floatval(preg_replace("/[^-0-9\.]/","",$updated_text));
						$price = get_post_meta($my_post['ID'], '_regular_price', true);
						//pree($price);
						//pree($updated_text);
						if((is_numeric($updated_text) || is_float($updated_text)) && ($updated_text<$price)){
							$updated_text = (float)$updated_text;
							update_post_meta($my_post['ID'], '_sale_price', ($updated_text>0?$updated_text:''));
						}else{
							
						}
					break;					
					case '_product_video':					
						$wc_productdata_options_arr = get_post_meta($my_post['ID'], 'wc_productdata_options', true);
						$wc_productdata_options_arr[0]['_product_video'] = $updated_text;
						update_post_meta($my_post['ID'], 'wc_productdata_options', $wc_productdata_options_arr);					
						//pree($wc_productdata_options_arr);
					break;
				}
				$my_post = array_filter($my_post, 'strlen');
				if(count($my_post)>1){
					wp_update_post( $my_post );			
				}
			}
		}
		
		echo json_encode($ret);
		exit;
	}
	}
	
	add_action( 'wp_ajax_wp_ca_update_chunks', 'wpbw_update_chunks' );
	
	if(!function_exists('wpbw_update_acf_fields')){
	function wpbw_update_acf_fields($postid){
		
		$product = wc_get_product($postid);
		$wp_ca_product_sub_type = get_post_meta($postid, '_wp_ca_product_sub_type', true);
		$is_rental = ($wp_ca_product_sub_type=='rental');
		$is_on_demand_service = ($wp_ca_product_sub_type=='on_demand_service');
		
		$enable_security_deposit = 'no';
		$enable_partial_deposit = 'no';
		
		if($is_rental){
			$security_deposit = function_exists('get_field') ? get_field( 'deposit_amount', $postid ) : 0;
			$security_deposit = ($security_deposit!=''?$security_deposit:0);
			
		
			$enable_security_deposit = 'yes';
		
			
			$multiply_by_persons = 'yes';
			$multiply_by_quantity = 'yes';
			

			$amount_type = 'fixed';
				
			$amount = is_numeric( $security_deposit ) ? floatval( $security_deposit ) : 0.0;
			
			if( $amount <= 0 ){
				$enable_security_deposit = 'no';
				$amount = '';
			}
			
			$product->update_meta_data( '_wc_security_deposits_multiply_by_quantity' , $multiply_by_quantity );
			$product->update_meta_data( '_wc_security_deposits_amount_type' , $amount_type );
			$product->update_meta_data( '_wc_security_deposits_deposit_amount' , $amount );
			
			if( $product->is_type( 'booking' ) && $product->has_persons() ){
				$product->update_meta_data( '_wc_security_deposits_multiply_per_persons' , $multiply_by_persons );
			}
			
		}elseif($is_on_demand_service){
			$partial_deposit_type = strtolower(get_field( 'deposit_type', $postid ));
			$partial_deposit = get_field( 'deposit_amount', $postid );
			$partial_deposit = ($partial_deposit!=''?$partial_deposit:0);
			
			$enable_partial_deposit = 'yes';
			
			
			$amount = is_numeric( $partial_deposit ) ? floatval( $partial_deposit ) : 0.0;
			
			if( $amount <= 0 ){
				$enable_partial_deposit = 'no';
				$amount = '';
			}
						
			$product->update_meta_data( '_wc_deposits_amount_type' , str_replace('age', '', $partial_deposit_type) );		
			$product->update_meta_data( '_wc_deposits_deposit_amount' , $amount );		
			//pree($amount);exit;
				
			
		}
					
		$product->update_meta_data( '_wc_security_deposits_enable' , $enable_security_deposit );
		
		$product->update_meta_data( '_wc_deposits_enable_deposit' , $enable_partial_deposit );
		
		
		
		
		$product->save();	
	}
	}
	
	if(!function_exists('wpbw_hours_selection')){
	function wpbw_hours_selection(){
		
		$ret = array('msg'=>false);
		
		$postid = wpbw_sanitize_bw_data($_POST['postid']);

		
		
		
		if(wpbw_is_user_item($postid)){
			
			$ymd = wpbw_sanitize_bw_data($_POST['ymd']);
			update_post_meta($postid, 'wp_ca_ymd', $ymd);
			
			
			
			$hours_selected = wpbw_sanitize_bw_data($_POST['hours_selected']);
			$hours_timezone = wpbw_sanitize_bw_data($_POST['timezone']);	
			
			update_post_meta($postid, 'hours_available', $hours_selected);
			update_post_meta($postid, '_wp-ca-timezone', $hours_timezone);
			
			$wp_ca_number_of = array();			
			parse_str($_POST['number_of'], $wp_ca_number_of);
			
			$wp_ca_number_of = array_values($wp_ca_number_of);
			$wp_ca_number_of = wpbw_sanitize_bw_data($wp_ca_number_of);
						
			update_post_meta($postid, 'wp_ca_number_of', $wp_ca_number_of);
			
			$wp_ca_duration_type = array();
			parse_str($_POST['duration_type'], $wp_ca_duration_type);			
			
			$wp_ca_duration_type = array_values($wp_ca_duration_type);
			$wp_ca_duration_type = wpbw_sanitize_bw_data($wp_ca_duration_type);

			update_post_meta($postid, 'wp_ca_duration_type', $wp_ca_duration_type);
			
			//pree($_POST);

			$wp_ca_product_type = wpbw_sanitize_bw_data($_POST['wp_ca_product_type']);
			$wp_ca_product_sub_type = wpbw_sanitize_bw_data($_POST['wp_ca_product_sub_type']);

			wpbw_update_product_types($postid, $wp_ca_product_type, $wp_ca_product_sub_type);
			
			
			
			//exit;
			$item_categories = wpbw_sanitize_bw_data($_POST['item_category']);
			
			$post_categories = (isset($_POST['item_category']) && is_numeric($_POST['item_category']))?array($item_categories):array(-1);
			$product = wc_get_product($postid);
			
	
			if($_POST['wp_ca_sale_status']=='false'){
				update_post_meta($postid, '_sale_price', '');
			}
	
			//pree($is_rental);
			//pree($postid);
	
			$product->set_category_ids($post_categories);
			$product->save();
		
			$ret['msg'] = true;
		}
		
		echo json_encode($ret);
		exit;
		
	}
	}
	
	add_action( 'wp_ajax_wp_ca_hours_selection', 'wpbw_hours_selection' );	
	
	
	
	
	if(!function_exists('wpbw_is_user_item')){
	function wpbw_is_user_item($postid){
		
		$post = get_post($postid);
		//pree(get_current_user_id());
		//pree($post);
		if(is_user_logged_in() && get_current_user_id()==$post->post_author)
		return true;
		else
		return false;
	
	}
	}
	
	if(!function_exists('wpbw_is_user_order')){
	function wpbw_is_user_order($order_id){
		
		$ret = false;

		if(is_user_logged_in()){


			
			$_customer_user = 0;
			
			$order_data = wc_get_order( $order_id );


			if(!empty($order_data)){
				
				$_customer_user = $order_data->get_customer_id();
			
				$wc_product_ids = array();
				
				foreach( $order_data->get_items() as $item_key => $item_values ){


					$wc_product_ids[] = ($item_values->get_product_id());
					
				}		
				
				if(!empty($wc_product_ids)){
					
					$pid = current($wc_product_ids);
					
				}


							
				$existing_booking = array();
				
				if($pid>0){
				
					$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_object_id'=>$pid, 'ca_user_id'=>get_current_user_id(), 'ca_order_attached'=>$order_id, 'ca_blog_id'=>get_current_blog_id()), true);
				}
				//pree($pid);pree($existing_booking);
				if(get_current_user_id()==$_customer_user && empty($existing_booking)){
					
					$ret = true;
					
				}


			}
			
		}else{
			
		}
		
		return $ret;
	
	}
	}
	
	if(!function_exists('wpbw_delete_user_item')){
	function wpbw_delete_user_item(){
		
		$ret = array('msg'=>false);
		
		$postid = wpbw_sanitize_bw_data($_POST['postid']);
		
		if(wpbw_is_user_item($postid)){
		
			wp_delete_post($postid, true);
			$ret['msg'] = true;
		}
		
		echo json_encode($ret);
		exit;
	}
	}
	
	
	add_action( 'wp_ajax_wp_ca_delete_user_item', 'wpbw_delete_user_item' );
	
	if(!function_exists('wpbw_hours_status')){
	function wpbw_hours_status($h){
		return ($h=='true'?true:false);
	}
	}
	
	if(!function_exists('wpbw_book_confirm')){
	function wpbw_book_confirm(){
		
		global $wp_ca_type, $wpdb;
		
		$rid_valid = false;
		
		$qty_count = 1;
		
		$ret = array('msg'=>__('Your slot has been booked successfully', 'booking-works'));
		
		//pree($_POST);
		
		if(!empty($_POST)){

		    $_POST = wpbw_sanitize_bw_data($_POST);

			$e_del = array();
			extract($_POST);


			$wp_ca_product_add_ons = $wp_ca_product_add_ons ?? array();
			$add_ons_array = WP_CA_METABOX::get_product_add_on($wc_product_id, $wp_ca_product_add_ons);



			if(!empty($existing_booking)){

				foreach($existing_booking as $ebooking){
					$id = str_replace(array('existing_booking[', ']'), '', $ebooking['name']);
					$value = ($ebooking['value']=='true');

					if(!$value){
						$e_del[] = $id;
					}
				}
			}



			
			$wp_ca_product_type = get_post_meta($wc_product_id, '_wp_ca_product_type', true);
			$wp_ca_product_sub_type = get_post_meta($wc_product_id, '_wp_ca_product_sub_type', true);
			
			$cm_array =  array('ca_type'=>'wc_product', 'ca_object_id'=>$wc_product_id, 'ca_user_id'=>get_current_user_id(), 'ca_status'=>'active', 'ca_blog_id'=>get_current_blog_id());
			$existing_booking = wpbw_get_actions('calendars_management', $cm_array);
			//pree($cm_array);


			if(!empty($existing_booking)){

				
				switch($wp_ca_product_type){
						case 'renting':
							switch($wp_ca_product_sub_type){
								default:
								case 'rental':
									
								break;
								
								case 'vacation_tour':
								case 'on_demand_service':
									
														
									
									foreach($existing_booking as $e_arr){
										$e_arr = !is_array($e_arr)?(array)$e_arr:$e_arr;
										$e_del[] = $e_arr['ca_id'];					
									}
																		
									

										
																	
								break;

								case 'vacation_tours':
									$e_dels = array();
									
									foreach($existing_booking as $e_arr){
										$e_arr = !is_array($e_arr)?(array)$e_arr:$e_arr;
										
										$ed_key = $e_arr['ca_slot_year'].$e_arr['ca_slot_month'].$e_arr['ca_slot_day'];
										
										if(!in_array($ed_key, $e_dels)){
											$e_dels[] = $ed_key;
										}else{
											$e_del[] = $e_arr['ca_id'];															
										}
									}
								break;

							}
						break;
					}								
				//pree($e_del);
				$existing_booking = '';
			}



			
			$e_del = array_filter($e_del, 'strlen');




			
			if(!empty($e_del)){
				$q = 'DELETE FROM `'.$wpdb->prefix.'calendars_management` WHERE ca_id IN('.implode(',', $e_del).') AND ca_user_id='.get_current_user_id().' AND ca_status="active" AND ca_blog_id='.get_current_blog_id();					
				//pree($q);
				$wpdb->query($q);
			}


			//exit;
			if($book_date!=''){

				$wp_ca_multiple = ($wp_ca_multiple=='true');
				$hours_enabled = get_post_meta($wc_product_id, '_hours_enabled', true);
				
				
				list($day, $month, $year) = explode('-', $book_date);
				
				//pree("$day, $month, $year");


				$meta = get_post_meta($wc_product_id);
				$wp_ca_number_of = maybe_unserialize($meta['wp_ca_number_of'][0]);
				$wp_ca_number_of = $wp_ca_number_of[0];
				
				$wp_ca_duration_type = maybe_unserialize($meta['wp_ca_duration_type'][0]);
				$wp_ca_duration_type = $wp_ca_duration_type[0];
	
				if($wp_ca_rid>0){
				
					$rid_valid = wpbw_is_user_order($wp_ca_rid);
				}


				
				
				
				if($rid_valid){
				
				
					$order_data = wc_get_order($wp_ca_rid);
					$order_status = $order_data->get_status();
					
					$items = $order_data->get_items();
					
					$get_product = wc_get_product( $wc_product_id );				
				
					if($get_product->is_type( 'variable' )){
							//pree($rid_valid);
							
							$wc_product_variation_ids = array();
							
							foreach( $items as $item_key => $item_values ){
								
								$wc_product_variation_ids[$item_values->get_product_id()] = ($item_values->get_variation_id());
								
							}
							
							$wp_ca_number_of = ($wp_ca_number_of[$wc_product_variation_ids[$wc_product_id]]);
							
							
							$wp_ca_duration_type = 'h';//($_wp_ca_duration_type[$wc_product_variation_ids[$wp_ca_array['wp_ca_wc_product_id']]]);
							//pree($wc_product_variation_ids);		
							//exit;			
					}else{
						
							$_wc_valid_product_id = true;
							
							foreach( $items as $item_key => $item_values ){
								
								if($item_values->get_product_id()!=$wp_ca_array['wp_ca_wc_product_id']){
									$_wc_valid_product_id = false;
								}
								
							}
							if($_wc_valid_product_id){
								$wp_ca_number_of = ($wp_ca_number_of[$wc_product_id]);
								$wp_ca_duration_type = ($wp_ca_duration_type[$wc_product_id]);
							}
											
					}
					
				}
							
				//pree($wp_ca_number_of);
				//pree($wp_ca_duration_type);
				//exit;
	
//				pree($wp_ca_multiple && !empty($wp_ca_slots));

				if($wp_ca_multiple && !empty($wp_ca_slots)){
					
					$wp_ca_slot_hours = is_array($wp_ca_slot_hours)?$wp_ca_slot_hours:array();
					
					$ret['msg'] = __('Your slots have been booked successfully', 'booking-works');
					
					if(!empty($wp_ca_slots)){
						
						//pree($wp_ca_slots);
						foreach($wp_ca_slots as $year=>$ydata){
							
							if(!empty($ydata)){
								foreach($ydata as $month=>$mdata){
										
									if(!empty($mdata)){
										foreach($mdata as $day=>$status){								
										
											$slot = $year.'-'.$month.'-'.$day;
											
											
											if($hours_enabled){
												if($status==true && isset($wp_ca_slot_hours[$slot]) && !empty($wp_ca_slot_hours[$slot])){
												
													$hours_selected = array_filter($wp_ca_slot_hours[$slot], 'wpbw_hours_status');
													
													if(!empty($hours_selected)){
														
														$hours_selected = array_keys($hours_selected);
													 
														$day = (strlen($day)==1?'0'.$day:$day);
														$month = (strlen($month)==1?'0'.$month:$month);
																			
														$data = array(
															'ca_type' => 'wc_product',
															'ca_object_id' => $wc_product_id,
															'ca_slot_year' => $year,
															'ca_slot_month' => $month,
															'ca_slot_day' => $day,
															'ca_slot_hour' => maybe_serialize($hours_selected),
															'ca_duration_number' => is_array($wp_ca_number_of)?current($wp_ca_number_of):$wp_ca_number_of,
															'ca_duration_type' => is_array($wp_ca_duration_type)?current($wp_ca_duration_type):$wp_ca_duration_type,
															'ca_user_remarks' => $book_notes,
															'ca_add_on' => maybe_serialize($add_ons_array),
														);
														
														
														switch($order_status){
															case 'processing':
															case 'completed':				
																$data['ca_status'] = 'booked';
																
															break;
															
															
															case 'on-hold':				
																if($rid_valid){
																	$data['ca_status'] = 'booked';
																}
															break;		
															
															case 'pending':
															case 'cancelled':	
															case 'refunded':				
															case 'failed':			
															
															break;													
														}
													
														
														if($rid_valid){												
															$data['ca_order_attached'] = $wp_ca_rid;
														}
														
														
														$where = array();
														
														if((is_array($existing_booking) && !empty($existing_booking)) || (!is_array($existing_booking) && $existing_booking!='')){
															$where['ca_id'] = $existing_booking;
															$where['ca_user_id'] = get_current_user_id();
															$where['ca_status'] = 'active';
															$where['ca_blog_id'] = get_current_blog_id();				
														}
														
														//pree(2);pree($data);
														
														wpbw_actions('calendars_management', $data, $where);		
														
													}
												}
												
											}else{
												
													
											 
												$day = (strlen($day)==1?'0'.$day:$day);
												$month = (strlen($month)==1?'0'.$month:$month);
																	
												$data = array(
													'ca_type' => 'wc_product',
													'ca_object_id' => $wc_product_id,
													'ca_slot_year' => $year,
													'ca_slot_month' => $month,
													'ca_slot_day' => $day,
													'ca_slot_hour' => maybe_serialize($hours_selected),
													'ca_duration_number' => is_array($wp_ca_number_of)?current($wp_ca_number_of):$wp_ca_number_of,
													'ca_duration_type' => is_array($wp_ca_duration_type)?current($wp_ca_duration_type):$wp_ca_duration_type,
													'ca_user_remarks' => $book_notes,
													'ca_add_on' => maybe_serialize($add_ons_array),

												); 
												
												
												switch($order_status){
													case 'processing':
													case 'completed':				
														$data['ca_status'] = 'booked';
														
													break;
													
													case 'pending':
													case 'on-hold':
													case 'cancelled':	
													case 'refunded':				
													case 'failed':																
													break;		
												}
											
												
												if($rid_valid){												
													$data['ca_order_attached'] = $wp_ca_rid;
												}
												
												//pree($data);
												
												$where = array();
												
												if((is_array($existing_booking) && !empty($existing_booking)) || (!is_array($existing_booking) && $existing_booking!='')){
													$where['ca_id'] = $existing_booking;
													$where['ca_user_id'] = get_current_user_id();
													$where['ca_status'] = 'active';
													$where['ca_blog_id'] = get_current_blog_id();				
												}
												//pree($existing_booking);
												//pree($data);
												//pree($where);
												//pree(3);pree($data);
												
												if(is_numeric($data['ca_slot_day']) && is_numeric($data['ca_slot_month']) && is_numeric($data['ca_slot_year']))
												wpbw_actions('calendars_management', $data, $where);
												
											
								
										
											}
										}
									
									}
								}
							}
							
						}
					}
					
					//pree($wp_ca_slots);
					//pree($wp_ca_slot_hours);
					//exit;
						
	
					
				}else{
				
					$day = (strlen($day)==1?'0'.$day:$day);
					$month = (strlen($month)==1?'0'.$month:$month);



					$data = array(
						'ca_type' => 'wc_product',
						'ca_object_id' => $wc_product_id,
						'ca_slot_year' => $year,
						'ca_slot_month' => $month,
						'ca_slot_day' => $day,
						'ca_slot_hour' => maybe_serialize($hours_selected),
						'ca_duration_number' => is_array($wp_ca_number_of)?current($wp_ca_number_of):$wp_ca_number_of,
						'ca_duration_type' => is_array($wp_ca_duration_type)?current($wp_ca_duration_type):$wp_ca_duration_type,
						'ca_user_remarks' => $book_notes,
						'ca_add_on' => maybe_serialize($add_ons_array),

					);


					
					$where = array();
					
					if((is_array($existing_booking) && !empty($existing_booking)) || (!is_array($existing_booking) && $existing_booking!='')){
						$where['ca_id'] = $existing_booking;
						$where['ca_user_id'] = get_current_user_id();
						$where['ca_status'] = 'active';
						$where['ca_blog_id'] = get_current_blog_id();				
					}


					
					//pree(4);pree($data);pree($existing_booking);


					
					wpbw_actions('calendars_management', $data, $where);
					
					
				}
				
				global $woocommerce;
//				$woocommerce->cart->empty_cart();
                $woo_cart = $woocommerce->cart->get_cart();


                if(!empty($woo_cart)){

                    foreach ($woo_cart as $item_key => $item){

                        $product_id = $item['product_id'];

                        if(!in_array($product_id, $wp_ca_product_add_ons)){

                           $woocommerce->cart->remove_cart_item($item_key);

                        }

                    }
                }






				
				//pree($qty_count);
				$cm_array =  array('ca_type'=>'wc_product', 'ca_object_id'=>$wc_product_id, 'ca_user_id'=>get_current_user_id(), 'ca_status'=>'active', 'ca_blog_id'=>get_current_blog_id());
				$existing_booking = wpbw_get_actions('calendars_management', $cm_array);
				
				//pree($qty_count);
				if(!empty($existing_booking) && count($existing_booking)>0){
					$qty_count = count($existing_booking);
					//pree($qty_count);
					//pree($existing_booking);
					
					
					//$ret['msg'] = 'halt';
				}
				
				//exit;
				//pree($qty_count);
				//pree($wc_product_id);
				
				if(!$rid_valid)
				$woocommerce->cart->add_to_cart($wc_product_id, $qty_count);			
				
			}
		}

		echo json_encode($ret);
		exit;		
	}
	}
	
	add_action( 'wp_ajax_wp_ca_book_confirm', 'wpbw_book_confirm' );
	
	
	
	
	add_action('woocommerce_order_status_pending', 'wpbw_checkout_order_processed');
	add_action('woocommerce_order_status_on-hold', 'wpbw_checkout_order_processed');
	add_action('woocommerce_order_status_processing', 'wpbw_checkout_order_processed');
	add_action('woocommerce_order_status_completed', 'wpbw_checkout_order_processed');
	
	if(!function_exists('wpbw_checkout_order_processed')){
	function wpbw_checkout_order_processed($order_id){
		
		if(is_user_logged_in()){
			
			
			$order_data = wc_get_order( $order_id );
			$items = $order_data->get_items();
			
			if(empty($items))
			return;
			
			
			$wc_product_ids = array();
			foreach( $order_data->get_items() as $item_key => $item_values ){
				$wc_product_ids[] = ($item_values->get_product_id());
			}
			//exit;
			if(!empty($wc_product_ids)){
				foreach($wc_product_ids as $wc_product_id){
					$where=array(
						'ca_type'=>'wc_product', 
						'ca_object_id'=>$wc_product_id, 
						'ca_user_id'=>get_current_user_id(), 
						'ca_status'=>'active', 
						'ca_blog_id'=>get_current_blog_id()
					);
					
					if(is_admin()){
						//pree($order_data->get_status());exit;
						
						switch($order_data->get_status()){
						
							case 'processing':
							case 'completed':				
								$where['ca_user_id'] = $order_data->get_customer_id();				
								wpbw_actions('calendars_management', array('ca_order_attached'=>$order_id, 'ca_status'=>'booked'), $where);
								
							break;
							
							case 'pending':
							case 'on-hold':
							case 'cancelled':	
							case 'refunded':				
							case 'failed':			
								unset($where['ca_status']);
								wpbw_actions('calendars_management', array('ca_order_attached'=>$order_id, 'ca_status'=>'active'), $where);
							break;						
							
						}
					}
					else{
						wpbw_actions('calendars_management', array('ca_order_attached'=>$order_id, 'ca_status'=>'booked'), $where);
					}
					//pree($_SESSION['expected_subsription']);exit;
					//pree($order_data->get_status());exit;
					if(isset($_SESSION['expected_subsription'])){
						list($fee_product_id, $to_publish_product_id) = $_SESSION['expected_subsription'];
						
						update_post_meta($order_id, 'expected_subsription_pid', $to_publish_product_id);
						update_post_meta($order_id, 'expected_subsription_fid', $fee_product_id);
						unset($_SESSION['expected_subsription']);
						//exit;
					}
					
					switch($order_data->get_status()){
						
							case 'processing':
							case 'completed':
							
								$wc_product_id = get_post_meta($order_id, 'expected_subsription_pid', true);
								$expected_subsription = get_post_meta($order_id, 'expected_subsription_fid', true);
								//pree($wc_product_id);
								//pree($expected_subsription);exit;
								if($expected_subsription==1541){
									update_post_meta($wc_product_id, 'recent_subsription', $order_id);
									update_post_meta($order_id, 'redeemed', true);
									wp_update_post( array('ID'=>$wc_product_id, 'post_status'=>'publish') );
									delete_post_meta($wc_product_id, 'expected_subsription');
									delete_post_meta($order_id, 'expected_subsription_fid');
									delete_post_meta($order_id, 'expected_subsription_pid');
								}
							break;
							
					}
							
				}
			}
		}
		
	}
	}
	
	if(!function_exists('wpbw_update_product_types')){
	function wpbw_update_product_types($id, $wp_ca_product_type, $wp_ca_product_sub_type){
		//pree($id);pree($wp_ca_product_type);pree($wp_ca_product_sub_type);
		
		update_post_meta($id, '_wp_ca_product_type', $wp_ca_product_type);
		update_post_meta($id, '_wp_ca_product_sub_type', $wp_ca_product_sub_type);
	}
	}
	
	if(!function_exists('wpbw_get_the_content_by_id')){
	function wpbw_get_the_content_by_id($post_id) {
	  $page_data = get_page($post_id);
	  if ($page_data) {
		return $page_data->post_content;
	  }
	  else return false;
	}	
	}
	
	if(!function_exists('wpbw_add_child_shortcodes')){
	function wpbw_add_child_shortcodes(){
		if(!empty($_POST) && isset($_POST['ns-product-name']) && $_POST['ns-product-name']!=''){
			remove_shortcode('ns-add-product');
			if(isset($_POST['submit'])){
				
				global $wp_ca_messenger;

				$ns_product_name = wpbw_sanitize_bw_data($_POST['ns-product-name']);
				$wp_ca_product_type = wpbw_sanitize_bw_data($_POST['wp_ca_product_type']);
				$wp_ca_product_sub_type = wpbw_sanitize_bw_data($_POST['wp_ca_product_sub_type']);

				$post_by_title = get_page_by_title( $ns_product_name, OBJECT, 'product' );
				
							


				if(!empty($post_by_title) && isset($post_by_title->post_author) && $post_by_title->post_author!=get_current_user_id()){
					
					if(function_exists('ns_save_product')){
						ns_save_product();
					}

										
					$wp_ca_messenger['item_msg'] = __('Successfully added.', 'booking-works');
					$post_by_title = get_page_by_title( $ns_product_name, OBJECT, 'product' );

					wpbw_update_product_types($post_by_title->ID, $wp_ca_product_type, $wp_ca_product_sub_type);
						
					if($post_by_title->post_status!='draft'){
						$temp_post = array(
						  'ID'           => $post_by_title->ID,
						  'post_status' => 'draft',
						);
						
						wp_update_post( $temp_post );			
					}
					

					switch($_POST['wp_ca_product_type']){
						case 'renting':
							switch($_POST['wp_ca_product_sub_type']){
								default:
								case 'rental':
									wp_redirect(home_url().'/update-renting-terms?id='.$post_by_title->ID);exit;
								break;
								
								case 'on_demand_service':
									wp_redirect(home_url().'/update-service-terms?id='.$post_by_title->ID);exit;
								break;

								case 'vacation_tour':
									wp_redirect(home_url().'/update-agreement-terms?id='.$post_by_title->ID);exit;
								break;

							}
						break;
					}						
					
				}else{
					$wp_ca_messenger['item_msg'] = __('Another item exists with this title in the system, please choose a different one.', 'booking-works').' <a href="'.admin_url('edit.php?post_type=product').'" target="_blank">'.__('Click here', 'booking-works').'</a> '.__('to view existing item or', 'booking-works').' <a class="wp_ca_goback">'.__('change title', 'booking-works').'</a>.';
				}
			}
			add_shortcode('ns-add-product', 'wpbw_ns_add_product_extended');
		}elseif(isset($_POST['wp_ca_redirect_after'])){
			//pree($_POST);
			switch($_POST['wp_ca_redirect_after']){
				case 'edit-schedule':
					//pree($_SERVER);
					$post_id = wpbw_sanitize_bw_data($_POST['post_id']);
					$for_post_type = get_post($post_id);
					if(is_object($for_post_type) && $for_post_type->post_type!='product'){
						$post_id = false;
						$query_id = wpbw_sanitize_bw_data($_POST['query_id']);
						$for_post_type = get_post($query_id);
						if($for_post_type->post_type=='product'){
							$post_id = $query_id;
						}
					}
					
					if($post_id){
						$link = get_permalink($post_id);//exit;
						$link .= (stristr($link, '?')?'&':'?').'edit';
						wp_redirect($link);exit;
					}else{
						//pree($_POST);exit;
					}
					
				break;
				default:
					wp_redirect(home_url().'/'.wpbw_sanitize_bw_data($_POST['wp_ca_redirect_after']));exit;
				break;
			}
		}
	}
	}
	
	add_action( 'wp_loaded', 'wpbw_add_child_shortcodes' );
	
	if(!function_exists('wpbw_ns_add_product_extended')){
	function wpbw_ns_add_product_extended(){
		global $wp_ca_messenger;
?>		
<h2><?php echo $wp_ca_messenger['item_msg']; ?> <?php _e('Here is a list of items by you.', 'booking-works'); ?></h2>
<?php        
		
		echo wpbw_sales_by_user();
		
	}
	}
	
	add_shortcode('WP-CA-PRODUCTS-BY-USERS', 'wpbw_sales_by_user');
	


	if(!function_exists('wpbw_footer_scripts')){
	function wpbw_footer_scripts(){
		global $post;
				
?>
	<style type="text/css">
		body{
			font-size:initial;
		}	
		@media only screen and (max-device-width: 480px) {
			
			
		}			
	</style>
    <script type="text/javascript" language="javascript">
		jQuery(document).ready(function($){

		});
	</script>
<?php		
	}
	}
	
	add_action('wp_footer', 'wpbw_footer_scripts');
	
	add_shortcode('WP-CA-SALES-BY-USERS', 'wpbw_sales_by_user');
	
	if(!function_exists('wpbw_get_orders_by_product')){
	function wpbw_get_orders_by_product( $product_id = array() ) {
	
		$product_id = is_array($product_id)?$product_id:array($product_id);
		$product_ids = implode(',', $product_id);
		
		global $wpdb;
	
		$raw = "
			SELECT
			  `items`.`order_id`,
			  MAX(CASE WHEN `itemmeta`.`meta_key` = '_product_id' THEN `itemmeta`.`meta_value` END) AS `product_id`
			FROM
			  `{$wpdb->prefix}woocommerce_order_items` AS `items`
			INNER JOIN
			  `{$wpdb->prefix}woocommerce_order_itemmeta` AS `itemmeta`
			ON
			  `items`.`order_item_id` = `itemmeta`.`order_item_id`
			WHERE
			  `items`.`order_item_type` IN('line_item')
			AND
			  `itemmeta`.`meta_key` IN('_product_id')
			GROUP BY
			  `items`.`order_item_id`
			HAVING
			  `product_id` IN (%s)";
	
		$sql = $wpdb->prepare( $raw, $product_ids );
		//pree($sql);
	
		return array_map(function ( $data ) {
			return wc_get_order( $data->order_id );
		}, $wpdb->get_results( $sql ) );
	
	}	
	}
	
	if(!function_exists('wpbw_sales_by_user')){
	function wpbw_sales_by_user(){
		
		if(!is_user_logged_in()){
			return __('You need to login to access this page.', 'booking-works');
		}
		
		ob_start();
		
		global $wc_vendors_status, $wc_vendors_edit_product;

		

		$args = array(
			'author' => get_current_user_id(),
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC'
		);
		
		
		//pree($args);
		$ores = array();
		$user_products = array();
		$products_by_user = get_posts($args);
		//pree($products_by_user);
		if(!empty($products_by_user)){
			foreach($products_by_user as $products){
				//$user_products[$products->ID] = $products;
				$product = wc_get_product($products->ID);
				
				if(!empty($product)){
					$ores[] =  wpbw_get_orders_by_product($product->get_id());
				}
				//pree($products->ID);
			}
		}
		
		/*
		//pree($user_products);
		if(!empty($user_products)){
			$user_products_ids = array_keys($user_products);
			//pree($user_products_ids);
			$ores = wpbw_get_orders_by_product($user_products_ids);
			//pree($ores);
		}*/
		//pree($ores);
		//exit;
		
		if(!empty($ores)){
?>
<div class="alignwide">
<form action="" method="post" class="wp_ca_products_form wp_ca_my_sales">
<?php wp_nonce_field( 'wp_ca_my_products_action', 'wp_ca_my_products_field' ); ?>
<div class="col-md-12 nopadding">

  <div class="btn-group">
  	<button data-status="wc-all" type="button" class="btn btn-primary active"><?php _e('ALL', 'booking-works'); ?></button>
    <button data-status="wc-completed" type="button" class="btn btn-primary"><?php _e('COMPLETED ORDERS', 'booking-works'); ?></button>
    <button data-status="wc-cancelled" type="button" class="btn btn-primary"><?php _e('CANCELLED ORDERS', 'booking-works'); ?></button>
  </div>

<input type="submit" class="btn btn-xs pull-right hide" value="Pause" />
<input type="hidden" name="wp_ca_my_products_status" value="pause" />
</div>

<table class="wp_ca_list_user_items">

<thead>
<tr>
	<th>&nbsp;</th>
    <th><?php _e('ID'); ?></th>
    <th><?php _e('Date'); ?></th>
	<th><?php _e('Items'); ?></th>
    <th><?php _e('Amount'); ?></th>
    <th><?php _e('Actions'); ?></th>
    
</tr>
</thead>
<tfoot>
<tr>
	<th>&nbsp;</th>
    <th><?php _e('ID'); ?></th>
    <th><?php _e('Date'); ?></th>    
	<td><?php _e('Items'); ?></td>
    <td><?php _e('Amount'); ?></td>
    <th><?php _e('Actions'); ?></th>
 
</tr>
</tfoot>
<?php		
			$order_count = array();
			
			$order_statuses = wc_get_order_statuses();
			//pree($order_statuses);
			if(!empty($order_statuses)){
				foreach($order_statuses as $order_status=>$status_label){
					//$order_status = str_replace('wc-', '', $order_status);
					$order_count[$order_status] = 0;
				}
			}
			
			//pree($order_count);
			

			$wp_ca_pages_updated = wpbw_get_pages(true);
			$track_link = get_permalink($wp_ca_pages_updated['booking_tracking']);
				
			$symbol = get_woocommerce_currency_symbol();
			foreach($ores as $order){
				
				if(empty($order)){ continue; }
				
				
				
				$order = (array_key_exists(0, $order)?current($order):$order);
				
				$order_count[$order->get_status()] = array_key_exists($order->get_status(), $order_count)?$order_count[$order->get_status()]:0;
			
			
				
				//pree($order->get_id());exit;
				//pree($order->get_id());
				//pree($order->get_id());pree($order['id']);exit;
				
					
				$order_data = wc_get_order( $order->get_id() );
				//pree($order_data);exit;
				//pree($order_data->get_type());
				
				if($order_data->get_type()!='shop_order'){
					continue;
				}
				//pree($order_data->order_date);
				//pree($order_data);
				
				if(empty($order_data)){
					continue;
				}
				
				$items = $order_data->get_items();
				
				if(empty($items))
				return;
				
				
				$wc_product_ids = array();
				foreach( $order_data->get_items() as $item_key => $item_values ){
					$wc_product_ids[] = ($item_values->get_product_id());
				}
				
				foreach($wc_product_ids as $r_id){
				
				
								
				//$p = wc_get_product($r_id);
				$p = new WC_Product($r_id);
				$wp_ca_product_type = get_post_meta($r_id, '_wp_ca_product_type', true);
				
				
				$regular_price = $p->get_regular_price();
				$price = $p->get_price();
				
				if(!$price && $regular_price){
					update_post_meta($r_id, '_price', $regular_price);
					$price = $regular_price;
				}
				
				$existing_booking_arr = array('ca_type'=>'wc_product', 'ca_order_attached|ca_id'=>$order->get_id(), 'ca_blog_id'=>get_current_blog_id());
				$existing_booking = wpbw_get_actions_plus('calendars_management', $existing_booking_arr, true);
				//pree($existing_booking_arr);
				//pree($existing_booking);
				
				$order_count[$order->get_status()] += 1;
				
?>

<tr class="wp_ca_products_<?php echo $order->get_status(); ?>">
<td><input type="checkbox" name="wp_ca_my_products[]" value="<?php echo $order->get_id(); ?>" /></td>
<td><?php echo $order->get_id(); ?></td>
<td><?php echo date('d M, Y', strtotime($order_data->get_date_created())); ?></td>
<td><a href="<?php echo get_permalink($p->get_id()); ?>" target="_blank" title="<?php echo $p->get_title(); ?>"><?php echo $p->get_title(); ?></a></td>
<td><span><?php echo $symbol.$price; ?></span></td>
<td data-id="<?php echo $order->get_id(); ?>" data-url="<?php echo $track_link.'?id='.$order->get_id(); ?>" data-edit-url="<?php echo get_permalink($order->get_id()).'?edit'; ?>">
<?php if(!empty($existing_booking)){ ?><a class="view btn btn-info btn-xs" target="_blank" title="<?php _e('Click here to view this item'); ?>"><?php _e('Track Details'); ?></a><?php } ?></td>
</tr>
<?php				
			}
			
			}
			//pree($order_count);
			if(!empty($order_count)){
				foreach($order_count as $order_status=>$order_total){
					if($order_total==0){
?>
<tr class="wp_ca_products_<?php echo $order_status; ?> no-items-to-show"><td></td><td colspan="5"><?php _e('There are no items to show.'); ?></td></tr>
<?php		
					}
				}
			}
?>
</table>
</form>
</div>
<?php	//pree($order_count);			

		}
		
		
		$out1 = ob_get_contents();
		
		
		ob_end_clean();		
		
		return $out1;		
	}	
	}
	
	if(!function_exists('wpbw_products_by_user')){
	function wpbw_products_by_user(){
		
		if(!is_user_logged_in()){
			return __('You need to login to access this page.', 'booking-works');
		}
		
		if(wpbw_valid_user()){
			
	
			
			ob_start();
			
			global $poi_acf, $ns_add_product, $wc_vendors_status, $wc_vendors_edit_product;
	
			$args = array(
				'author' => get_current_user_id(),
				'post_type' => 'product',
				'post_status' => array('publish', 'draft'),
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			
			
			
			$res = get_posts($args);
			
			//pree($res);
			
			if(!empty($res)){
				
				$active_status = (isset($_GET['ids'])?'':'active');
				$paused_status = (isset($_GET['ids'])?'active':'');
	?>
	<form action="" method="post" class="wp_ca_products_form">
	<?php wp_nonce_field( 'wp_ca_my_products_action', 'wp_ca_my_products_field' ); ?>
	<div class="col-md-12 nopadding">
	
	  <div class="btn-group">
		<button data-status="publish" type="button" class="btn btn-primary  <?php echo $active_status; ?>"><?php _e('ACTIVE PRODUCTS'); ?></button>
		<button data-status="draft" type="button" class="btn btn-primary <?php echo $paused_status; ?>"><?php _e('PAUSED PRODUCTS'); ?></button>    
	  </div>
	<?php if($ns_add_product): ?>  
		<a class="btn btn-success pull-right" href="/add-product"><?php _e('ADD PRODUCT'); ?></a>      
	<?php elseif($wc_vendors_status): ?>
		<a class="btn btn-success pull-right" href="<?php echo get_admin_url(); ?>post-new.php?post_type=product" target="_blank"><?php _e('ADD PRODUCT'); ?></a>
	<?php endif; ?>  
    <a class="btn btn-warning pull-left dcta" data-cta="pause" style="margin-right:6px;"><?php _e('Pause'); ?></a>
	<input data-id="pause" type="submit" class="btn btn-xs pull-right hide fhbutton" value="<?php _e('Pause'); ?>" />
	<input data-id="play" type="hidden" name="wp_ca_my_products_status" value="<?php _e('pause'); ?>" />
	</div>
	
	<?php if(!empty($res)): ?>
	<table class="wp_ca_list_user_items">
	
	<thead>
	<tr>
		<th>&nbsp;</th>
		<th><?php _e('Title'); ?></th>
		<th><?php _e('Price'); ?></th>
		<th><?php _e('Actions'); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th>&nbsp;</th>
		<td><?php _e('Title'); ?></td>
		<td><?php _e('Price'); ?></td>
		<td><?php _e('Actions'); ?></td>
	</tr>
	</tfoot>
	<?php				
				$symbol = get_woocommerce_currency_symbol();
				$order_count = 0;
				foreach($res as $r){
					$p = wc_get_product($r->ID);
					$wp_ca_product_type = get_post_meta($r->ID, '_wp_ca_product_type', true);
					$wp_ca_product_sub_type = get_post_meta($r->ID, '_wp_ca_product_sub_type', true);
					
					
					$regular_price = $p->get_regular_price();
					$price = $p->get_price();
					
					if(!$price && $regular_price){
						update_post_meta($r->ID, '_price', $regular_price);
						$price = $regular_price;
					}
					
					$tr_class = 'wp_ca_'.$wp_ca_product_type.'_'.$wp_ca_product_sub_type;
					
					$order_count++;
					
	?>
	<tr class="wp_ca_products_<?php echo $r->post_status; ?> <?php echo $tr_class; ?>" <?php echo ($active_status=='active'?'':($r->post_status=='draft'?'style="display:table-row;"':'style="display:none;"')); ?>>
	<td align="center"><input type="checkbox" name="wp_ca_my_products[]" value="<?php echo $r->ID; ?>" /></td>
	<td><a href="<?php echo get_permalink($r->ID); ?>" target="_blank" title="<?php echo $r->post_title; ?>"><?php echo $r->post_title; ?></a></td>
	<td><span><?php echo $symbol.$price; ?></span></td>
	<td data-id="<?php echo $r->ID; ?>" data-url="<?php echo get_permalink($r->ID); ?>" data-edit-url="<?php echo (($wc_vendors_status && $wc_vendors_edit_product)?admin_url().'post.php?post='.$r->ID.'&action=edit':get_permalink($r->ID).'?edit'); ?>" data-edit-terms="/update-renting-terms/?id=<?php echo $r->ID; ?>" data-publish="<?php echo get_permalink(1541); ?>?ids=<?php echo $r->ID; ?>">
	
    <?php if(!wpbw_valid_user_allowed($r->ID) && $r->post_status=='draft'): ?>
    <a class="publish_s btn btn-success btn-xs" data-es="<?php echo get_post_meta($r->ID, 'expected_subsription', true); ?>" target="_blank" title="<?php _e('Click here to publish this item'); ?>"><?php _e('Publish'); ?></a>
    <?php endif; ?>
    
    
    <a class="edit_s btn btn-warning btn-xs" target="_blank" title="<?php _e('Click here to edit this item'); ?>"><?php _e('Edit'); ?></a>
	
	<?php
	switch($wp_ca_product_type){
		default:
		break;
		case 'renting':
		
	?>
    
    <?php		
		if($poi_acf){
	?>
	
	<?php			
			switch($wp_ca_product_sub_type){
				default:			
				case 'rental':		
	?>                                    
	<a class="edit_t btn btn-warning btn-xs" data-type="renting" target="_blank" title="<?php _e('Click here to edit rental contract'); ?>"><?php _e('Terms'); ?></a>
	<?php
				break;
				case 'on_demand_service':		
	?>
	<a class="edit_t btn btn-warning btn-xs" data-type="service" target="_blank" title="<?php _e('Click here to edit service agreement'); ?>"><?php _e('Terms'); ?></a>
	<?php
				break;
				case 'vacation_tour':		
	?>
	<a class="edit_t btn btn-warning btn-xs" data-type="agreement" target="_blank" title="<?php _e('Click here to edit risk and indemnity agreement'); ?>"><?php _e('Terms'); ?></a>
	<?php
				break;						
				
			}
		}
	?>
	
	<?php	
		break;
	}
	?>
	<a class="view btn btn-info btn-xs" target="_blank" title="<?php _e('Click here to view this item'); ?>"><?php _e('View'); ?></a>
	<a class="delete btn btn-danger btn-xs" target="_blank" title="<?php _e('Click here to remove this item'); ?>"><?php _e('Delete'); ?></a>
	</td>
	</tr>
	<?php				
				}
	?>
    
<?php
			if($order_count==0){
?>
<tr><td colspan="3"><?php _e('There are no items to show.'); ?></td></tr>
<?php				
			}
?>    
	</table>
	<?php else: ?>
	
	<?php endif; ?>
	<?php				
	
			}else{
				_e('You can add a new item here!');
				
	if($ns_add_product): ?>  
		<a class="btn btn-success pull-right" href="/add-product"><?php _e('ADD PRODUCT'); ?></a>      
	<?php elseif($wc_vendors_status): ?>
		<a class="btn btn-success pull-right" href="<?php echo get_admin_url(); ?>post-new.php?post_type=product" target="_blank"><?php _e('ADD PRODUCT'); ?></a>
	<?php endif; 
			}
			
			
			$out1 = ob_get_contents();
			
			
			ob_end_clean();	
			
		}else{
			$out1 = '';
		}
		
		return $out1;		
	}
	}

	
	add_shortcode('WP-CA-PRODUCT-TYPES', 'wpbw_product_types');
	
	add_action('init', 'wpbw_front_init');
	
	if(!function_exists('wpbw_front_init')){
	function wpbw_front_init(){
		wpbw_my_products();
		wpbw_email_to_admin();
		
	}
	}
	
	if(!function_exists('wpbw_my_products')){
	function wpbw_my_products(){
		

		
		if(!empty($_POST) && isset($_POST['wp_ca_my_products'])){
					
			if ( 
				! isset( $_POST['wp_ca_my_products_field'] ) 
				|| ! wp_verify_nonce( $_POST['wp_ca_my_products_field'], 'wp_ca_my_products_action' ) 
			) {
			
			   _e('Sorry, your nonce did not verify.', 'booking-works');
			   exit;
			
			} else {
			
				$params = array();
				// process form data
				$wp_ca_my_products = wpbw_sanitize_bw_data($_POST['wp_ca_my_products']);

				global $wpdb;
				$product_ids = is_array($wp_ca_my_products)?$wp_ca_my_products:array();
				
				$status = wpbw_sanitize_bw_data($_POST['wp_ca_my_products_status']);
				
				
				if(!empty($product_ids)){
					$valid_products = array();
				
					switch($status){
					   case 'pause':
							$post_status = 'draft';
					   break;
					   default:
					   case 'play':
							$post_status = 'publish';			
					   break;
					
					}
										
					foreach($product_ids as $product_id){

						if($post_status=='publish' && wpbw_valid_user_allowed($product_id)){
							$valid_products[] = $product_id;
						}elseif($post_status=='draft'){
							$valid_products[] = $product_id;
						}else{
							$params[] = $product_id;
						}
						
					}
					//pree($valid_products);exit;
					if(!empty($valid_products)){
						$q = "UPDATE $wpdb->posts p SET p.post_status='$post_status' WHERE p.ID IN (".implode(',', $valid_products).") AND p.post_type='product' AND p.post_author=".get_current_user_id();
						//pree($q);exit;
						$wpdb->query($q);
					}else{
						
					}
				
				}
				
				$wp_ca_pages = wpbw_get_pages(true);
				$products_url = get_permalink($wp_ca_pages['my_items']);
				
				if(!empty($params) && wpbw_posting_fee_required()){
					$products_url .= '?ids='.implode(',', $params);
				}
				wp_redirect($products_url);
						
				exit;
			   
			}
			
			
			
		}
		
		
		if(is_user_logged_in()){
			if(!empty($_GET) && isset($_GET['edit']) && isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete']>0){

			    $get_delete = wpbw_sanitize_bw_data($_GET['delete']);

				
				$wp_ca_is_user_item = wpbw_is_user_item($get_delete);
				
				if($wp_ca_is_user_item){
					//pree($_GET['delete']);
					wp_delete_attachment( $get_delete, true );
				}
				
			}
			
			
						
		}
	}
	}
		
	
	if(!function_exists('wpbw_product_types')){
	function wpbw_product_types(){		
		
		if(!is_user_logged_in() || !empty($_POST)){
			return;// __('You need to login to access this page.', 'booking-works');
		}
		
		if(wpbw_valid_user()){
			
	
		
		
		ob_start();
		
		wpbw_updates_required('Please complete the following information before posting an item, click below.');
?>		
		<div class="product_types_heading col col-md-12 mt-4 mb-2 p-0"><h4><?php _e('Click to select product type'); ?>:</h4></div>
		<div class="btn-group btn-group-lg wp_ca_product_types w-100" style="margin:5px 0 10px 0;">
		  <button type="button" class="btn btn-primary active" data-type="salesable"><?php _e('Saleable'); ?></button>
		  <button type="button" class="btn btn-primary" data-type="renting"><?php echo wpbw_messages('type'); ?></button>
		</div>
        
		<div class="btn-group btn-group-md wp_ca_product_sub_types col col-md-12 nopadding">
		  <button type="button" class="btn btn-info active" data-type="rental"><?php _e('Rental Product'); ?></button>
		  <button type="button" class="btn btn-info" data-type="on_demand_service"><?php echo _e('On Demand Service'); ?></button>
          <button type="button" class="btn btn-info" data-type="vacation_tour"><?php echo _e('Vacation | Adventure | Tours | Games'); ?></button>
          <br /><br />
		</div>        

<?php        
		$out1 = ob_get_contents();
		ob_end_clean();
		
		}else{
			$out1 = '';
		}
		return $out1;		


	}
	}
	
	add_action('woocommerce_before_single_product', 'wpbw_woocommerce_before_single_product');
	
	if(!function_exists('wpbw_woocommerce_before_single_product')){
	function wpbw_woocommerce_before_single_product(){
		
		global $post, $wp_ca_dir, $wp_fu_plugin, $wp_ca_wishlist;
		
		$wp_ca_product_type = get_post_meta($post->ID, '_wp_ca_product_type', true);
		
		wpbw_update_acf_fields($post->ID);
		//pree($wp_ca_product_type);
		
		switch($wp_ca_product_type){
			default:
				//return do_shortcode('[WP-CA-PRODUCT-TYPES]');
			break;
			case 'renting':
			
				add_filter( 'add_to_cart_text', 'wpbw_custom_single_add_to_cart_text' );                // < 2.1
				add_filter( 'woocommerce_product_single_add_to_cart_text', 'wpbw_custom_single_add_to_cart_text' );  // 2.1 +
				

				$author = get_user_by('id', $post->post_author);
				//pree($author);	
		        $featured_img_url = get_the_post_thumbnail_url($post->ID,'full'); 	
							
							
				$product = new WC_product($post->ID);
				$attachment_ids = $product->get_gallery_image_ids();

				$images = array(array($featured_img_url, ''));
								
				foreach( $attachment_ids as $attachment_id ) 
				{
				  // Display the image URL
					$Original_image_url = wp_get_attachment_url( $attachment_id );
					$images[$attachment_id] = array($Original_image_url, '');
				  // Display Image instead of URL
				  //echo wp_get_attachment_image($attachment_id, 'full');
			
				}
							
				$args = array(
					'posts_per_page'   => -1,
					'offset'           => 0,
					'category'         => '',
					'category_name'    => '',
					'orderby'          => 'date',
					'order'            => 'DESC',
					'include'          => '',
					'exclude'          => '',
					'meta_key'         => '',
					'meta_value'       => '',
					'post_type'        => 'attachment',
					'post_mime_type'   => '',
					'post_parent'      => $post->ID,
					'author'	   => $post->post_author,
					'author_name'	   => '',
					'post_status'      => 'private',
					'suppress_filters' => true 
				);
				//pree($args);
				$imgs_array = get_posts( $args );		
				//pree($imgs_array);
				if(!empty($imgs_array)){
					foreach( $imgs_array as $img_obj ) 
					{
						$images[$img_obj->ID] = array($img_obj->guid, '');
					}			
				}
				
				$args = array(
					'posts_per_page'   => 6,
					'offset'           => 0,
					'category'         => '',
					'category_name'    => '',
					'orderby'          => 'date',
					'order'            => 'DESC',
					'include'          => '',
					'exclude'          => array($post->ID),
					'meta_key'         => '_wp_ca_product_type',
					'meta_value'       => 'renting',
					'post_type'        => 'product',
					'post_mime_type'   => '',
					'post_parent'      => '',
					'author'	   => $post->post_author,
					'author_name'	   => '',
					'post_status'      => array('publish', 'draft'),
					'suppress_filters' => true 
				);
				$seller_products = get_posts( $args );								
				
				//pree($seller_products);
				
				$is_owner = (wpbw_is_user_item($post->ID));
				
				$is_edit = (isset($_GET['edit']) && $is_owner);		

				$wc_productdata_options_arr = get_post_meta($post->ID, 'wc_productdata_options', true);


				if(!empty($wc_productdata_options_arr)){
					
					foreach($wc_productdata_options_arr as $v_k=>$wc_productdata_options){
						
						if(isset($wc_productdata_options['_product_video']) && $wc_productdata_options['_product_video']!=''){
							$wc_productdata_options['_product_video'] = explode('/', $wc_productdata_options['_product_video']);
							$wc_productdata_options['_product_video'] = end($wc_productdata_options['_product_video']);
							$wc_productdata_options['_product_video'] = explode('=', $wc_productdata_options['_product_video']);
							$wc_productdata_options['_product_video'] = end($wc_productdata_options['_product_video']);			
							$vi = 'https://img.youtube.com/vi/'.$wc_productdata_options['_product_video'].'/hqdefault.jpg';
							$vl = 'https://www.youtube.com/embed/'.$wc_productdata_options['_product_video'];
							$images['videos video-'.$v_k] = array($vi, $vl);
						}
					}

				}
				//pree($images);
				//pree($is_edit);exit;
				include($wp_ca_dir.'/templates/wp_ca_rentable_items.php');get_footer();exit;
			break;
		}
	}
	}
	
	if(!function_exists('wpbw_cart_has_renting')){
	function wpbw_cart_has_renting(){
		$ret = array();
		
		global $woocommerce;
		
		$items = $woocommerce->cart->get_cart();
		
		if(!empty($items)){

			foreach($items as $item => $values) { 
			
				
				
				$wp_ca_product_type = get_post_meta($values['data']->get_id(), '_wp_ca_product_type', true);
				
				if($wp_ca_product_type=='renting'){
					$ret[] = $values['data']->get_id();
				}
			} 
		}
		
		return $ret;
		
	}
	}
	
	if(!function_exists('wpbw_duration_to_days')){
	function wpbw_duration_to_days($short, $num=0){
		
		$ret = '';
		
		switch($short){
			
			case 'w':
				$ret = $num*7;
			break;
			case 'd':
				$ret = $num*1;
			break;
			case 'h':
				$ret = ($num>=24?$num/24:$num); //e.g. 48 hours
			break;


		}
		
		return $ret;
		
		
	}
	}
	
	if(!function_exists('wpbw_duration_text')){
	function wpbw_duration_text($short, $num=0){
		
		$ret = '';
		
		switch($short){
			
			case 'w':
				$ret = 'week';
			break;
			default:
			case 'd':
				$ret = 'day';
			break;
			case 'h':
				$ret = 'hour';
			break;


		}
		
		$ret .= ($num>1?'s':'');
		
		return $ret;
		
		
	}
	}
	
	if(!function_exists('wpbw_generate_ticket_by_item')){
	function wpbw_generate_ticket_by_item($id, $type='', $existing_booking_param=array()){
		
		global $wp_ca_dir, $wp_inbox_pages, $wp_ca_logo, $woocommerce, $existing_booking, $qty_count, $lg_contract_fields;


		$existing_booking_param = (!empty($existing_booking_param)?$existing_booking_param:$existing_booking);

		$help_url = (!is_null($wp_inbox_pages) && $wp_inbox_pages['Help']?get_permalink($wp_inbox_pages['Help']):home_url().'/contact');

		$contract_fields = array();
		
		//pree($id);pree($type);pree($existing_booking);	
		
		
		/*pree($woocommerce->cart->get_cart());
		foreach($woocommerce->cart->get_cart() as $key => $val ) {			
			pree($key);
			pree($val);
			
		}*/
		
		//pree($existing_booking_param);	
			
		if(empty($existing_booking_param)){
			
			switch($type){			
				
				case 'ca_object_id':
					$c_array = array('ca_type'=>'wc_product', 'ca_object_id'=>$id, 'ca_user_id'=>get_current_user_id(), 'ca_status'=>'active', 'ca_blog_id'=>get_current_blog_id());
					//pree($c_array);
					$existing_booking_param = wpbw_get_actions('calendars_management', $c_array, false);		
					$existing_booking = current($existing_booking_param);
					
					//pree($existing_booking);
				
				
				break;
				
			}
			
		}
		//pree($existing_booking);
			
		if(!empty($existing_booking_param)){
			
			
			$qty_count = count($existing_booking_param);
			//pree($existing_booking_param);
			
			if(array_key_exists(1, $existing_booking_param)){ //means multiple arrays are there
				$existing_booking = current($existing_booking_param);
			}elseif(array_key_exists(0, $existing_booking_param)){						
				$existing_booking = current($existing_booking_param);
			}else{
				$existing_booking = $existing_booking_param;
			}

			//pree(is_array($existing_booking));
			//pree(is_object($existing_booking));
			if(is_object($existing_booking)){
				$existing_booking = (array)$existing_booking;
			}
			//pree((array)$existing_booking);
			
			//pree($existing_booking);
			
			extract($existing_booking);
			
			$item = $ca_object_id;
			//pree($item);exit;
			wpbw_update_acf_fields($item);
			
			//pree($existing_booking);

			
			$wc_get_product = wc_get_product($item);
			
			//pree($item);
			//pree($wc_get_product);exit;
			
			$link = get_permalink($item);
			$title = $wc_get_product->get_title();
			$description = wp_trim_words($wc_get_product->get_description(), 40, '...');
			
			
			$price = ($wc_get_product->get_price()*$qty_count);
			
			
			$image = get_the_post_thumbnail_url($item, 'full');
			$meta = get_post_meta($item);
			$wp_ca_number_of = ($meta['wp_ca_number_of'][0]);
			$wp_ca_duration_type = wpbw_duration_text($meta['wp_ca_duration_type'][0], $wp_ca_number_of);			
			//wpbw_pre($wp_ca_number_of);
			//wpbw_pre($wp_ca_duration_type);
			
			$post = get_post($item);
						
			$author = get_user_by('id', $post->post_author);
			$author_bio = get_the_author_meta( 'description', $author->ID );
			

			
			$monthName = date('M', mktime(0, 0, 0, $ca_slot_month, 10));
			
			$from_date = $ca_slot_day.' '.$monthName.', '.$ca_slot_year;
			
			$date = mktime( 0, 0, 0, $ca_slot_month, $ca_slot_day, $ca_slot_year );
			
			$to_date = '';
			
			if($wp_ca_number_of!=0 && $wp_ca_duration_type!='')
			$to_date = date( 'd M, Y', strtotime( '+'.$wp_ca_number_of.' '.$wp_ca_duration_type, $date ) );
			
			
			$ticket_number = $ca_id;
			
			$contract_fields = get_option('contract_snapshot_'.$ticket_number, array());
			$lg_contract_fields = maybe_unserialize($contract_fields);
			
			$contract_fields = $lg_contract_fields;

			//pree($contract_fields);
		}

//		pree($existing_booking);
		
		//pree($lg_contract_fields);
		
		include($wp_ca_dir.'/templates/wp_ca_ticket_view.php');			
	}
	}
	
	if(!function_exists('wpbw_booking_details')){
	function wpbw_booking_details($existing_booking_param){

		global $wp_ca_booking_details;
		
		
		
		if(!$wp_ca_booking_details && !empty($existing_booking_param)){
			//pree($existing_booking_param);
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col"><?php _e('Order Date'); ?></th>
      <th scope="col"><?php _e('Booking Slots'); ?></th>
    </tr>
  </thead>
  <tbody>
  
    
<?php 
		$b_arr = array();
		
		$t = 0;

		foreach($existing_booking_param as $booking_details){

		    $ca_add_on = maybe_unserialize($booking_details->ca_add_on);
		    $ca_add_on = $ca_add_on ?? array();

		    if(!empty($ca_add_on)){

                global $wp_ca_add_on_total, $wp_ca_add_on_html;


		        $counter = 1;

		        foreach ($ca_add_on as $add_on){

                    $wp_ca_add_on_html .= "<li class=''>{$add_on['title']} - {$add_on['formatted_price']}</li>";

		            $wp_ca_add_on_total += $add_on['price'];
		            $counter++;

		        }



		    }


		    $t++;
		
			//pree($booking_details);

			$booking_details->ca_slot_month = date('F', mktime(0, 0, 0, $booking_details->ca_slot_month, 10));
			
			$b_arr[$booking_details->ca_slot_day.$booking_details->ca_slot_month.$booking_details->ca_slot_year.$t] = '
			<tr>
			<th scope="row">'.$t.'</th>
			<td>'.date('d F Y', ($booking_details->ca_order_attached?$booking_details->ca_dated:time())).'</td>
			<td>'.$booking_details->ca_slot_day.' '.$booking_details->ca_slot_month.' '.$booking_details->ca_slot_year.'</td>
			</tr>';
			
			
		} 
		ksort($b_arr);
		echo implode('', $b_arr);
?>	  

  </tbody>
</table>
<?php			
		$wp_ca_booking_details = true;
		}
		
	}
	}
	
	if(!function_exists('wpbw_woocommerce_before_checkout_form')){
	function wpbw_woocommerce_before_checkout_form(){

		$booking_items = wpbw_cart_has_renting();
		if(!empty($booking_items)){
			foreach($booking_items as $item){
				wpbw_generate_ticket_by_item($item, 'ca_object_id');
			}
			
		}
		
	}
	}
	
	add_action('woocommerce_before_checkout_form', 'wpbw_woocommerce_before_checkout_form');
	
	if(!function_exists('wpbw_filter_existing_booking_by')){
	function wpbw_filter_existing_booking_by($existing_bookings, $id, $field){
		
		$existing_bookings_filtered = array(); 
		if(!empty($existing_bookings)){
			foreach($existing_bookings as $dmy=>$data){
				if(array_key_exists($field, $data) && in_array($id, $data) && $data[$field]==$id){
					$existing_bookings_filtered[$dmy] = $data;
				}
				
			}
		}
		
		
		return $existing_bookings_filtered;
	}
	}
	
	if(!function_exists('wpbw_woocommerce_before_single_product_inner')){
	function wpbw_woocommerce_before_single_product_inner($simulation=false, $existing_bookings=array()){
		
		//pree($existing_booking);exit;
		
		global $post, $product, $wp_ca_url;
		 
		
		$is_edit = (isset($_GET['edit']) && wpbw_is_user_item($post->ID));		
		$hours_enabled = get_post_meta($post->ID, '_hours_enabled', true);
		
		$wp_ca_number_of = get_post_meta($post->ID, 'wp_ca_number_of', true);
		$wp_ca_number_of = $wp_ca_number_of[0];
		
		$wp_ca_duration_type = get_post_meta($post->ID, 'wp_ca_duration_type', true);
		$wp_ca_duration_type = $wp_ca_duration_type[0];
		
		$hours_available = get_post_meta($post->ID, 'hours_available', true);
		$hours_available = (is_array($hours_available)?$hours_available:array());
		
		$timezone = get_post_meta($post->ID, '_wp-ca-timezone', true);
		$timezone = ($timezone?$timezone:'America/New_York');
		
		$ca_id = '';
		$ca_slot_hour = array();
		$am_count = 0;
		$pm_count = 0;
		
		if($simulation || $is_edit){
			//EDIT MODE
			
			
			if(!$is_edit && !empty($existing_bookings)){
				//pree($existing_bookings);
				$rid = (isset($_GET['rid'])?wpbw_sanitize_bw_data($_GET['rid']):0);
				$rid_valid = wpbw_is_user_order($rid);		
				
				if($rid_valid){
					$existing_bookings = wpbw_filter_existing_booking_by($existing_bookings, $rid, 'ca_order_attached');
				}
				
				$existing_booking = current($existing_bookings);
				extract($existing_booking);

			
			}
			
			
			
			//pree($timezone);exit;
?>

<div class="row wp_ca_item_edit_section wp_ca_step <?php echo !$is_edit ? 'step_2' : '' ?>">


<div class="col-md-12 wp_ca_hours_selection">
<span class="wipe-out" title="<?php _e('Click here to exclude this day'); ?>"></span>
<span class="down-to" title="<?php _e('Click here to minimize'); ?>"></span>
<div class="form-group">
<?php 

if($hours_enabled){
	
	
	//pree($hours_available);
?>
<h3>
<?php
	if($is_edit)
	_e('Please select the hours in which your item/offer is available'); 
	else
	_e('Please select the hours in which you need this item/offer'); 
?>
:</h3>
<?php
}

?>
</div>
<?php if(!empty($existing_bookings))://pree($existing_bookings);
foreach($existing_bookings as $ca_id_obj): ?>
<input type="hidden" name="existing_booking[<?php echo $ca_id_obj['ca_id']; ?>]" data-dmy="<?php echo ($ca_id_obj['ca_slot_day']*1).'-'.$ca_id_obj['ca_slot_month'].'-'.$ca_id_obj['ca_slot_year']; ?>" value="true" />
<?php endforeach; ?>
<?php else: ?>
<input type="hidden" name="existing_booking[]" value="<?php echo $ca_id; ?>" />
<?php endif; ?>

<?php if($is_edit): ?>
<input type="hidden" name="postid" value="<?php echo $post->ID; ?>" />
<input type="hidden" name="wp-ca-timezone" value="<?php echo $timezone; ?>" data-ask="<?php _e('Timezone selection is an important thing for your item/service. You may skip hours for now.'); ?>" />
<div class="form-group">
<?php
$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);


?>
<div class="dropdown scrollable-menu wp-ca-timezone">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?php echo ($timezone?$timezone:__('Timezone Selection', 'booking-works'));?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
<?php foreach($tzlist as $con){
?>	
<li <?php echo ($timezone==$con?'active':''); ?>><a data-val="<?php echo $con; ?>"><?php echo $con; ?></a></li>
<?php	
}  
?>    
  </ul>
</div> 
</div>
<?php elseif($timezone): ?>
<div class="h4"><?php echo __('Timezone', 'booking-works').': '.$timezone; ?></div>
<?php endif; ?>

<?php if($hours_enabled): ?>
<div class="row">
	<?php if(!$is_edit): ?>
	<div class="col-md-3 wp_ca_cslots"> <br />   	
    <span><?php _e('No slots selected yet.'); ?></span>    	
    </div>
    <?php endif; ?>
    <div class="col-md-3">
        
        <div class="form-group wp-ca-am-pm">
        <span><?php _e('AM'); ?></span>
        <div class="btn-group-vertical btn-group-sm wp-ca-am-hours" style="margin:5px 0 10px 0;">
        <?php for($h=1; $h<=24; $h++):  
			$active = in_array($h, $ca_slot_hour);
			$am = false;
			if($h<=12){
				$am = true;
			}
			
			if($am && $active)
			$am_count++;
			
			if(!$am && $active)
			$pm_count++;
			
			(is_array($hours_available) && !in_array($h, $hours_available)?'':'')
			
		?>
          <button data-val="<?php echo $h; ?>" type="button" class="btn btn-<?php echo ($am)?'info':'warning'; echo ($active?' active':''); echo ($is_edit?(!in_array($h, $hours_available)?' ':' active'):(!in_array($h, $hours_available)?' disabled':''));?>"><?php echo $h; ?></button>
        <?php if($h==12): ?>
        </div>
        <span><?php _e('PM'); ?></span>
        <div class="btn-group-vertical btn-group-sm  wp-ca-pm-hours" style="margin:5px 0 10px 0;">
        <?php endif; ?>  
        <?php endfor; ?>  
        </div>
        </div>

    </div>
    
    <div class="col-md-6 wp-ca-am-hours-count">
    	<div class="row">
        <div class="col-md-12">
        <h3></h3>
        </div>
        </div>
        
        
        <div class="row justify-content-md-center">
        <div class="col col-lg-6  wp-ca-am">
          <h3 class="label label-info"><?php _e('Morning Hours'); ?></h3> - <span class="label label-info"><?php echo $am_count; ?></span>
        </div>

        <div class="col col-lg-6  wp-ca-pm">
          <h3 class="label label-warning"><?php _e('Evening Hours'); ?></h3> - <span class="label label-warning"><?php echo $pm_count; ?></span>
        </div>
        </div>
        
    	
              
    </div>
</div>
<?php endif; ?>


<?php if($is_edit): 
?>
<div class="form-group wp_ca_duration_section">
		

		
        
<?php

if ($product->is_type( 'variable' )) 
{
	
    $available_variations = $product->get_available_variations();
	
	if(!empty($available_variations)){

?>

	<div class="form-group col col-md-12 nopadding">
        	<strong class=" pull-left"><?php echo __('Offer', 'booking-works').' '.__('Duration', 'booking-works').'/'.__('Validity', 'booking-works'); ?>:</strong>
    </div>
<?php        
		
        //pree($wp_ca_number_of);	
		//pree($wp_ca_duration_type);	
		//pree($available_variations);
		foreach ($available_variations as $key => $value) 
		{ 
			if($value['variation_is_active']){
				
				$attributes = $value['attributes'];
?>
		<div class="col col-md-12">
        
			<?php if(!empty($attributes)){ ?>
            <div class="row">
            <label class=" pull-left"><?php foreach($attributes as $k=>$attribute){ echo $attribute.' '; } ?></label>
            </div>
            <?php } ?>
            
            <div class="row"> 
                <div class="form-group col col-md-3">
                 <input type="text" name="wp_ca_number_of[<?php echo $value['variation_id']; ?>]" value="<?php echo $wp_ca_number_of[$value['variation_id']]?$wp_ca_number_of[$value['variation_id']]:1; ?>" placeholder="e.g. 12" />
                 <input type="hidden" name="wp_ca_duration_type[<?php echo $value['variation_id']; ?>]" value="<?php echo $wp_ca_duration_type[$value['variation_id']]; ?>" />
                 
                </div>
                <div class="form-group col col-md-2">
                <?php _e('Hours'); ?>
                </div>
                
			</div>                
        </div>
<?php
			}
		}
	
	}
	
}else{
	//pree($product->get_id());exit;
	//pree($wp_ca_duration_type);exit;
?>    
        <div class="form-group col col-md-12 nopadding hide">
        	<strong class=" pull-left"><?php echo __('Offer', 'booking-works').' '.__('Duration', 'booking-works').'/'.__('Validity', 'booking-works'); ?>:</strong>
        </div>
		<div class="row col col-md-12 nopadding hide">
    	<div class="form-group col col-md-2">
   	     <input type="text" name="wp_ca_number_of[<?php echo $product->get_id(); ?>]" value="<?php echo $wp_ca_number_of[$product->get_id()]?$wp_ca_number_of[$product->get_id()]:1; ?>" placeholder="e.g. 12" />
         <input type="hidden" name="wp_ca_duration_type[<?php echo $product->get_id(); ?>]" value="<?php echo $wp_ca_duration_type[$product->get_id()]; ?>" />
        </div>
        <div class="form-group col col-md-2">
            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
              <span class="caret"></span></button>
              <ul class="dropdown-menu">
                <li data-id="w"><a><?php _e('Weeks'); ?></a></li>
                <li data-id="d"><a><?php _e('Days'); ?></a></li>
                <li data-id="h"><a><?php _e('Hours'); ?></a></li>
              </ul>
            </div>
        </div>
        </div>
<?php
}
?>
        
        
         

        
</div>


<div class="row edit-booking-cta">
    <div class="col-md-12">
        <div class="form-group">
        <button type="button" class="btn btn-primary btn-lg submit"><?php _e('Save Changes'); ?></button>
        </div>
    </div>
</div>
<?php else: ?>

<?php endif; ?>


</div>		



</div>
<?php			
		//get_footer();
		//exit;
					
		}else{
			//VIEW MODE
		}
		

	}
	}
	
	if(!function_exists('wpbw_header_scripts')){
	function wpbw_header_scripts(){
		
		global $post;

		if(!is_object($post))return;

		$wp_ca_product_type = get_post_meta($post->ID, '_wp_ca_product_type', true);
		$userdata = get_userdata( get_current_user_id() );
		$userdata = is_object($userdata)?$userdata:new stdClass;
					
		
		$menus = wp_get_nav_menus();
		$pages = wpbw_get_pages();
		
		
		$obj_ids = array();
		
		foreach ( $menus as $menu ):
		$menu_items = wp_get_nav_menu_items($menu->name);
		//pree($menu_items);
		if(!empty($menu_items)){
			foreach($menu_items as $items){
				$obj_ids[$items->object_id][] = $items->ID;
			}
		}
		endforeach;		
?>
	<style type="text/css">
		<?php if(!is_user_logged_in() && !empty($pages)){ foreach($pages as $id=>$title){ $ids = isset($obj_ids[$id])?$obj_ids[$id]:array();
			
			if(!empty($ids)){ foreach($ids as $ds){ 
		?>
		.menu-item-<?php echo $ds; ?>{ display:none !important; }
		
		<?php } } } } ?>
		@media only screen and (max-device-width: 480px) {
			
			
		}			
	</style>
    <script type="text/javascript" language="javascript">
		jQuery(document).ready(function($){
<?php 
			if($wp_ca_product_type=='renting'){
?>
				$('body').addClass('<?php echo $wp_ca_product_type; ?>');
<?php					
			}
			$userdata->caps = is_array($userdata->caps)?$userdata->caps:array();
			
			$caps = array_keys($userdata->caps);
			if(!empty($caps)){		
?>
				$('body').addClass('<?php echo implode(' ', $caps).''; ?>');
<?php				
			}
			
			?>			
		});
	</script>
<?php		
		
	}
	}
	
	add_action('wp_head', 'wpbw_header_scripts');
	
	if(!function_exists('wpbw_admin_header_scripts')){
	function wpbw_admin_header_scripts(){
		
?>
	<style type="text/css">
		#toplevel_page_ns-add-product-frontend-ns-admin-options-ns_admin_option_dashboard{
			display:none;
		}
		@media only screen and (max-device-width: 480px) {
			
			
		}			
	</style>
    <script type="text/javascript" language="javascript">
		jQuery(document).ready(function($){
		});
	</script>
<?php		
		
	}
	}
	
	add_action('admin_head', 'wpbw_admin_header_scripts');	
	
	if(!function_exists('wpbw_plugins_to_hide')){
	function wpbw_plugins_to_hide() {
		global $wp_list_table;
		$hidearr = array('ns-add-product-frontend/ns-frontend-add-product-page.php');
		$myplugins = $wp_list_table->items;
		//pree($myplugins);exit;
		foreach ($myplugins as $key => $val) {
			if (in_array($key,$hidearr)) {
				unset($wp_list_table->items[$key]);
			}
		}
	}
	}
	//add_action('pre_current_active_plugins', 'wpbw_plugins_to_hide');	

	if(!function_exists('wpbw_get_start_dt')){
	function wpbw_get_start_dt($order_id){
		$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_order_attached'=>$order_id, 'ca_blog_id'=>get_current_blog_id()), true);
		$booked = current($existing_booking);		
		$booked_date = $booked->ca_slot_day.'-'.$booked->ca_slot_month.'-'.$booked->ca_slot_year;
		
		if($booked->ca_start_date>0){
			$booked_date = date( 'd-m-Y', $booked->ca_start_date);
		}
		
		return $booked_date;
	}	
	}
	
	if(!function_exists('wpbw_get_end_dt')){
	function wpbw_get_end_dt($order_id){
		
		$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_order_attached'=>$order_id, 'ca_blog_id'=>get_current_blog_id()), false);
		//pree($existing_booking);
		
		$booked_start = current($existing_booking);		
		$booked_end = end($existing_booking);
		
		
		$booked_date = $booked_start->ca_slot_day.'-'.$booked_start->ca_slot_month.'-'.$booked_start->ca_slot_year;
		
		if($booked_start->ca_start_date>0){
			$ca_slot_day = date('d', $booked_start->ca_start_date);
			$ca_slot_month = date('m', $booked_start->ca_start_date);
			$ca_slot_year = date('Y', $booked_start->ca_start_date);
		}else{

			$ca_slot_day = $booked_start->ca_slot_day;
			$ca_slot_month = $booked_start->ca_slot_month;
			$ca_slot_year = $booked_start->ca_slot_year;
		}
		
		$wp_ca_number_of = count($existing_booking);//$booked->ca_duration_number;
		$wp_ca_duration_type = wpbw_duration_text('d', $wp_ca_number_of);//$booked->ca_duration_type

		$date = mktime( 0, 0, 0, $ca_slot_month, $ca_slot_day, $ca_slot_year );
		$booked_date = date( 'd-m-Y', strtotime( '+'.$wp_ca_number_of.' '.$wp_ca_duration_type, $date ) );		

		
		return $booked_date;		
	}
	}
	
	if(!function_exists('wpbw_messages')){
	function wpbw_messages($msg, $type=''){
		
		global $wp_ca_type;



		$type = ($type?$type:$wp_ca_type);


		$ret = '';
		
		$msgs = array(
			'virtual' => array(
				'type' => __('Online Course', 'booking-works'),
				'price_type' => __('Fee', 'booking-works'),
				'item' => __('Course', 'booking-works'),
				'start_1' => __('If your teacher has contacted you and you are agreed on a time to start with your teacher so you can confirm the receipt.', 'booking-works'),
				'start_2' => __('Do you want to confirm that you are in contact with your teacher and order can be started now?', 'booking-works'),
				'end_1' => __('Once you will complete this course or set of lessons, this order will be ended.', 'booking-works'),
                'end_2' => __('Do you want to confirm that you  have completed this course and order can be ended now?', 'booking-works'),
				'error_1' => __('A teacher cannot book himself.', 'booking-works')
			),
			'tangible' => array(
				'type' => __('Rentable Product', 'booking-works'),
				'price_type' => __('Rent', 'booking-works'),
				'item' => __('Item', 'booking-works'),
				'start_1' => __('Once you receive the item either by pickup or vendor delivers you, you have to confirm the receipt.', 'booking-works'),
				'start_2' => __('Do you want to confirm that you have received the item and order can be started now?', 'booking-works'),
				'end_1' => __('Once you will receive the item or get the job done so you can end this offer.', 'booking-works'),
                'end_2' => __('Do you want to confirm that you  have received the item and order can be ended now?', 'booking-works'),
				'error_1' => __('You are the owner of this item so you cannot book to yourself.', 'booking-works')
			),
			'activity' => array(
				'type' => __('Physical Activities', 'booking-works'),
				'price_type' => __('Fee', 'booking-works'),
				'item' => __('Item', 'booking-works'),
				'start_1' => __('If organizer has contacted you and you are agreed on a time so you can confirm the receipt.', 'booking-works'),
				'start_2' => __('Do you want to confirm that you are in contact with the organizer and order can be started now?', 'booking-works'),
				'end_1' => __('Once you will complete this activity, this order will be ended.', 'booking-works'),
                'end_2' => __('Do you want to confirm that you  have completed this activity and order can be ended now?', 'booking-works'),
				'error_1' => __('An organizer cannot book himself.', 'booking-works')
			)
				
		);
		
		switch($type){
			case 'virtual':
				
				$ret = $msgs[$type][$msg];
			
			break;
			case 'renting':
			case 'physical':
			case 'tangible':

				$type = 'tangible';
				
			break;
			case 'activity':

				$type = 'activity';
				
			break;			
		}

		$ret = isset($msgs[$type][$msg]) ? $msgs[$type][$msg] : '';
		
		return $ret;
	}
	}
	
	if(!function_exists('wpbw_action_woocommerce_order_details_after_customer_details')){
	function wpbw_action_woocommerce_order_details_after_customer_details( $order ) {
		global $wp_ca_activated;
		
		if(!$wp_ca_activated)
		return;
		
		//pree($order->get_id());
		global $post;
		
		//pree($post->ID);
		//pree($order);
		
		$order = (is_numeric($order)?wc_get_order($order):$order);
		


		if(!empty($order)){
		
			$items = $order->get_items();
		
		
			
			//pree($items);
			$vendors = array();
			
			if(!empty($items)){
				foreach( $items as $item_key => $item_values ){
					//pree($order->get_id());

//					pree($item_values);
					$for_pid = $item_values->get_data();
					$pid = $for_pid['product_id'];
					$wp_ca_product_type = get_post_meta($pid, '_wp_ca_product_type', true);
					

					switch($wp_ca_product_type){
						case 'renting':
							//$wp_ca_get_post_meta = get_post_meta($item_values->get_product_id());
							//pree($wp_ca_get_post_meta);
							$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_status'=>'booked', 'ca_object_id'=>$pid, 'ca_user_id'=>get_current_user_id(), 'ca_order_attached'=>$order->get_id(), 'ca_blog_id'=>get_current_blog_id()), false);
							

							$rid_valid = wpbw_is_user_order($order->get_id());

							if(empty($existing_booking) && $rid_valid){
								
								//pree($rid_valid);
								
	?>
								<div class="col-md-12" style="text-align:center;">
								<a href="<?php echo get_permalink($pid).'?rid='.$order->get_id(); ?>" class="btn btn-danger btn-lg"><?php _e('Schedule your session'); ?></a>
								</div>
								
	<?php							
								
							}else{
							
								
								$booked_start = current($existing_booking);
								
								

								$booked_date = $booked_start->ca_slot_day.'-'.$booked_start->ca_slot_month.'-'.$booked_start->ca_slot_year;
								
								if($booked_start->ca_user_id!=get_current_user_id())
								return;									
								
								if($booked_start->ca_start_date==0){
								
	?>
								<div class="wp-ca-offer-counter type-start col-md-12 nopadding">
								<h2><?php _e('Offer will be started in'); ?>:</h2>
								
								<ul class="wp-ca-offer-item-<?php echo $order->get_id(); ?> col-md-12" data-id="<?php echo $order->get_id(); ?>" data-dt="<?php echo date('M d, Y H:i:s', strtotime($booked_date));//Jan 5, 2018 15:37:25 ?>">
									<li class="dd col-md-2 btn btn-info">00</li>
									<li class="sep col-md-1">:</li>
									<li class="hh col-md-2 btn btn-info">00</li>
									<li class="sep col-md-1">:</li>
									<li class="mm col-md-2 btn btn-info">00</li>
									<li class="sep col-md-1">:</li>
									<li class="ss col-md-2 btn btn-info">00</li>                            
								</ul>
								
								<div class="col-md-12" style="text-align:center;">
								<p class="col-md-12"><?php echo wpbw_messages('start_1'); ?></p>
								<a data-type="start" data-id="<?php echo base64_encode($order->get_id()); ?>" data-msg="<?php echo wpbw_messages('start_2'); ?>" class="btn btn-primary btn-lg wp-ca-confirm-start"><?php _e('Click here to confirm'); ?></a>
								</div>
								
								</div>
	<?php
								}elseif($booked_start->ca_start_date>0 && $booked_start->ca_end_date==0){
									
								
									
								$booked_date = wpbw_get_end_dt($order->get_id());
								//pree($booked_date);
	?>
    							
								<div class="wp-ca-offer-counter type-end col-md-12">
								<h2><?php _e('Offer is valid till'); ?>:<a class="btn btn-primary pull-right" target="_blank" href="/booking-tracking/?id=<?php echo $order->get_id(); ?>"><?php _e('Click here to track details'); ?></a></h2>
                                
								
								<ul class="wp-ca-offer-item-<?php echo $order->get_id(); ?> col-md-12" data-id="<?php echo $order->get_id(); ?>" data-dt="<?php echo date('M d, Y H:i:s', strtotime($booked_date));//Jan 5, 2018 15:37:25 ?>">
									<li class="dd col-md-2 btn btn-warning">00</li>
									<li class="sep col-md-1">:</li>
									<li class="hh col-md-2 btn btn-warning">00</li>
									<li class="sep col-md-1">:</li>
									<li class="mm col-md-2 btn btn-warning">00</li>
									<li class="sep col-md-1">:</li>
									<li class="ss col-md-2 btn btn-warning">00</li>                            
								</ul>
								
								<div class="col-md-12">
								<p class="col-md-12"><?php echo wpbw_messages('end_1'); ?></p>
								<a data-type="end" data-id="<?php echo base64_encode($order->get_id()); ?>" data-msg="<?php echo wpbw_messages('end_2'); ?>" class="btn btn-danger btn-lg wp-ca-confirm-end hide"><?php _e('Click here to confirm'); ?></a>
								</div>
                                
                                
                                
								
								</div>
                                
                                
	<?php							
								wpbw_booking_details($existing_booking);
								
								
								}
							
							}
							
							//pree($existing_booking);
						break;
					}
				}
			}		
		}
		
		
		if(!empty($vendors)){
?>
<div class="wp_inbox_message_boxes">
<h3><?php echo (count($vendors)>1?__('Need to contact sellers?', 'booking-works'):__('Need to contact seller?', 'booking-works')); ?></h3>
<?php			
			foreach($vendors as $vendor=>$products){
				if($vendor!=get_current_user_id()){
					wp_order_message_box($vendor, $products);
				}
			}
?>
</div>
<?php				
		}
		
	}
	}
		
	add_action( 'woocommerce_order_details_after_order_table', 'wpbw_action_woocommerce_order_details_after_customer_details', 10, 1 );	
	
	if(!function_exists('wpbw_confirm_se')){
	function wpbw_confirm_se(){
		
		$ret = array('msg'=>__('There is some problem in the order status. Please check it with the administrator.', 'booking-works'));
		
		$action = wpbw_sanitize_bw_data($_POST['action']);
		$oid = wpbw_sanitize_bw_data($_POST['oid']);
		$postid = base64_decode($oid);
		
		
		
		//$ret['msg'] = $action;
		//$ret['msg'] = wpbw_is_user_item($postid);

			
			//$ret['msg'] = $existing_booking;
			
			switch($action){
				
				case 'wp_ca_confirm_start':
				
					$existing_booking = wpbw_get_actions('calendars_management', array('ca_status'=>'booked', 'ca_type'=>'wc_product', 'ca_order_attached'=>$postid, 'ca_user_id'=>get_current_user_id(), 'ca_blog_id'=>get_current_blog_id()), true);
					
					//pree($existing_booking);
					
					//$ret['msg'] = empty($existing_booking)?'E':'!E';
						
					if(!empty($existing_booking)){
						
						$existing_booking = current($existing_booking);		
							
						if($existing_booking->ca_start_date==0){
					
							$where=array(
								'ca_order_attached'=>$postid,
								'ca_type'=>'wc_product', 					
								'ca_user_id'=>get_current_user_id(), 
								'ca_status'=>'booked', 
								'ca_blog_id'=>get_current_blog_id()
							);
							
							
							
							wpbw_actions('calendars_management', array('ca_start_date'=>time()), $where);	
									
							$ret['msg'] = true;
						}else{
							$ret['msg'] = __('This order has been started already.', 'booking-works');
						}
						
					}
					
				break;
				
				case 'wp_ca_confirm_end':
					
					$existing_booking = wpbw_get_actions('calendars_management', array('ca_status'=>'booked', 'ca_type'=>'wc_product', 'ca_order_attached'=>$postid, 'ca_blog_id'=>get_current_blog_id()), true);
					
					if(!empty($existing_booking)){
						
						
						
						$existing_booking = current($existing_booking);		
						
						
						if(wpbw_is_user_item($postid) && $existing_booking->ca_end_date==0){
					
							$where=array(
								'ca_order_attached'=>$postid,
								'ca_type'=>'wc_product', 												
								'ca_status'=>'booked', 
								'ca_blog_id'=>get_current_blog_id()
							);
							
							wpbw_actions('calendars_management', array('ca_end_date'=>time(), 'ca_status'=>'closed'), $where);	
									
							$ret['msg'] = true;
						}else{
							$ret['msg'] = __('This offer has been closed already or you are not the vendor. Only vendor can close this offer.', 'booking-works');
						}
					}
					
				break;
				
			}

				
		
		
		echo json_encode($ret);
		exit;
		
	}
	}
	
	add_action( 'wp_ajax_wp_ca_confirm_start', 'wpbw_confirm_se' );	
	add_action( 'wp_ajax_wp_ca_confirm_end', 'wpbw_confirm_se' );	
		
			
	if(!function_exists('wpbw_custom_single_add_to_cart_text')){
	function wpbw_custom_single_add_to_cart_text() {
		
		return __( 'Proceed and Book Now', 'woocommerce' );
	  
	}
	}
	
	if(!function_exists('wpbw_woo_in_cart')){
	function wpbw_woo_in_cart($product_id) {
		global $woocommerce;
		
		if(!is_admin()){
	 
			foreach($woocommerce->cart->get_cart() as $key => $val ) {
				$_product = $val['data'];


				if($product_id == $_product->get_id() ) {
					return true;
				}
			}
	 
		}
		return false;
	}
	}
			
	add_filter( 'woocommerce_add_to_cart_redirect', 'wpbw_redirect_on_add_to_cart' );
	
	if(!function_exists('wpbw_redirect_on_add_to_cart')){	
	
		function wpbw_redirect_on_add_to_cart() {
			
			//Get product ID
			if ( isset( $_POST['add-to-cart'] ) ) {
				//pree($_POST);exit;
				$add_to_cart = wpbw_sanitize_bw_data($_POST['add-to-cart']);
				$product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', $add_to_cart );
			
				//Check if product ID is in the proper taxonomy and return the URL to the redirect product
				$wp_ca_product_type = get_post_meta($product_id, '_wp_ca_product_type', true);
				//$product = new WC_Product( $product_id );
				//$product = wc_get_product($product_id);
				
				//pree($product);
				//pree($wp_ca_product_type);
				//pree($product->get_type());
				
				
				//exit;
				if($wp_ca_product_type=='renting'){// && $product->get_type()=='variable')
					if(isset($_POST['gwnt_payment_proceed_btn'])){
						$ret = wc_get_checkout_url();
					}else{
						$ret = get_permalink( $product_id );
					}
					//echo $ret;exit;
					return $ret;
				}
				
					
			}
			
		}			
		
	}
		
//	add_action( 'post_submitbox_misc_actions', 'wp_ca_custom_button' );
	
	/*function wp_ca_custom_button(){

	}*/
		
	add_action('admin_footer', 'wpbw_admin_footer_scripts');
	
	if(!function_exists('wpbw_admin_footer_scripts')){
		
		function wpbw_admin_footer_scripts(){
			 global $post;
			 $complete = '';
			 $label = '';
			 //pre($post);
	
	
			 if(is_object($post) && $post->post_type == 'product'){
				
		?>
				  <script type="text/javascript" language="javascript">
				  jQuery(document).ready(function($){
					   $('.wp-ca-section input[type="button"]').click(function(){
						   $(this).toggleClass('active');
						   var h = $(this).data('h');
						   var obj = $('.wp-ca-section input[name="'+h+'"]');
						   
						   if(obj.val()=='true')
						   obj.val('false');
						   else
						   obj.val('true');
						   
					   });
				  });
				  </script>
				  
				  <style type="text/css">
				  .wp-ca-section{
	
				  }
				  .wp-ca-section input[type="button"]{
					background-color:#FFF;
					font-size:12px;  
					color:#000;
				  }
				  .wp-ca-section input[type="button"].active,
				  .wp-ca-section input[type="button"]:hover{
					background:none !important;
					font-size:12px;  
					background-color:#0085ba !important;
					color:#fff;
					border:0;
					box-shadow:none;
				  }
		
				  </style>
		<?php  
			 }
		}	
		
	}
	
	

	if(!function_exists('wpbw_valid_user')){	
	
		function wpbw_valid_user($user_id=0){
			
			$roles = wpbw_options('roles'); 		
			$userdata = get_userdata( $user_id?$user_id:get_current_user_id() );
			$caps = $userdata->roles;
			$valid_caps = array();
			
			if(!empty($caps) && !empty($roles))
			$valid_caps = array_intersect(array_keys($roles), $caps);
			
			return (!empty($valid_caps));
		}
		
	}
		
	if(!function_exists('wpbw_account_menu_items')){				
		function wpbw_account_menu_items( $items =  array() ) {
			
			global $ns_add_product, $wp_ca_wishlist;
			
	
			
			$nitems = array();
			
			//pree($valid_caps);exit;
			
			if(wpbw_valid_user()){
				$wp_ca_pages_default = wpbw_get_pages();
				$wp_ca_pages_updated = wpbw_get_pages(true);
				
				
			
				if($ns_add_product)
				$nitems['add-product'] = $wp_ca_pages_default[$wp_ca_pages_updated['add_product']]; //add_item
				
				$nitems['my-products'] = $wp_ca_pages_default[$wp_ca_pages_updated['my_items']];
				
				$nitems['my-sales'] = $wp_ca_pages_default[$wp_ca_pages_updated['my_sales']];
				
				
				
				
				//pree($nitems);exit;
				if(!empty($nitems)){
					foreach($nitems as $nkey=>$item){
						$page = get_page_by_title( $item, ARRAY_A, 'page' );
						if(isset($page['post_status']) && $page['post_status']!='publish'){
							unset($nitems[$nkey]);
						}
					}
				}
			
			}
			
			$nitems = array_merge($nitems, $items);
			
			unset($nitems['downloads']);
			unset($nitems['edit-address']);
			unset($nitems['dashboard']);
	
			$nitems['orders'] = __('My Bills', 'booking-works');
			if(wpbw_valid_user()){
				$nitems['payments'] = __('Payment Settings', 'booking-works');
			}
			$nitems['edit-account'] = __('Change Password', 'booking-works');
			if($wp_ca_wishlist){
				$nitems['wishlist'] = get_option( 'yith_wcwl_socials_title' );
			}
			
			return $nitems;
		 
		}
	}
	
	add_filter( 'woocommerce_account_menu_items', 'wpbw_account_menu_items', 10, 1 );	
	
		
	add_filter( 'woocommerce_product_add_to_cart_text' , 'wpbw_custom_woocommerce_product_add_to_cart_text' );
	/**
	 * custom_woocommerce_template_loop_add_to_cart
	*/
	if(!function_exists('wpbw_custom_woocommerce_product_add_to_cart_text')){
	function wpbw_custom_woocommerce_product_add_to_cart_text() {
		global $product;

		if(is_null($product)){return; }
		
		$wp_ca_product_type = get_post_meta($product->get_id(), '_wp_ca_product_type', true);

		
		switch ( $wp_ca_product_type ) {
			default:
				return __( 'Add to cart', 'woocommerce' );
			break;
			case 'renting':
				$wp_ca_product_sub_type = get_post_meta($product->get_id(), '_wp_ca_product_sub_type', true);
				
				switch ( $wp_ca_product_sub_type ) {
					default:
					case 'rental':
						return __( 'Rent Now', 'woocommerce' );
					break;
					case 'on_demand_service':
						return __( 'Hire Now', 'woocommerce' );					
					break;
					case 'vacation_tour':
						return __( 'Book Now', 'woocommerce' );					
					break;				
				}
			break;
		}
		
	}
	}
		
	//add_filter( 'woocommerce_product_add_to_cart_url', 'wpbw_add_to_cart_url' );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'wpbw_add_to_cart_url' );
	if(!function_exists('wpbw_add_to_cart_url')){
	function wpbw_add_to_cart_url( $link ) {
		global $product, $woocommerce;
	
		$wp_ca_product_type = get_post_meta($product->get_id(), '_wp_ca_product_type', true);
		
		$btn = sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
					esc_url( $wp_ca_product_type=='renting'?$product->get_permalink():$product->add_to_cart_url() ),
					esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					esc_html( $product->add_to_cart_text() )
				);
		
		
		return $btn;
	}
	}
	
	if(!function_exists('wpbw_email_to_admin')){
	function wpbw_email_to_admin(){
		if(isset($_GET['e2a1'])){
			$e2a1 = wpbw_sanitize_bw_data($_GET['e2a1']);
			$redir = wp_login_url();
			if(is_user_logged_in()){		
				if(current_user_can('administrator')){
					$redir = admin_url('users.php?role=pending_vendor&action=approve_vendor&user_id='.$e2a1);
				}else{
					$redir = home_url('my-account');
				}
			}
			wp_redirect($redir);exit;			
		}
	}
	}
	
	if(!function_exists('wpbw_woo_endpoint_title')){
	function wpbw_woo_endpoint_title( $title, $id ) {

		if ( is_wc_endpoint_url( 'orders' ) ) {
			$title = "My Bills";
		}
		return $title;
	}
	}
	add_filter( 'the_title', 'wpbw_woo_endpoint_title', 10, 2 );	
	
	if(!function_exists('wpbw_updates_required')){
	function wpbw_updates_required($msg=''){
		
		/*$user_details = get_user_by('id', get_current_user_id() );
		$user_meta = get_user_meta(get_current_user_id());
		$user_meta_keys = array_keys($user_meta);*/
		//pre($user_details);
		//pre($user_meta);
		//pre($user_meta_keys);
		
		
		$updates_required = array(
			'/my-account/edit-address/billing' => 'Name & Address',
			'/my-account' => 'Personal Information',
		);
		if(!empty($updates_required)){ echo '<div class="wpbw_updates_required page-noprint col col-md-12"><strong>'.__($msg).'</strong><ul class="wp_bw_missing_buttons">'; foreach($updates_required as $link=>$title){ echo '<li><a class="button" href="'.$link.'" target="_blank">'.$title.'</a></li>'; } echo '</ul></div>';
		}
	}	
	}
	if(!function_exists('wpbw_become_a_vendor')){	
		function wpbw_become_a_vendor(){
			ob_start();
			echo '<div class="upgrading-account"><a href="/my-account/payments/">'.__('Upgrade to Vendor', 'booking-works').'</a></div>';
			$out1 = ob_get_contents();
			ob_end_clean();
			return $out1;
			
		}
	}
	
	add_shortcode('BECOME-A-VENDOR', 'wpbw_become_a_vendor');	
	
	if(!function_exists('wpbw_removing_customer_details_in_emails')){	
	function wpbw_removing_customer_details_in_emails( $order, $sent_to_admin, $plain_text, $email ){
		$wmail = WC()->mailer();
		remove_action( 'woocommerce_email_customer_details', array( $wmail, 'email_addresses' ), 20, 3 );
	}
	}
	add_action( 'woocommerce_email_customer_details', 'wpbw_removing_customer_details_in_emails', 5, 4 );
	
	if(!function_exists('wpbw_remove_all_quantity_fields')){	
		function wpbw_remove_all_quantity_fields( $return, $product ) {
			
			$ret = false;
			
			$wp_ca_product_type = get_post_meta($product->get_id(), '_wp_ca_product_type', true);
			$wp_ca_product_sub_type = get_post_meta($product->get_id(), '_wp_ca_product_sub_type', true);
			
			//pree($wp_ca_product_type);
			//pree($wp_ca_product_sub_type);
			
	
			switch($wp_ca_product_type){
					case 'renting':
						$ret = true;
						switch($wp_ca_product_sub_type){
							
							case 'on_demand_service':	
								
								
							
							break;
						}
						
					break;
			}
					
			return $ret;
		}
	}
	//add_filter( 'woocommerce_is_sold_individually', 'wpbw_remove_all_quantity_fields', 10, 2 );


    add_filter('woocommerce_cart_item_name', 'wpbw_add_tickte_print_link', 10, 2);

    add_action('woocommerce_thankyou', function ($order_id){

        global $thankyou_order_id;
        $thankyou_order_id = $order_id;

        add_filter('woocommerce_order_item_name', 'wpbw_add_tickte_print_link_thankyou', 10, 3);


    });


    add_action('woocommerce_order_details_before_order_table_items', function ($order){

        global $thankyou_order_id;
        $thankyou_order_id = $order->get_id();

        add_filter('woocommerce_order_item_name', 'wpbw_add_tickte_print_link_thankyou', 10, 3);


    });

    if(!function_exists('wpbw_add_tickte_print_link_thankyou')){

	    function wpbw_add_tickte_print_link_thankyou($product_name, $item, $is_visible){

	        global $wpdb, $thankyou_order_id;




	        $table_name = $wpdb->prefix.'calendars_management';
	        $product_id = $item['product_id'];

	        $query = "SELECT COUNT(*) FROM $table_name WHERE ca_object_id = $product_id AND ca_order_attached = $thankyou_order_id";

	        $count_rows = $wpdb->get_var($query);


	        $wp_ca_product_type = get_post_meta($product_id, '_wp_ca_product_type', true);
	                        $nonce = wp_create_nonce('wp_ca_print_ticket_action');

            $print_ticket = __('Print Ticket', 'booking-works');




	        if($wp_ca_product_type == 'renting' && $count_rows > 0){

                $product_name = "<a href='' data-product='$product_id' data-order='$thankyou_order_id' class='wp_ca_print_ticket' data-nonce='$nonce'>$print_ticket</a><br>".$product_name;

	        }

	        return $product_name;

	    }

	};



    add_filter('the_content', 'wpbw_show_ticket_thankyou_page');



    add_filter('woocommerce_thankyou_order_key', 'wpbw_show_ticket_thankyou_page');

    if(!function_exists('wpbw_show_ticket_thankyou_page')){

        function wpbw_show_ticket_thankyou_page($content){

            if(isset($_GET['key'])){


                $extended_key = wpbw_sanitize_bw_data($_GET['key']);
                $extended_key = explode('##', $extended_key);

                if(sizeof($extended_key) > 1){

                    $new_key = current($extended_key);
                    $order_product = end($extended_key);

                    $order_product_array = explode('|', $order_product);
                    $product_id = current($order_product_array);
                    $order_id = end($order_product_array);


                    $c_array = array('ca_type'=>'wc_product', 'ca_object_id'=>$product_id, 'ca_order_attached' => $order_id);
					//pree($c_array);
					$existing_booking_param = wpbw_get_actions('calendars_management', $c_array, false);

					global $new_content;
					ob_start();
					?>


                        <div class="container wp_ca_back_thankyou" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-md-12" ><a href="" class="btn btn-primary" data-key="<?php echo $new_key ?>"><?php _e('Back') ?></a></div>
                            </div>
                        </div>


                    <?php


                    wpbw_generate_ticket_by_item($product_id, 'ca_object_id', $existing_booking_param);

                    $new_content = ob_get_clean();


                        add_filter('woocommerce_thankyou_order_key', function($key)use($new_key){

                            return $new_key;

                        });



                    return $new_content;

                }else{

                    return $content;

                }

            }else{
                return $content;
            }

        }

    }

	if(!function_exists('wpbw_add_tickte_print_link')){

	    function wpbw_add_tickte_print_link($product_name, $item){

	        global $wpdb;


	        $table_name = $wpdb->prefix.'calendars_management';
	        $product_id = $item['product_id'];

	        $query = "SELECT COUNT(*) FROM $table_name WHERE ca_object_id = ".$product_id;

	        $count_rows = $wpdb->get_var($query);



	        $wp_ca_product_type = get_post_meta($product_id, '_wp_ca_product_type', true);


	        if($wp_ca_product_type == 'renting' && $count_rows > 0){

	            $checkout_link = wc_get_checkout_url();
                $product_name = "<a href='$checkout_link'>Print Ticket</a><br>".$product_name;

	        }

	        return $product_name;

	    }

	};
	
	
	add_action('wp_ajax_wp_ca_add_on_cart', 'wpbw_add_on_cart_callback');

	if(!function_exists('wpbw_add_on_cart_callback')){

	    function wpbw_add_on_cart_callback(){

	       if(!is_user_logged_in()){return;};


	       $return = array(
	           'status' => false,
	           'cart_key' => null,
	       );


	        if(!empty($_POST)){

	            if($_POST['update_cart'] == 'yes'){


                    $product_id = wpbw_sanitize_bw_data($_POST['product_id']);
                    $cart_key = WC()->cart->add_to_cart( $product_id ,$quantity = 1, $variation_id = 0, $variation = array(), $cart_item_data = array('Add on' => 'Polo'));

                   if(false !== $cart_key){

					$return['status'] = 'updated';
					$return['cart_key'] = $cart_key;

                   }

	            }else{


					$cart_key = wpbw_sanitize_bw_data($_POST['cart_key']);
					$return['status'] = WC()->cart->remove_cart_item($cart_key) ? 'deleted' : false;
					$return['cart_key'] = $return['status'] ? null : $cart_key;

	            }



	        }


	        echo json_encode($return);
	        wp_die();


	    }
	}



	add_filter('woocommerce_is_purchasable', function($param_1, $product){


	    $wp_ca_booking_option = get_post_meta($product->get_id(), '_wp_ca_booking_option', true);

	    return $param_1;

//	    if($wp_ca_booking_option && $wp_ca_booking_option == 'add_on' && !is_checkout()){
//
//	        return null;
//	    }else{
//	        return $param_1;
//	    }

	},10, 2);




	@include_once('functions-user.php');



	add_action('init', function(){

	    if(!empty($_POST)){
//	        pree($_POST);exit;
	    }



	});
	if(!function_exists('wpbw_plugin_linx')){
		function wpbw_plugin_linx($links) { 

		global $wp_ca_pro, $bw_premium_copy;


		$settings_link = '<a href="admin.php?page=wc_ca_settings">'.__('Settings', 'booking-works').'</a>';

		
		if($wp_ca_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wc_os_premium_link = '<a href="'.esc_url($bw_premium_copy).'" title="'.__('Go Premium', 'booking-works').'" target="_blank">'.__('Go Premium', 'booking-works').'</a>'; 
			array_unshift($links, $settings_link, $wc_os_premium_link); 
		
		}
				
		
		return $links; 
	}	
	}