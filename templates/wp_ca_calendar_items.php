<?php

$cart = WC()->cart->get_cart();

$cart_item_array = array();

if(!empty($cart)){
    foreach ($cart as $cart_key => $cart_item){

        $cart_item_array[$cart_item['product_id']] = $cart_item['key'];

    }
}


?>

<div class="wp_ca_calendar_items col col-md-12">

<form class="form-horizontal">


    <div class="row wp_ca_step step_1">
        <div class="col-md-12">
<div style="text-align:left">



    <div class="form-group wp_ca_nav_back">

		<a class="btn btn-info  back"><?php _e('Back', 'booking-works'); ?></a>

    </div>

    <?php if(!$is_edit): ?>

    <div class="form-group book_date">

    	<label><?php _e('Date', 'booking-works'); ?>/<?php _e('Time', 'booking-works'); ?>:</label> <span></span>

    </div>

    <?php endif; ?>

    

    <div class="form-group book_item">

        <?php
            $item_label = wpbw_messages('item') ? wpbw_messages('item'): __('Item', 'booking-works');
        ?>

    	<label><?php echo $item_label; ?>:</label> <span></span>

    </div>    

   

    <div class="form-group book_item_price">

        <?php

            $msg = wpbw_messages('price_type') ? wpbw_messages('price_type').'/' : '';

        ?>

    	<label><?php echo $msg; ?><?php _e('Price', 'booking-works'); ?>:</label> <span></span>

    </div>

    <?php

    $add_ons =  WP_CA_METABOX::get_product_add_on($post->ID);
    global $product;

    if(!empty($add_ons) && !$is_edit):


    ?>


    <div class="form-group h4">

        <label>

            <?php _e('Add-on:', 'booking-works'); ?>

        </label>


        <hr style="width: 60%; margin: 10px 0 0 0;">

    </div>


        <?php




            foreach ($add_ons as $index => $add_on):


                $cart_key_exit = array_key_exists($add_on['id'], $cart_item_array);
                $cart_key = $cart_key_exit ? $cart_item_array[$add_on['id']] : '';


        ?>



    <div class="form-group form-check">
        <input <?php echo $cart_key_exit ? 'checked' : '' ?> class="form-check-input wp_ca_product_add_ons" type="checkbox" name="wp_ca_product_add_ons[]" value="<?php echo $add_on['id']; ?>" id="add_on<?php echo $add_on['id']; ?>" data-price="<?php echo $add_on['price']; ?>" data-cart_key="<?php echo $cart_key; ?>">
        <label class="form-check-label" for="add_on<?php echo $add_on['id']; ?>" style="font-weight: normal; font-size: 15px; cursor: pointer">
            <?php echo $add_on['title']." - ".$add_on['formatted_price'] ?>
        </label>
    </div>

    <?php

        endforeach;


        ?>

        <br>

        <div class="form-group book_item_total_price" data-total="<?php echo $product->get_price(); ?>">

            <label><?php _e('Total Price', 'booking-works'); ?>:</label> <span class="wp_ca_total_price"></span>

        </div>


    <?php

        endif;
    ?>

    

   <?php if(!$is_edit): ?>

    <div class="form-group book_item_duration hide">

    	<label><?php _e('Duration', 'booking-works'); ?>/<?php _e('Validity', 'booking-works'); ?>:</label> <span></span>

    </div>    

    
<?php 
	if($poi_acf){
		
		$pickup_time = get_field( 'pickup_time', $post->ID );
		$return_time = get_field( 'return_time', $post->ID );
		if($pickup_time!='' || $return_time!=''){
?>		
    <div class="form-group book_times">

    	<label><?php _e('Pickup Time', 'booking-works'); ?>: <span><?php echo $pickup_time; ?></span></label>

		<label><?php _e('Return Time', 'booking-works'); ?>: <span><?php echo $return_time; ?></span></label>

		

		<small><?php _e('These are proposed timings from the vendor, if you have any other preferred timing so you can write below in notes.', 'booking-works'); ?></small><br />
    </div>
<?php
		}
	}
?>	

    <div class="form-group book_notes">

    	<label><?php _e('Notes', 'booking-works'); ?>: <span>(<?php _e('Optional', 'booking-works'); ?>)</span></label>

        <textarea class="form-control" placeholder="<?php _e('Any instructions for provder', 'booking-works'); ?>..."><?php echo $ca_user_remarks; ?></textarea>

    </div>

    <?php endif; ?>

     

</div>

        </div>
    </div>




    <?php 

	//pree($existing_booking);

	wpbw_woocommerce_before_single_product_inner(true, $existing_bookings); ?>




    <div class="row wp_ca_step <?php echo !$is_edit ? 'step_3': ''; ?>">
        <div class="col-md-12">
    <div class="form-group cta">

    <?php if(is_user_logged_in()): ?>

		<?php if(!$is_owner): ?>

            <button type="button" class="btn btn-primary btn-lg book_confirm"><?php _e('Book Now', 'booking-works'); ?></button>

        <?php else: ?>

        	<p><?php echo wpbw_messages('error_1'); ?></p>

        <?php endif; ?>

	<?php else: ?>

		<button type="button" class="btn btn-danger btn-lg login_proceed"><?php _e('Login to Proceed', 'booking-works'); ?></button>

    <?php endif; ?>

   

    </div>
        </div>
    </div>

</form> 





</div>