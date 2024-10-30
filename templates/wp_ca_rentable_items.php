<?php global $product, $wp_ca_url;


?>

<style type="text/css">

#carousel-custom {

    margin-top:  10px;

    width: 100%;
	float:left;

}

#carousel-custom .carousel-indicators {

    margin: 10px 0 0;

    overflow: auto;

    position: static;

    text-align: left;

    white-space: nowrap;

    width: 100%;

}

#carousel-custom .carousel-indicators li {

    background-color: transparent;

    -webkit-border-radius: 0;

    border-radius: 0;

    display: inline-block;

    height: auto;

    margin: 0 !important;

    width: auto;

}

#carousel-custom .carousel-inner .item img{

	height:300px;
    margin: auto;



}

#carousel-custom .carousel-indicators li img {

    display: block;

    opacity: 0.5;

	height:100px;

}

#carousel-custom .carousel-indicators li.active img {

    opacity: 1;

}

#carousel-custom .carousel-indicators li:hover img {

    opacity: 0.75;

}

#carousel-custom .carousel-outer {

    position: relative;

}

.carousel-control.left {

    background-image: none;

    width: 54px;

    height: 54px;

    top: 50%;

    left: 20px;

    margin-top: -27px;

    line-height: 54px;

    border: 2px solid #fff;

    opacity: 1;

    text-shadow: none;

    -webkit-transition: all 0.2s ease-in-out 0s;

    -o-transition: all 0.2s ease-in-out 0s;

    transition: all 0.2s ease-in-out 0s;

}

.carousel-control.right {

    background-image: none;

    width: 54px;

    height: 54px;

    top: 50%;

    right: 20px;

    margin-top: -27px;

    line-height: 54px;

    border: 2px solid #fff;

    opacity: 1;

    text-shadow: none;

    -webkit-transition: all 0.2s ease-in-out 0s;

    -o-transition: all 0.2s ease-in-out 0s;

    transition: all 0.2s ease-in-out 0s;

}

.carousel-control.left,

.carousel-control.right{

	background-color:#333;

	opacity:0.2;

}

.carousel-control.left:hover,

.carousel-control.right:hover{

	opacity:0.6;

}



.reviews-section ul{

	height:422px;

	overflow:auto;

}

.reviews-section img{

	height:40px;

	margin-right:20px;

}

.booking-section{



	/*text-align:center;*/

	margin:30px 0;

}

.booking-section > .booking-module{

	display:none;

}

.booking-section > a{

	display:inline-block;

	margin: 0 0 30px 0;

}

.more-items-section .list li {

  display: table;

  border-collapse: collapse;

  width: 100%;

  height:200px;

}

.more-items-section .inner {

  display: table-row;

  overflow: hidden;

}

.more-items-section .li-img {

  display: table-cell;

  vertical-align: top;

  width: 30%;

  padding-right: 1em;

  

}

.more-items-section .li-img img {

  display: block;

  height: 140px;

  width:auto;

  max-width:none;

}

.more-items-section .li-text {

  display: table-cell;

  vertical-align: top;

  width: 70%;

}

.more-items-section .li-head {

  margin: 0;

  font-size:18px;
  text-transform:capitalize;

}

.more-items-section .li-sub {

  margin: 0;

  font-size:14px;

}

.about-seller-section > p{
	word-wrap: break-word;
}

.about-seller-section .li-img img{

	height:140px;

	

}

.about-seller-section .li-img{

	width:20%;

}

.about-seller-section .li-text {

	width:80%;

}

.about-seller-section .li-head {

	font-size:20px;

}

.about-seller-section a:hover,

.about-seller-section a:hover .li-sub {

	text-decoration:none;

}

.rentable_view{

	margin:40px 0 0 0;

}

.rentable_view h1{

	font-size:22px;
	float:left;
	width:auto;
	text-transform:capitalize;

}

.rentable_view .reviews-section h4{

	font-size:18px;

}

.rentable_view .wp_ca_duration_section{



}

.wp_ca_hours_selection .dropdown-menu li a,

.rentable_view .wp_ca_duration_section .dropdown li a{

	cursor:pointer;

}

.wp_ca_product_desc{

	padding:0;

	max-height:300px;

	overflow-y:auto;
	margin-top:20px;

}

.wp_ca_product_desc div > p{
	word-wrap: break-word;
}

.wp_ca_product_price,
.wp_ca_product_video{
	margin-top:20px;
	padding:0;
}

.wp_ca_product_desc > strong,
.wp_ca_product_price > strong,
.wp_ca_product_video > strong,
.wp_ca_product_types_div > strong,
.wp_ca_sale_switch > strong,
.wp_ca_sale > strong{

	font-size:18px;

}

.wp_ca_sale{
	visibility:hidden;
}

.wp_ca_sale_switch,
.wp_ca_sale{
	margin-top:10px !important;
}

.wp_ca_sale_switch .btn{
	margin-right:0;
}

div.rentable_view ol.carousel-indicators li > a{
	display:none;
}

div.rentable_view.owner_view ol.carousel-indicators li{
	position:relative;
}

div.rentable_view.owner_view ol.carousel-indicators li > a{
	position:absolute;
	top:0;
	right:0;
	height:10px;
	width:60px;
	cursor:pointer;	
	color: #FFF;
	background-color:#F00;
	font-size:10px;
	
}

div.rentable_view.owner_view ol.carousel-indicators li:hover > a{
	display:block;
}



div.rentable_view.owner_view ol.carousel-indicators li:hover > a:before{
	font-family:"FontAwesome";
	content:"\f00d";		
}

.author-section-top-right{
}

.author-section-top-right a.request_custom{
	cursor:pointer;
}

.author-section-top-right a img{
	height:auto;	
}

.author-section-top-right .wp_inbox_message_box_toggle_div{
	display:none;
}

.row .mb-3{
    margin-bottom: 1em;
}

.about-seller-section{
    padding-top: 1em;
}

.wp_ca_step_parent{
    position: relative;
}

.wp_ca_step_parent img.step2_img {
    width: 200px;
    height: auto;
    position: absolute;
    right: -105px;
    top: auto;
}

.wp_ca_step{
    background-repeat: no-repeat;
    background-position: right;
}

.wp_ca_step.step_1{
    background-image: url("<?php echo $wp_ca_url; ?>images/step1.svg");
    background-position: 105% 0%;
}

.wp_ca_step.step_2{
    background-image: url("<?php echo $wp_ca_url; ?>images/step2.svg");
    background-position: 106% 0%;
}

.wp_ca_step.step_2 .col-md-12{
    padding-right: 75px;
}

.wp_ca_step.step_3{

    background-image: url("<?php echo $wp_ca_url; ?>images/step3.svg");
    background-position: 94% 0%;
    padding-top: 8%;
    min-height: 200px;


}

.book_notes textarea{

    width: 87%;
}


.right-sidebar .content-area {
    width: 100%;

}

.content-area {
    width: 100%;

}

.booking-module .wp_ca_tables{
    margin-bottom: 20px;
}


/*

@media all and (min-width: 45em) {

.more-items-section .list li {



    width: 100%;

  }

}



@media all and (min-width: 75em) {

.more-items-section .list li {

    width: 100%;

  }

}*/



</style>

<div class="container-fluid rentable_view <?php echo $is_edit?'owner_view':'user_view'; ?>">
	


  <div class="content">


      <div class="wp_ca_main_title">


          <?php if($is_edit): ?>
              <div class="row mb-3">

                  <div class="col-md-12 wp_ca_product_types_div">
                      <strong data-type="product_type"><?php echo __('Type', 'booking-works'); ?>:</strong>
                      <div>
                          <?php echo do_shortcode('[WP-CA-PRODUCT-TYPES]'); ?>
                      </div>
                  </div>
              </div>
          <?php endif; ?>



          <div class="row mb-3">
              <div class="col-md-12">
                  <h1 class="h1" data-type="post_title"><?php the_title(); ?></h1>
              </div>
          </div>

      </div>


      <div class="wp_ca_gallery">

          <div class="row">

              <div class="col-md-8 ">



                  <div id="carousel-custom" class="carousel slide" data-ride="carousel">

                      <!-- Wrapper for slides -->

                      <div class="carousel-inner" role="listbox">



                          <?php $c = 0; if(!empty($images)){ foreach($images as $i_k=>$image){ $c++; ?>

                              <div class="item <?php echo ($c==1?'active':''); ?>">

                                  <?php if($image[1]!=''): ?>
                                      <iframe width="100%" height="300" src="<?php echo $image[1]; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                  <?php else: ?>
                                      <img data-id="<?php echo (is_numeric($i_k) && $i_k>0)?$i_k:''; ?>" src="<?php echo $image[0]; ?>" class="img-responsive <?php echo $i_k; ?>" data-full="<?php echo $image[1]; ?>">
                                  <?php endif; ?>

                              </div>

                          <?php } } ?>



                      </div>



                      <!-- Controls -->

                      <a class="left carousel-control" href="#carousel-custom" role="button" data-slide="prev">

                          <i class="fa fa-chevron-left"></i>

                          <span class="sr-only"><?php _e('Previous', 'booking-works'); ?></span>

                      </a>

                      <a class="right carousel-control" href="#carousel-custom" role="button" data-slide="next">

                          <i class="fa fa-chevron-right"></i>

                          <span class="sr-only"><?php _e('Next', 'booking-works'); ?></span>

                      </a>





                      <!-- Indicators -->

                      <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block">


                          <?php $c = 0; if(!empty($images)){ ?>



                              <?php foreach($images as $i_k=>$image){  ?>

                                  <li data-target="#carousel-custom" data-slide-to="<?php echo $c; ?>" class="<?php echo ($c==0?'active':''); ?>">

                                      <img data-id="<?php echo $del = (is_numeric($i_k) && $i_k>0)?$i_k:''; ?>" src="<?php echo $image[0]; ?>" class="img-responsive <?php echo $i_k; ?>" data-full="<?php echo $image[1]; ?>">
                                      <?php if($del): ?>
                                          <a title="<?php _e('Click here to delete this item', 'booking-works'); ?>"><span class="fa fa-remove"></span></a>
                                      <?php endif; ?>
                                  </li>

                                  <?php $c++; } ?>



                          <?php } ?>




                      </ol>

                  </div>



              </div>


              <div class="col-md-4 reviews-section">
                  <?php if($wp_ca_wishlist): ?>
                      <div class="col col-md-12 nopadding">
                          <a style="margin-top:30px;" class="pull-right" href="/my-account/wishlist/?add_to_wishlist=<?php echo $post->ID; ?>"><?php echo get_option( 'yith_wcwl_add_to_wishlist_text' ); ?></a>
                      </div>
                  <?php endif; ?>
                  <?php

                  $get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

                  $ptype = $product->get_type();

                  $available_variations = (($ptype=='variable' && $get_variations) ? $product->get_available_variations() : false);

                  $attributes           = ($ptype=='variable'?$product->get_variation_attributes():array());

                  $selected_attributes  = ($ptype=='variable'?$product->get_default_attributes():array());

                  $userdata = get_userdata( $post->post_author );

                  if(array_key_exists( "administrator", $userdata->caps)){

                      ?>

                      <div class="wp_ca_admin_support pull-right hide">

                          <p><?php _e('Any questions about booking?', 'booking-works'); ?></p>

                          <a class="btn btn-primary pull-right" href="<?php echo home_url(); ?>/contact" target="_blank"><?php echo __('Click here to contact us', 'booking-works'); ?></a>

                      </div>

                      <?php

                  }

                  ?>

                  <?php

                  $rid_valid = false;
                  $rid = (isset($_GET['rid'])?wpbw_sanitize_bw_data($_GET['rid']):0);
                  if($rid>0){
                      $rid_valid = wpbw_is_user_order($rid);
                      //pree($rid_data);
                  }


                  if(!$is_owner && !$rid_valid){



                      switch($ptype){

                      default:



                      if ( $product->is_in_stock()) : ?>



                          <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>



                          <form class="cart" method="post" enctype='multipart/form-data' style="display:none">

                              <?php

                              /**

                               * @since 2.1.0.

                               */

                              do_action( 'woocommerce_before_add_to_cart_button' );



                              /**

                               * @since 3.0.0.

                               */

                              do_action( 'woocommerce_before_add_to_cart_quantity' );

                                $quantity = wpbw_sanitize_bw_data($_POST['quantity']);

                              woocommerce_quantity_input( array(

                                  'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),

                                  'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),

                                  'input_value' => isset( $quantity ) ? wc_stock_amount( $quantity ) : $product->get_min_purchase_quantity(),

                              ) );



                              /**

                               * @since 3.0.0.

                               */

                              do_action( 'woocommerce_after_add_to_cart_quantity' );

                              ?>



                              <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>



                              <?php

                              /**

                               * @since 2.1.0.

                               */

                              do_action( 'woocommerce_after_add_to_cart_button' );

                              ?>

                          </form>



                      <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>



                      <?php endif;



                      break;



                      case 'variable':



                      if(!wpbw_woo_in_cart($product->get_id())){



                      $attribute_keys = array_keys( $attributes );



                      do_action( 'woocommerce_before_add_to_cart_form' ); ?>



                          <form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">

                              <?php do_action( 'woocommerce_before_variations_form' ); ?>



                              <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>

                                  <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

                              <?php else : ?>

                                  <table class="variations" cellspacing="0">

                                      <tbody>

                                      <?php foreach ( $attributes as $attribute_name => $options ) : ?>

                                          <tr>

                                              <td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>

                                              <td class="value">

                                                  <?php

                                                  $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );

                                                  wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );

                                                  echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) : '';

                                                  ?>

                                              </td>

                                          </tr>

                                      <?php endforeach;?>

                                      </tbody>

                                  </table>



                                  <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>



                                  <div class="single_variation_wrap">

                                      <?php

                                      /**

                                       * woocommerce_before_single_variation Hook.

                                       */

                                      do_action( 'woocommerce_before_single_variation' );



                                      /**

                                       * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.

                                       * @since 2.4.0

                                       * @hooked woocommerce_single_variation - 10 Empty div for variation data.

                                       * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.

                                       */

                                      do_action( 'woocommerce_single_variation' );



                                      /**

                                       * woocommerce_after_single_variation Hook.

                                       */

                                      do_action( 'woocommerce_after_single_variation' );

                                      ?>

                                  </div>



                                  <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

                              <?php endif; ?>



                              <?php do_action( 'woocommerce_after_variations_form' ); ?>

                          </form>



                      <?php

                      do_action( 'woocommerce_after_add_to_cart_form' );

                      ?>
                          <script type="text/javascript" language="javascript">

                              jQuery(document).ready(function($){

                                  function getPropertyCount(obj) {
                                      var count = 0,
                                          key;

                                      for (key in obj) {
                                          if (obj.hasOwnProperty(key)) {
                                              count++;
                                          }
                                      }

                                      return count;
                                  }



                                  $('.renting.single-product').on('click', '.variations_form.cart .single_add_to_cart_button', function(event){
                                      event.preventDefault();
                                      if($('input[type="hidden"][name="variation_id"]').val()=='0'){
                                          var variations = $('.variations_form.cart').data('product_variations');


                                          $.each(variations, function(i, v){
                                              var attribs = getPropertyCount(v.attributes);
                                              var attrib_count = 0;
                                              $.each(v.attributes, function(names, values){

                                                  names = names.replace('attribute_', '');
                                                  if($('.variations_form.cart table.variations #'+names).val()==values){
                                                      attrib_count++;
                                                  }
                                              });
                                              //document.title = attribs+' - '+attrib_count;
                                              if(attribs==attrib_count){
                                                  $('input[type="hidden"][name="variation_id"]').val(v.variation_id);
                                                  $('.variations_form.cart').submit();
                                              }
                                          });

                                      }

                                  });

                              });

                          </script>
                          <style type="text/css">
                              .variations_form.cart a.reset_variations{
                                  display:none;
                              }
                          </style>
                      <?php

                      }else{

                      ?>





                          <script type="text/javascript" language="javascript">

                              jQuery(document).ready(function($){

                                  $('.booking-section > a').click();



                              });

                          </script>

                          <?php

                      }

                          break;

                      }



                  }

                  ?>
                  <?php if(!$is_owner): ?>
                      <div class="col col-md-12 nopadding author-section-top-right ">
                          <a href="<?php echo get_author_posts_url($author->ID); ?>" class="inner" target="_blank" title="<?php echo $author->display_name; ?>">
                              <div class="li-img">
                                  <?php if(get_avatar_url($author->ID)!=''): ?>
                                      <img src="<?php echo get_avatar_url($author->ID); ?>" alt="<?php echo $author->display_name; ?>" />
                                  <?php endif; ?>
                              </div>
                              <div class="li-text">
                                  <h4 class="li-head"><?php echo $author->display_name; ?></h4>
                              </div>
                          </a>
                          <a class="request_custom"> <?php _e('Request', 'booking-works'); ?> <?php echo __('Custom', 'booking-works').' '.__('Order', 'booking-works').'/'.__('Service', 'booking-works'); ?></a>
                          <?php
                          if(function_exists('wp_inbox_message_box')){
                              wp_inbox_message_box($author->ID, true, array('default_text'=>__('Hello '.$author->display_name.',', 'booking-works'), 'button_text'=>__('Send Request', 'booking-works'), 'caption_text'=>__('Hi, Please describe your service request below be as detailed as possible.', 'booking-works')));
                          }
                          ?>
                      </div>
                  <?php endif; ?>

              </div>



          </div>

      </div>


      <div class="wp_ca_price_description">

          <div class="row">
              <div class="col-md-8">
                  <div class="col-md-12 wp_ca_product_price">
                      <strong data-type="product_price"><?php echo __('Price', 'booking-works').'/'.__('Fee', 'booking-works'); ?>:</strong>
                      <div><?php echo get_woocommerce_currency_symbol().$product->get_regular_price(); ?></div>
                  </div>

                  <?php if($is_owner): ?>
                      <div class="col-md-12 wp_ca_sale_switch nopadding">
                          <strong><?php _e('Promotion', 'booking-works'); ?>/<?php _e('Sale', 'booking-works'); ?>:</strong>
                          <div class="onoffswitch">
                              <div class="btn-group btn-toggle">
                                  <button data-switch="on" class="btn btn-xs btn-default <?php echo ($mail_notification?'active btn-warning':''); ?>"><?php _e('Enable', 'booking-works'); ?></button>
                                  <button data-switch="off" class="btn btn-xs btn-default <?php echo (!$mail_notification?'active btn-warning':''); ?>"><?php _e('Disable', 'booking-works'); ?></button>
                              </div>
                          </div>
                      </div>

                  <?php endif; ?>
                  <div class="col-md-12 wp_ca_sale nopadding" data-sale-price="<?php echo $product->get_sale_price(); ?>">
                      <strong data-type="product_sale_price"><?php echo __('Sale Price', 'booking-works').'/'.__('Promotional Fee', 'booking-works'); ?>:</strong>
                      <div><?php echo get_woocommerce_currency_symbol().($product->get_sale_price()>0?$product->get_sale_price():'0'); ?></div>
                  </div>
                  <div class="col-md-12 wp_ca_product_desc">

                      <strong data-type="post_content"><?php _e('Description', 'booking-works'); ?>:</strong>
                      <div class="">
                          <?php the_content(); ?>
                      </div>
                  </div>

                  <?php if($is_owner):?>
                      <div class="col-md-12 wp_ca_product_video">

                          <strong data-type="_product_video"><?php _e('Video URL', 'booking-works'); ?>: <small>(Youtube)</small></strong>
                          <div class="">
                              <?php echo $wc_productdata_options['_product_video'] ?? '#N/A'; ?>
                          </div>
                      </div>
                  <?php endif; ?>
              </div>
          </div>

      </div>


    

<?php 


 
	if($is_owner || ($product->get_type()!='variable' || ($product->get_type()=='variable' && (wpbw_woo_in_cart($product->get_id()) || $rid_valid)))){
	
	if($is_owner)	
	include('wp_ca_edit_fields.php');

?>

        <div class="wp_ca_info_alert">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible show" role="alert">
                        <?php _e('Click Book Now button then select date and hour to book your event.', 'booking-works') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
	

        <div class="row">

            <div class="col-md-12 booking-section">

                <a class="btn btn-primary btn-lg <?php echo ($is_owner && !$is_edit?'edit-mode':''); ?>"><?php echo ($is_owner?__('Go to Calendar', 'booking-works'):__('Book Now', 'booking-works')); ?></a>

                <div class="booking-module">

                    <?php





                    echo wpbw_draw_calendar(date('m'),date('Y'));







                    ?>



                </div>


            </div>


        </div>


     

     <?php 

	}

	?>



      <div class="row" style="background-color: #eeeeee9e; margin: 0 0.3em">


      <div class="col-md-6 more-items-section">

      



		<?php if(!empty($seller_products)){ ?>

        <h3><?php _e('More items by', 'booking-works'); ?> <?php echo $author->display_name; ?></h3>

        <ul class="list img-list">

		<?php foreach($seller_products as $products){ 

				$featured_img_url = get_the_post_thumbnail_url($products->ID,'full'); 	

		?>

                    <li>
                        <a href="<?php echo get_permalink($products->ID); ?>" class="inner">

                            <div class="li-img">
								<?php if($featured_img_url!=''): ?>
                                <img src="<?php echo $featured_img_url; ?>" alt="<?php echo $products->post_title; ?>" />
								<?php endif; ?>
                            </div>

                            <div class="li-text">

                                <h4 class="li-head"><?php echo $products->post_title; ?></h4>

                                <p class="li-sub"><?php echo wp_trim_words($products->post_excerpt, 20); ?></p>

                            </div>

                        </a>

                    </li>

		<?php } ?>                    

                  

      </ul>

		<?php } ?>      

      </div>

      <div class="col-md-6 about-seller-section more-items-section">

          

                  <?php $bio = get_the_author_meta( 'description', $author->ID ); ?>

            <ul class="list img-list ">

                     

                        <li>

                            <a href="<?php echo get_author_posts_url($author->ID); ?>" class="inner">

                                <div class="li-img">
									<?php if(get_avatar_url($author->ID)!=''): ?>
                                    <img src="<?php echo get_avatar_url($author->ID); ?>" alt="<?php echo $author->display_name; ?>" />
									<?php endif; ?>
                                </div>

                                <div class="li-text">

                                    <h4 class="li-head"><?php echo $author->display_name; ?></h4>

                                    <p class="li-sub"><?php echo $bio; ?></p>

                                </div>

                            </a>

                        </li>

			</ul>                              

            

      </div>

      </div>



      <div class="row">
		<div class="col-md-12">

<?php

	

	if(isset($products) && is_object($products) && wpbw_is_user_item($products->ID)){

	

	$rented = array(

		'posts_per_page' => -1,

		'post_type'        => 'shop_order',

		'author'	   => $author->ID,

		'post_status'      => 'any',

		'orderby'          => 'date',

		'order'            => 'DESC',		

	);

	//pree($rented);

	$results = get_posts($rented);

	

	//pree($results);

	

	if(!empty($results)){

		foreach($results as $orders){

			

			$existing_booking = wpbw_get_actions('calendars_management', array('ca_type'=>'wc_product', 'ca_order_attached'=>$orders->ID, 'ca_blog_id'=>get_current_blog_id()), true);	

			

			if(empty($existing_booking))

			continue;

			

			//pree($existing_booking);

			$booked = current($existing_booking);

			

			

			if(empty($booked))

			continue;

			

						

			$booked_date = $booked->ca_slot_day.'-'.$booked->ca_slot_month.'-'.$booked->ca_slot_year;

			

			if($booked->ca_start_date>0 && $booked->ca_end_date==0){

				$booked_date = wpbw_get_end_dt($orders->ID);

			}else{

				continue;

			}

			

			$buyer = get_user_by('id', $booked->ca_user_id);

			//$featured_img_url = get_the_post_thumbnail_url($booked->ca_object_id,'full'); 	

			//pree($buyer);

			$featured_img_url = $images[0];
			
			if($buyer->ID!=get_current_user_id())
			return;

			

?>

					<div class="wp-ca-offer-counter type-end col-md-12">

                        <h2><?php echo __('This order is valid till', 'booking-works').' '.$booked_date; ?></h2>

                        

						<ul class="wp-ca-offer-item-<?php echo $orders->ID; ?> col-md-12" data-id="<?php echo $orders->ID; ?>" data-dt="<?php echo date('M d, Y H:i:s', strtotime($booked_date));//Jan 5, 2018 15:37:25 ?>">

                        	<li class="dd col-md-2 btn btn-info">00</li>

							<li class="sep col-md-1">:</li>

                        	<li class="hh col-md-2 btn btn-info">00</li>

							<li class="sep col-md-1">:</li>

                        	<li class="mm col-md-2 btn btn-info">00</li>

							<li class="sep col-md-1">:</li>

                        	<li class="ss col-md-2 btn btn-info">00</li>                            

                        </ul>

                        

                        <div class="col-md-12" style="text-align:center;">

                        <p class="col-md-12"><?php echo wpbw_messages('end_1'); ?></p>

                        <a data-type="end" data-id="<?php echo base64_encode($orders->ID); ?>" data-msg="<?php echo wpbw_messages('end_2'); ?>" class="btn btn-danger btn-lg wp-ca-confirm-end"><?php _e('Click here to confirm'); ?></a>

                        </div>

                        

                        

                        <div class="col-md-12 wp_ca_offer_counter_bottom">

                            <div class="col-md-6">

                                <img src="<?php echo $featured_img_url; ?>" alt="<?php echo the_title(); ?>" />

                                <h6><?php echo the_title(); ?></h6>

                            </div>                            

                            <div class="col-md-6">

                                <img src="<?php echo get_avatar_url($buyer->ID); ?>" alt="<?php echo $buyer->display_name; ?>" />

                                <h6><a href="<?php echo get_author_posts_url($booked->ca_user_id); ?>" target="_blank"><?php echo $buyer->display_name; ?></a></h6>

                            </div>

                        

                            

                        </div>

                        

                        </div>

<?php			

			

		}		

	}

	

	}

?>        

        

        </div>
      </div>
    

  </div>

</div>