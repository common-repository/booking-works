<?php defined( 'ABSPATH' ) or die( __('No script kiddies please!', 'booking-works') );

	if ( !current_user_can( 'install_plugins' ) ) {

		wp_die( __( 'You do not have sufficient permissions to access this page.', 'booking-works') );

	}



	global $wpdb, $wp_ca_data, $wp_ca_pro, $wp_ca_activated, $wp_ca_settings, $wp_ca_currency, $wp_ca_required_plugins, $wp_ca_all_plugins, $wp_ca_plugins_activated;

	$roles = wpbw_options('roles'); 
	$roles = $roles ? $roles : array();



	//wp_ca_pree($wp_ca_settings);

	//pree($roles);

?>





<div class="wrap bw_settings_div">



        







        <div class="icon32" id="icon-options-general"><br></div><h1><?php echo $wp_ca_data['Name']; ?> <?php echo '('.$wp_ca_data['Version'].($wp_ca_pro?') Pro':')'); ?> - <?php _e("Settings"); ?></h1> 

    

         

           

        <h2 class="nav-tab-wrapper">

        	<a class="nav-tab nav-tab-active"><?php _e("Dashboard"); ?></a>
			<a class="nav-tab"><?php _e("Permissions"); ?></a>
            <a class="nav-tab"><?php _e("Advanced Settings"); ?></a>

        </h2>      







<?php if(!$wp_ca_activated): ?>

<div class="wp_ca_notes">

<h2><?php _e("You need WooCommerce plugin to be installed and activated."); ?> <?php _e("Please"); ?> <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank"><?php _e("Install"); ?></a> <?php _e("and"); ?>/<?php _e("or"); ?> <a href="plugins.php?plugin_status=inactive" target="_blank"><?php _e("Activate"); ?></a> WooCommerce <?php _e("plugin to proceed"); ?>.</h2>

<br />

<br />

<br />

<br />

<br />

<br />

<br />

<br />

<br />

<br />

<br />

<br />

</div>

<?php exit; endif; ?>



<form class="nav-tab-content" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<input type="hidden" name="bw_tn" value="<?php echo isset($_GET['t'])?wpbw_sanitize_bw_data($_GET['t']):'0'; ?>" />

<?php wp_nonce_field( 'wp_ca_settings_action', 'wp_ca_settings_field' ); ?>



<?php

if(!empty($wp_ca_required_plugins)){

?>

<h3><?php _e('Statistics', 'booking-works'); ?></h3>

<ul class="stats_total">	

<?php		



$symbol = get_woocommerce_currency_symbol();



$queries = array();

$queries['total products'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts WHERE post_type IN ('product')", 'link' => 'edit.php?post_type=product');



$queries[wpbw_messages('type').' products'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts p, $wpdb->postmeta pm WHERE p.post_type IN ('product') AND pm.meta_key='_wp_ca_product_type' AND pm.meta_value='renting' AND pm.post_id=p.ID", 'link' => '');



$queries['total bookings'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts p, $wpdb->postmeta pm, ".$wpdb->prefix."calendars_management cm WHERE cm.ca_object_id=p.ID AND p.post_type IN ('product') AND pm.meta_key='_wp_ca_product_type' AND pm.meta_value='renting' AND pm.post_id=p.ID", 'link' => '');



$queries['bookings in progress'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts p, $wpdb->postmeta pm, ".$wpdb->prefix."calendars_management cm WHERE cm.ca_status IN ('booked', 'active') AND cm.ca_object_id=p.ID AND p.post_type IN ('product') AND pm.meta_key='_wp_ca_product_type' AND pm.meta_value='renting' AND pm.post_id=p.ID", 'link' => '');



$queries['bookings completed'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts p, $wpdb->postmeta pm, ".$wpdb->prefix."calendars_management cm WHERE cm.ca_status IN ('closed') AND cm.ca_object_id=p.ID AND p.post_type IN ('product') AND pm.meta_key='_wp_ca_product_type' AND pm.meta_value='renting' AND pm.post_id=p.ID", 'link' => '');





$queries['total orders'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->posts WHERE post_type IN ('shop_order')", 'link' => 'edit.php?post_type=shop_order');



$queries['total sales'] = array('query' => "SELECT CONCAT('".$symbol."', SUM(pm.meta_value)) AS total FROM $wpdb->posts p, $wpdb->postmeta pm WHERE p.post_type IN ('shop_order') AND pm.post_id = p.ID AND pm.meta_key='_order_total'", 'link' => '');



$queries['booking sales'] = array('query' => "SELECT CONCAT('".$symbol."', SUM(pm.meta_value)) AS total FROM $wpdb->posts p, $wpdb->postmeta pm, ".$wpdb->prefix."calendars_management cm WHERE cm.ca_status IN ('booked', 'closed') AND cm.ca_order_attached=p.ID AND p.post_type IN ('shop_order') AND pm.post_id = p.ID AND pm.meta_key='_order_total'", 'link' => '');



$queries['users'] = array('query' => "SELECT COUNT(*) AS total FROM $wpdb->users", 'link' => 'users.php');







if(!empty($queries)){

	foreach($queries as $cap=>$arr){		

		//pree($arr['query']);

		$rows = $wpdb->get_row($arr['query']);			

		

?>

<li><strong><?php echo $cap; ?>:</strong> <?php if($arr['link']!=''){ ?><a href="<?php echo $arr['link']; ?>" target="_blank"><?php echo $rows->total; ?></a><?php }else{ echo $rows->total; } ?></li>	

<?php		

	}

}



?>	

</ul>

<?php    

}

?><br />



	











</form>


<form class="nav-tab-content hides" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<input type="hidden" name="bw_tn" value="<?php echo isset($_GET['t'])?wpbw_sanitize_bw_data($_GET['t']):'0'; ?>" />

<?php wp_nonce_field( 'wp_ca_settings_action3', 'wp_ca_settings_field3' ); ?>

<br />

<br />



<div class="col col-md-12">

<strong><?php _e('Select user roles to allow add/edit product/services:', 'booking-works'); ?></strong>

<table border="0" style="margin:0 0 40px 0; padding:0">
<?php foreach (get_editable_roles() as $role_name => $role_info): ?>
<tr>
<td><input type="checkbox" name="wp_ca_options[roles][<?php echo $role_name ?>]" value="1" id="<?php echo $role_name ?>" <?php checked(array_key_exists($role_name, $roles)); ?>/></td>
<td><label for="<?php echo $role_name ?>"><?php echo $role_name ?></label></td>
</tr>
<?php endforeach; ?>




</table>



<input type="submit" class="btn btn-primary " value="<?php _e('Save Changes', 'booking-works'); ?>" /><br />

<br />



</div>


</form>

<form class="nav-tab-content hides" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<input type="hidden" name="bw_tn" value="<?php echo isset($_GET['t'])?wpbw_sanitize_bw_data($_GET['t']):'0'; ?>" />

<?php wp_nonce_field( 'wp_ca_settings_action2', 'wp_ca_settings_field2' ); ?>

<br />

<br />





<?php

$logo = wpbw_options('logo'); 

?>

<div class="col col-md-12">

<?php if($logo): ?>

<img src="<?php echo $logo; ?>" style="max-height:50px;" /><br />

<?php endif; ?>

<input style="width:40%" type="text" name="wp_ca_options[logo]" placeholder="<?php _e('Logo URL', 'booking-works'); ?>" value="<?php echo $logo; ?>" />

<br /><br />

<textarea style="width:40%" name="wp_ca_options[ticket_terms]" placeholder="<?php _e('Terms & Conditions Excerpt For Ticket View', 'booking-works'); ?>"><?php echo wpbw_options('ticket_terms'); ?></textarea>

<br /><br />

<table border="0" style="margin:0 0 40px 0; padding:0">
<tr>
<td style="width:20px"><input style="position:relative; top:-4px" id="virtual" type="radio" name="wp_ca_options[wp_ca_type]" value="virtual" <?php checked( wpbw_options('wp_ca_type', 'virtual'), 'virtual' ); ?> /></td>
<td><label for="virtual"><?php _e('Online', 'booking-works'); ?> <?php _e('Courses', 'booking-works'); ?>/<?php _e('Products', 'booking-works'); ?>/<?php _e('Services', 'booking-works'); ?></label></td>
</tr>

<tr>
<td><input style="position:relative; top:-4px" id="tangible" type="radio" name="wp_ca_options[wp_ca_type]" value="tangible" <?php checked( wpbw_options('wp_ca_type', 'virtual'), 'tangible' ); ?> /></td>
<td><label for="tangible"><?php _e('Tangible', 'booking-works'); ?> <?php _e('Products', 'booking-works'); ?>/<?php _e('Services', 'booking-works'); ?>/<?php _e('Activities', 'booking-works'); ?></label></td>
</tr>

<tr>
<td colspan="2">&nbsp;</td>
</tr>

<tr>
<td colspan="2"><strong><?php _e('Calendar Selection', 'booking-works'); ?>:</strong></td>
</tr>
<tr>
<td><input style="position:relative; top:-4px" id="wp_ca_multiple_on" type="radio" name="wp_ca_options[wp_ca_multiple]" value="multiple" <?php checked( wpbw_options('wp_ca_multiple', 'single'), 'multiple' ); ?> /></td>
<td><label for="wp_ca_multiple_on"><?php _e('Multiple', 'booking-works'); ?></label></td>
</tr>
<tr>
<td><input style="position:relative; top:-4px" id="wp_ca_multiple_off" type="radio" name="wp_ca_options[wp_ca_multiple]" value="single" <?php checked( wpbw_options('wp_ca_multiple', 'single'), 'single' ); ?> /></td>
<td><label for="wp_ca_multiple_off"><?php _e('Single', 'booking-works'); ?></label></td>
</tr>

<tr>
<td colspan="2">&nbsp;</td>
</tr>

<tr>
<td colspan="2"><strong><?php _e('Product Posting', 'booking-works'); ?>:</strong></td>
</tr>
<tr class="wp_ca_posting_options">
<td><input style="position:relative; top:-4px" id="wp_ca_posting_free" type="radio" name="wp_ca_options[wp_ca_posting]" value="free" <?php checked( wpbw_options('wp_ca_posting', 'free'), 'free' ); ?> /></td>
<td><label for="wp_ca_posting_free"><?php _e('Free', 'booking-works'); ?></label></td>
</tr>
<tr class="wp_ca_posting_options">
<td><input style="position:relative; top:-4px" id="wp_ca_posting_paid" type="radio" name="wp_ca_options[wp_ca_posting]" value="paid" <?php checked( wpbw_options('wp_ca_posting', 'free'), 'paid' ); ?> /></td>
<td><label for="wp_ca_posting_paid"><?php _e('Paid', 'booking-works'); ?></label></td>

</tr>
<tr id="wp_ca_posting_fee">
<td colspan="2"><?php echo get_woocommerce_currency_symbol(); ?><input value="<?php echo wpbw_options('wp_ca_posting_fee', '0'); ?>" style="width:40px" type="text" name="wp_ca_options[wp_ca_posting_fee]" placeholder="0.20"  /></td>
</tr>

<tr>
<td colspan="2">&nbsp;</td>
</tr>

</table>



<input type="submit" class="btn btn-primary " value="<?php _e('Save Changes', 'booking-works'); ?>" /><br />

<br />



</div>





<?php

if(!empty($wp_ca_required_plugins)){

?>

<h3><?php _e('Following plugins are (maybe) required to be installed and activated.', 'booking-works'); ?></h3>

<ul>	

<?php

	foreach($wp_ca_required_plugins as $plugin=>$path){

		

		$install_link = 'plugin-install.php?tab=search&type=term&s=';

		$activate_link = 'plugins.php?plugin_status=inactive&s=';

		

		if(array_key_exists($path, $wp_ca_all_plugins)){

			

			

			

			if(in_array($path, $wp_ca_plugins_activated)){

				$title = '';

				$link = '';	

			}else{

				$title = __('Click here to activate', 'booking-works');

				$link = $activate_link.$plugin;

			}

			

			

		}else{

			$title = __('Click here to install', 'booking-works');

			$link = $install_link.$plugin;

			

		}

		

?>

<li><?php echo $plugin; ?> <?php if($title!=''){ ?><a href="<?php echo $link; ?>" target="_blank" title="<?php $title; ?>"><?php echo $title; ?></a><?php }else{ echo ' / '.__('Installed', 'booking-works').' & '.__('Activated', 'booking-works'); } ?></li>

<?php		

	}

?>	

</ul>

<?php    

}

?><br />



<h3><?php _e('Following pages are required for this plugin. Please click setup pages if any of them does not exist. If any page is trashed, please restore that or delete permanently and then setup again.', 'booking-works'); ?></h3>





<?php

	$get_pages = wpbw_get_pages();

	//pree($get_pages);

	$setup = 0;

	if(!empty($get_pages)){

		$setup_pages = count($get_pages);

		

		foreach($get_pages as $id=>$title){

			

			switch(substr($id, 0, 1)){

				case 'N':

					$id = 'Does Not Exist';

				break;

				case 'D':

					$id = '<a href="edit.php?post_status=draft&post_type=page" target="_blank">Drafted</a>';

				break;

				case 'X':

					$id = '<a href="edit.php?post_status=trash&post_type=page" target="_blank">Trashed</a>';

				break;

				default;

					$setup++;

					$id = '<a href="post.php?post='.$id.'&action=edit" target="_blank">Edit</a> | <a href="'.get_permalink($id).'" target="_blank">View</a>';

				break;

			}

			echo $id.' - '.$title.'<br /><br />';

		}

		

?>

<br />

<br />

<?php if($setup!=$setup_pages){ ?>

<a href="options-general.php?page=wc_ca_settings&wp_ca_setup" class="button button-secondary"><?php _e('Setup Pages', 'booking-works'); ?></a>

<?php } ?>

<?php		

	}

?>

	











</form>



















</div>



<script type="text/javascript" language="javascript">

jQuery(document).ready(function($) {

	

	

	

});	

</script>



<style type="text/css">

<?php echo implode('', $css_arr); ?>

	#wpfooter{

		display:none;

	}

<?php if(!$wp_ca_pro): ?>



	#adminmenu li.current a.current {

		font-size: 12px !important;

		font-weight: bold !important;

		padding: 6px 0px 6px 12px !important;

	}

	#adminmenu li.current a.current,

	#adminmenu li.current a.current span:hover{

		color:#9B5C8F;

	}

	#adminmenu li.current a.current:hover,

	#adminmenu li.current a.current span{

		color:#fff;

	}	

<?php endif; ?>

	.woocommerce-message,

	.update-nag{

		display:none;

	}

</style>

<script type="text/javascript" language="javascript">

    jQuery(document).ready(function($){


        <?php if(isset($_GET['t'])): ?>
			$('.nav-tab-wrapper .nav-tab:nth-child(<?php echo wpbw_sanitize_bw_data($_GET['t'])+1; ?>)').click();
        <?php endif; ?>

		<?php if(isset($_POST['bw_tn'])): ?>
			$('.nav-tab-wrapper .nav-tab:nth-child(<?php echo wpbw_sanitize_bw_data($_POST['bw_tn'])+1; ?>)').click();			
		<?php endif; ?>		

    });

</script>

