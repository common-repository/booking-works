<?php

    global $wp_ca_add_on_total, $wp_ca_add_on_html;

    $wp_ca_add_on_total = 0;
    $wp_ca_add_on_html = '';


?>

<style type="text/css">

.ticket-section .coupon {

    border: 3px dashed #bcbcbc;

    border-radius: 10px;

    font-family: "HelveticaNeue-Light", "Helvetica Neue Light", 

    "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 

    font-weight: 300;
	float:left;
	width:100%;

}

.ticket-body > h3{
	text-transform:capitalize;
}

.ticket-section .coupon #head {

    border-top-left-radius: 10px;

    border-top-right-radius: 10px;

    min-height: 56px;

}

.ticket-section .coupon #head span small{

	width:100%;

	text-align:right;

	font-size:10px;

	display:block;

}

.ticket-section .coupon #head span small strong{

	text-decoration:underline;

}

.ticket-section .coupon #footer {

    border-bottom-left-radius: 10px;

    border-bottom-right-radius: 10px;

}



.ticket-section #title .visible-xs {

    font-size: 12px;

}



.ticket-section .coupon #title img {

    font-size: 30px;

    height: 30px;

    margin-top: 5px;

	display:inline-block;

}







.ticket-section .coupon #title span {

    float: right;

    margin-top: 5px;

    font-weight: 700;

    text-transform: uppercase;

}



.ticket-section .coupon-img {

    width: 100%;

    margin-bottom: 15px;

    padding: 0;

}



.ticket-section .items {

    margin: 15px 0;

	list-style:none;

}



.ticket-section .usd, .ticket-section .cents {

    font-size: 20px;

}



.ticket-section .number {

    font-size: 25px;

    font-weight: 600;

}



.ticket-section sup {

    top: -15px;

}



.ticket-section #business-info ul {

    margin: 0;

    padding: 0;

    list-style-type: none;

    text-align: center;

}



.ticket-section #business-info ul li { 

    display: inline;

    text-align: center;

}



.ticket-section #business-info ul li span {

    text-decoration: none;

    padding: .2em 1em;

}



.ticket-section #business-info ul li span i {

    padding-right: 5px;

}



.ticket-section .disclosure {

    padding-top: 15px;

    font-size: 11px;

    color: #bcbcbc;

    text-align: left;

}

.ticket-section .disclosure strong{

	font-size:16px;

}



.ticket-section .terms-section .term-conds{

	font-size:14px;

}

.ticket-section .coupon-code {

    color: #333333;

    font-size: 11px;

}

.ticket-section .exp{
	float:left;
	width:100%;
}

.ticket-section .exp,

.ticket-section .exp a {

    color: #f34235;

}



.ticket-section .print,
.ticket-section .print a,
.ticket-section .print-contract,
.ticket-section .print-contract a,
.ticket-section .download,
.ticket-section .download a{

    font-size: 12px;

    float: right;

	cursor:pointer;

}
.ticket-section .print a{
	padding-right:0;
}

.ticket-section .about-seller-section{

}
.ticket-section .about-seller-section > p,
.ticket-section .add-remarks > p{
	word-wrap: break-word;
}

.ticket-section .about-seller-section h2{

	font-weight:bold;

}

.ticket-section .about-seller-section ul{

	list-style:none;

	margin: 0 0 20px 0;

	

}

.ticket-section .about-seller-section ul li{

}
.ticket-section .about-seller-section ul li a p{
	color:#000;
}
.ticket-section .about-seller-section ul li a:hover{
	text-decoration:none;

}






.ticket-section .about-seller-section .li-img {

  display: table-cell;

  vertical-align: top;

  width: 20%;

  padding-right: 1em;

  

}

.ticket-section .about-seller-section .li-img img {

  display: block;

  height: 70px;

  width:auto;

  max-width:none;

}

.ticket-section .about-seller-section .li-text {

  display: table-cell;

  vertical-align: top;

  width: 80%;

}



.ticket-section .item-section{

}

.ticket-section .item-section h2{

	font-weight:bold;

	font-size:14px;

}

.wp-ca-offer-counter{
    clear: both;
}

.ticket-section .item-section h2 span{

	font-weight:normal;

}
<?php if(!isset($_GET['updated'])): ?>
.checkout.woocommerce-checkout {
  display: none;
}
<?php else: ?>
.container.ticket-section{
	display: none;
}
<?php endif; ?>

.wp_ca_printing .ticket-section .coupon-img{

	margin: 0 auto;

	width:auto;

}
.ticket-section .coupon .panel-footer{
	float:left;
	width:100%;
}
.wp_ca_printing header, 

.wp_ca_printing footer, 

.wp_ca_printing .woocommerce-breadcrumb, 

.wp_ca_printing #wpadminbar, 

.wp_ca_printing .ticket-section .panel-footer,

.wp_ca_printing .page-title,

.wp_ca_printing .page-noprint{

	display:none;

}
.wp_ca_printing .ticket-section .about-seller-section h2{
	visibility:hidden;
}

.wp_ca_printing .page-checkout{
	margin:0;
}
.wp_ca_printing .bw_document_wrapper{
	height:auto;
}
html.wp_ca_print{
	background-color:#fff;
}
<?php if(empty($_POST)): ?>

.woocommerce-messages{

	display:none;

}

<?php endif; ?>

.woocommerce-additional-fields{

	display:none;

}

.container{
    width: 100%;
}


.ticket-skeleton .required {
	border-bottom: 1px solid red !important;
}
.ticket-section .item-section ul.items, 
div.ticket-section div.row ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.wp_ca_ticket_contact{
	cursor:pointer;
}
.ticket-section .wp_inbox_message_box_toggle{
	display:none;
}

@media print {
    body * {
        visibility: hidden;
    }


    
    #section-to-print , #section-to-print * {
        visibility: visible;
    }

    .wp-ca-offer-counter{
        visibility: hidden !important;
    }






    #section-to-print {
        position: absolute;
        left: 0;
        top: 0;
    }

    a[href]:after {
        content: none !important;
    }
}

.entry-header{
    padding-left: 14px;
}

@media screen and (max-width: 500px) {

.ticket-section .coupon #title img {

        height: 15px;

    }

}

</style>

<script type="text/javascript" language="javascript">

jQuery(document).ready(function($){

	

	setTimeout(function(){

		if($('input[name="agree"]').length>0){

			var ck = $('input[name="agree"]').is(':checked');



			$('.woocommerce-checkout input[name="woocommerce_checkout_place_order"]').prop('disabled', !ck);	
			
			

		}

	}, 3000);

	$('.ticket-section .wp_ca_ticket_contact').on('click', function(){
		$('.ticket-section .wp_inbox_message_box_toggle').click();
		$(this).hide();
	});

	

	$('input[name="agree"]').click(function(){
		
		var proceed = true;
		var empty_field;
		
		if($('.ticket-section :input.required').length>0){
			$.each($('.ticket-section :input.required'), function(i, v){
				
				if(proceed && $.trim($(this).val())==''){
					
					proceed = false;
					
					empty_field = $(this);
				}
			});
		}
		
		if(!proceed){
			
			alert('<?php _e('Please fill the required fields.', 'booking-works'); ?>');
			$(this).prop('checked', false);
			
			$('html, body').animate({ scrollTop: $(".bw_document_wrapper").offset().top }, 2000);		
			setTimeout(function(){	
				$(empty_field).focus();
			}, 1000);
			
		}else{
			
	
			if($(this).is(':checked')){
	
				$('.container.ticket-section').hide();
	
				$('.checkout.woocommerce-checkout').fadeIn();
	
				$('.woocommerce-checkout input[name="woocommerce_checkout_place_order"]').prop('disabled', false);	
	
				$('html, body').animate({
	
					scrollTop: $(".checkout.woocommerce-checkout").offset().top
	
				}, 2000);		
					
				
				var data = {
		
						'action':'wpbw_confirm_contract',
						'ticket_number':'<?php echo $ticket_number; ?>'
		
				};			
				jQuery.each(jQuery('.lb-contract :input'), function(i, v){ 
					var id = jQuery(this).data('id');
					data[id] = {};
					if(id!='undefined'){
						data[id]['value'] = jQuery(this).val();
						data[id]['text'] = jQuery(this).text();
					}
				});
		
				$.post(wp_ca.ajaxurl, data, function(resp){
		
					resp = $.parseJSON(resp);
					//alert(resp.msg);
					if(resp.msg="redirect"){
						alert("<?php _e('Updating your order, please wait.', 'booking-works'); ?>");
						setTimeout(function(){
							window.location.href = wp_ca.checkout_url+'?updated';
						}, 1000);
					}
		
				});			
							
		
			}else{
	
				$('.container.ticket-section').fadeIn();
	
				$('.checkout.woocommerce-checkout').hide();		
	
				$('.woocommerce-checkout input[name="woocommerce_checkout_place_order"]').prop('disabled', true);	
	
			}
			
		}

	});

	

	$('.ticket-section .print-contract a').click(function(){

		

		$('body').addClass('wp_ca_printing contract');

		$('.coupon-img').hide();
		
		$('.row.row-main > div').attr('class', 'col col-md-10');
		
		$('.ticket-section .ticket-skeleton').attr('class', 'col col-md-12');

		$('.bw_document_wrapper').removeClass('page-noprint');

		$('#content > div').addClass('pull-left');

		$('html').addClass('wp_ca_print');
		$('.wp_ca_printing .ticket-section .panel-footer').hide();
        $('#section-to-print .wp-ca-offer-counter').hide();



        window.print();

	});	

	$('.ticket-section .print a').click(function(){

		

		$('body').addClass('wp_ca_printing');

		$('.row.row-main > div').attr('class', 'col col-md-8');

		$('.ticket-section .ticket-skeleton').attr('class', 'col col-md-12');

		$('#content > div').addClass('pull-left');

		$('html').addClass('wp_ca_print');
        $('.wp_ca_printing .ticket-section .panel-footer').hide();
        $('#section-to-print .wp-ca-offer-counter').hide();


        window.print();

	});
	
	<?php 
	$readonly = ($ca_order_attached); ?>
	setTimeout(function(){
	<?php 
		if(!empty($contract_fields)){
			foreach($contract_fields as $field=>$values){
				
				if(is_array($values) && !empty($values) && count($values)==2){
	?>
				if($(':input[data-id^="<?php echo $field; ?>"]').length>0){
	<?php
					$val = ($values['text']!=''?$values['text']:$values['value']);
					$val = addslashes(preg_replace("/[\r\n]+/",' ',(preg_replace('/\s\s+/', ' ', $val))));
					if($values['text']!=''){
	?>
						//$(':input[data-id="<?php //echo $field; ?>"]').text('<?php echo $val; ?>').attr({'readonly':'readonly', 'disabled':'disabled'});
						$('<div class="col col-md-12"><?php echo $val; ?></div>').insertAfter($(':input[data-id="<?php echo $field; ?>"]'));
						$(':input[data-id="<?php echo $field; ?>"]').remove();
						
	<?php	
					}else{
												
						if(is_array($values) && !isset($values['text'])){
							
	?>
							$('<div class="col col-md-12"><?php echo $val; ?></div>').insertAfter($(':input[data-id="<?php echo $field; ?>"]'));
							$(':input[data-id="<?php echo $field; ?>"]').remove();
	<?php						
						}else{						
						
							if($readonly){
	?>
								$(':input[data-id="<?php echo $field; ?>"]').val('<?php echo $val; ?>').attr({'readonly':'readonly', 'disabled':'disabled'});
						
	<?php				
							}else{
	?>
								$(':input[data-id="<?php echo $field; ?>"]').val('<?php echo $val; ?>');
						
	<?php						
							}
						
						}
					}
	?>
					
				}
	<?php				
				}
			}
		}
	?>
	
	}, 2000);

});

</script>

<div class="container ticket-section" id="section-to-print">


	<div class="row">

        <div class="col-md-12 ticket-skeleton">

            <div class="panel panel-default coupon">

              <div class="panel-heading" id="head">

                <div class="panel-title" id="title">

                    <img src="<?php echo $wp_ca_logo; ?>">

                    <?php if($ticket_number): ?>

                    <span class="hidden-xs"><?php _e('Booking Ticket', 'booking-works'); ?># <?php echo $ticket_number; ?><br /><small><strong><?php _e('From', 'booking-works'); ?></strong> <?php echo $from_date; ?> <?php if($to_date){ ?><strong><?php _e('To', 'booking-works'); ?></strong> <?php echo $to_date; ?><?php } ?></small></span>

                    <span class="visible-xs"><?php _e('Booking Ticket', 'booking-works'); ?># <?php echo $ticket_number; ?><br /><small><strong><?php _e('From', 'booking-works'); ?></strong> <?php echo $from_date; ?> <?php if($to_date){ ?><strong><?php _e('To', 'booking-works'); ?></strong> <?php echo $to_date; ?><?php } ?></small></span>

                    <?php endif; ?>

                </div>

              </div>

              <div class="panel-body ticket-body">


                  <div class="row">
                      <div class="col-xs-12">
                          <h2><?php echo $title; ?></h2>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-xs-4">
                          <img src="<?php echo $image; ?>" class="coupon-img img-rounded">

                      </div>

                      <div class="col-xs-8">
                          <p><?php echo $description; ?></p>

                      </div>
                  </div>



                <div class="row">

                    <div class="col-md-12 about-seller-section">


                        <div class="h3"><?php _e('Details', 'booking-works'); ?>:</div>

                        <ul class="list img-list">



                            <li>

                                <a href="<?php echo get_author_posts_url($author->ID); ?>" class="inner" target="_blank" title="<?php echo $author->display_name; ?>">

                                    <div class="li-img">
                                        <?php if(get_avatar_url($author->ID)!=''): ?>
                                            <img src="<?php echo get_avatar_url($author->ID); ?>" alt="<?php echo $author->display_name; ?>" />
                                        <?php endif; ?>
                                    </div>

                                    <div class="li-text">

                                        <h4 class="li-head"><?php echo $author->display_name; ?></h4>

                                        <p class="li-sub"><?php echo $author_bio; ?></p>

                                    </div>

                                </a>

                            </li>

                        </ul>



                    </div>


                </div>


                  <div class="row">

                      <div class="col-md-12">
                          <div class="h3"><?php _e('Booking Details', 'booking-works'); ?>:</div>
                      </div>

                  </div>


                  <div class="row" style="margin-bottom: 15px;">

                      <div class="col-md-12">
                          <div class="h4"><?php _e('Item', 'booking-works'); ?>: <span><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span></div>
                      </div>

                  </div>


              <div class="row">
                <div class="col-xs-8 col-sm-9 col-md-10 item-section">


                    <ul class="items">


                        <?php /*

                        <li><h2><?php _e('Model', 'booking-works'); ?>: <span>2016</span></h2></li>

                        <li><h2><?php _e('Delivery Type', 'booking-works'); ?>: <span>Pickup</span></h2></li>

                        <li><h2><?php _e('Delivery Address', 'booking-works'); ?>: <span>N/A</span></h2></li>

                        <li><h2><?php _e('Pickup Location'); ?>: <span>4299 Express Lane, Sarasota, FL 34238 USA</span></h2></li>

                        <li><h2><?php _e('Date'); ?>: <span><?php echo date('d F, Y'); ?></span></h2></li>

                        <li><?php _e('Parts'); ?>: Add up to 5 quarts of motor oil (per specification)</li>

                        <li><?php _e('Included'); ?>: Complimentary multi-point inspection</li>

                        <li>Drain and refill trnasmission fluid</li>

                        <li>System inspection</li>

                        */ ?>

                        

                        

                    </ul>


					<?php wpbw_booking_details($existing_booking_param); ?>
                </div>


                  <div class="col-xs-4 col-sm-3 col-md-2">
                  <div class="offer">

<!--                   		<strong class="cents"><sup>--><?php //_e('Bill'); ?><!--</sup></strong>-->

                        <span class="usd"><sup></sup></span>

                        <span class="number"><?php echo get_woocommerce_currency_symbol(); ?><?php echo $price+$wp_ca_add_on_total; ?></span>

                        

                    </div>

                </div>

              </div>

                  <?php if($wp_ca_add_on_html): ?>
              <div class="row">
                  <div class="col-md-12">
                      <h3><?php _e('Add-on', 'booking-works'); ?>:</h3>
                  </div>

                  <style>
                      .add_on_single{
                          margin-bottom: 10px;

                      }
                  </style>

                  <div class="row" >
                      <div class="col-md-12">
                          <ol>
                                <?php echo $wp_ca_add_on_html; ?>
                          </ol>
                      </div>
                  </div>
              </div>

                  <?php endif; ?>

				<?php if(trim($ca_user_remarks)!=''): ?>
                <div class="row">
                <div class="col-md-12 add-remarks">

                	<div class="h3"><?php _e('Added Remarks', 'booking-works'); ?>:</div>

                    <p><?php echo $ca_user_remarks; ?></p>

                </div>
                </div>
				<?php endif; ?>
                

                
                
                 
				<?php 
				
				//wpbw_updates_required('Please complete the following information before filling the contract, click below.');
				
				echo apply_filters('bw_ticket_before_terms', '', $item); ?>
                
                <?php if(!$ca_order_attached): ?>

                <div class="row">
                
                <div class="col-lg-12 terms-section page-noprint">


                   

                    <p class="disclosure"><strong><?php _e('Terms and Conditions', 'booking-works'); ?>:</strong><br /><?php echo wpbw_options('ticket_terms'); ?></p>

                    

                    

                    <div class="checkbox term-conds">

                        <label>

                            <input name="agree" value="agree" type="checkbox"> <?php _e("I've read the terms and conditions"); ?>

                        </label>

                    </div>

         

              

                    

                </div>

                </div>
                

                <?php endif; ?>

              </div>

              <div class="panel-footer">

                <div class="coupon-code">

                	<?php if($ticket_number): ?>

                    <?php _e('Tracking', 'booking-works'); ?># <?php echo $ticket_number; ?>

                    <?php endif; ?>

                    <span class="print">
                        <a class="btn btn-link"><i class="fa fa-lg fa-print"></i> <?php _e('Print Ticket', 'booking-works'); ?></a>
                    </span>
                    <?php if($ca_order_attached): ?>
                    &nbsp;
                    <span class="print-contract">
	                    <a class="btn btn-link"><i class="fa fa-lg fa-print"></i> <?php _e('Print Contract', 'booking-works'); ?></a>
                    </span>
					<?php endif; ?>

                    <span class="download">
                        <a class="btn btn-link"><i class="fa fa-lg fa-arrow-circle-down"></i> <?php _e('Download PDF', 'booking-works'); ?></a>
                    </span>


                </div>

                <div class="exp"><a href="<?php echo $help_url; ?>" target="_blank"><?php _e('Need help?', 'booking-works'); ?></a>&nbsp;
                <?php
				$cname = __('Seller', 'booking-works');
				$contact_id = $author->ID;
				//pree($author->ID);//pree(get_current_user_id());
				if($author->ID==get_current_user_id()){
					$cname = __('Buyer', 'booking-works');
					$contact_id = $ca_user_id;
				}
				
				if($contact_id && function_exists('wp_order_message_box')){
				?>
                <a class="pull-right wp_ca_ticket_contact"><?php echo __('Contact', 'booking-works').' '. $cname; ?></a>
                <?php
					wp_order_message_box($contact_id, $products);
				}
				?>
               </div>



              </div>

            </div>
    <?php
	wpbw_action_woocommerce_order_details_after_customer_details($ca_order_attached);
	?>
    	</div>

    </div>

    



    



</div>