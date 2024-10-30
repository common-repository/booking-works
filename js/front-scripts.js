// JavaScript Document

var current_url = location.protocol + '//' + location.host + location.pathname;

function wp_ca_hours_refresh($){

	var am_count = $('.wp-ca-am-hours button.active').length;

	var pm_count = $('.wp-ca-pm-hours button.active').length;

	$('.wp-ca-am span').html(am_count);

	$('.wp-ca-pm span').html(pm_count);
	
	wp_ca_update_slot_hours_numbers($);
		
}

function wp_ca_is_owner(){
	return jQuery('.rentable_view').hasClass('owner_view');
}


jQuery(document).ready(function($){
	if($('form.acf-form').length>0){
		var url = new URL(document.location.href);
		var id = url.searchParams.get("id");
		$('form.acf-form').prepend('<input type="hidden" name="query_id" value="'+id+'" />');
	}
	
	$('body').on('keydown', '.number', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
	
	$('.wp_ca_sale_switch .btn-toggle').click(function() {
		$(this).find('.btn').toggleClass('active');  
		
		if ($(this).find('.btn-primary').length>0) {
			$(this).find('.btn').toggleClass('btn-primary');
		}
		if ($(this).find('.btn-danger').length>0) {
			$(this).find('.btn').toggleClass('btn-danger');
		}
		if ($(this).find('.btn-success').length>0) {
			$(this).find('.btn').toggleClass('btn-success');
		}
		if ($(this).find('.btn-info').length>0) {
			$(this).find('.btn').toggleClass('btn-info');
		}
		
		if ($(this).find('.btn-warning').length>0) {
			$(this).find('.btn').toggleClass('btn-warning');
		}		

		switch($(this).find('.active').data('switch')){
			case 'on':	
				$('.wp_ca_sale').css('visibility', 'visible');
			break;
			case 'off':
				$('.wp_ca_sale').css('visibility', 'hidden');
			break;
			
		}
	   
		var data = {
			'action': 'wp_ca_sale_status',
			'switch': $(this).find('.active').data('switch')
		};



		/*$.post(wp_inbox.ajaxurl, data, function(response) {
			
		});*/
		
	});		
	
	
	setTimeout(function(){
		if($('.woocommerce-view-order .woocommerce-customer-details').length>0){
			$('.woocommerce-view-order .woocommerce-customer-details').remove();
		}
		if($('.wp_ca_sale').length>0){
			if($('.wp_ca_sale').data('sale-price')*1>0){
				if($('.wp_ca_sale_switch').length>0){
					$('.wp_ca_sale_switch button[data-switch="on"]').click();
				}
				$('.wp_ca_sale').css('visibility', 'visible');
			}
		}
	}, 1000);
	
	$('.author-section-top-right .request_custom').on('click', function(){
		$(this).hide();
		$('.author-section-top-right .wp_inbox_message_box_toggle_div').toggle();
		$('.wp_inbox_message_box_toggle').hide();
	});

	$('div.rentable_view.owner_view ol.carousel-indicators li > a').on('click', function(){
		var c = confirm('Do you want to delete this item?');
		if(c && wp_ca.wp_ca_edit){
			var d_url = current_url+'?edit';
			document.location.href = d_url+'&delete='+$(this).parent().find('img').data('id');
		}
	});
	
	$('body').on('click', '.wp_ca_item_edit_section.selecting .wp_ca_hours_selection .down-to', function(){
		$('.wp_ca_item_edit_section.selecting').removeClass('selecting');
	});
	

	var wp_ca_product_types_fileds = '<input type="hidden" id="wp_ca_product_type" name="wp_ca_product_type" value="renting" /><input type="hidden" id="wp_ca_product_sub_type" name="wp_ca_product_sub_type" value="rental" />';
	
	$('.wp_ca_product_types_div').on('click', 'btn', function(){
		
	});

	setTimeout(function(){

		if($('.owner_view .wp_ca_product_types_div').length>0){
			$('.owner_view .wp_ca_product_types_div').parent().append(wp_ca_product_types_fileds);
			$('.wp_ca_product_types button[data-type="'+wp_ca.wp_ca_wc_product_details.wp_ca_product_type+'"]').click();
			$('.wp_ca_product_sub_types button[data-type="'+wp_ca.wp_ca_wc_product_details.wp_ca_product_sub_type+'"]').click();
			
			var prod_type = '';
			$.each($('.owner_view .wp_ca_product_types_div .btn.active'), function(){

				prod_type += $(this).html()+' > ';
			});
			
			$('<div class="prod_type">'+prod_type+'</div>').insertAfter($('.owner_view .wp_ca_product_types_div > strong'));
			
			

		}
	}, 500);
	
	setTimeout(function(){

	
	
		if($('input[name="return"]').length>0 && $('input[name="return"]').val().search('update-')>0){
			$('<input type="hidden" name="wp_ca_redirect_after" value="edit-schedule" />').insertBefore($('input[name="return"]'));
			if($('#poststuff :input:visible').length<=1){
				$('#poststuff input[type="submit"]').click();
			}
		}
		

		var obj = $('#ns-container-add-product-frontend');

		

		if(obj.length>0){

			

			

			$.each($('#ns-container-add-product-frontend :input'), function(){

				

				$(this).closest( "div" ).addClass('wp-ca-'+$(this).attr('id'));

					

			});

			

			if(wp_ca.wp_ca_product_type!=''){

				$('#ns-container-add-product-frontend').addClass('wp-ca-'+wp_ca_product_type);

				$('.wp-ca-ns-regular-price label').html('Rent amount ('+wp_ca.wp_ca_wc_product_details.currency+') per hour');

			}



			obj.show();

			

			obj.find('form').prepend(wp_ca_product_types_fileds);

		

			var e = 1;

			$.each($('.ns-pointer'), function(){

				var obj = $(this);

				setTimeout(function(){

					obj.delay(300).click();

				}, 500*e);

				e++;

			});

		}

	

	}, 1000);

	

	

	$('body').on('click', '.wp_ca_list_user_items a', function(){

		var obj = $(this).parent(); 

		var id = obj.data('id');

		if(id!='' && id>0){

			

			var action = $(this).hasClass('delete')?'delete':'';

			

			if(action=='')

			action = $(this).hasClass('view')?'view':'';			

			if(action=='')

			action = $(this).hasClass('edit_s')?'edit_schedule':'';			

			if(action=='')

			action = $(this).hasClass('edit_t')?'edit_terms':'';			
			
			if(action=='')

			action = $(this).hasClass('edit_a')?'edit_all':'';		
			
			if(action=='')

			action = $(this).hasClass('publish_s')?'publish':'';					
			

			switch(action){

				case 'delete':

				

					var ask = confirm("Are you sure, you want to delete this item?");

					

					if(ask){

						var data = {

								'action':'wp_ca_delete_user_item',

								'postid':id

						};			

						$.post(wp_ca.ajaxurl, data, function(resp){

							resp = $.parseJSON(resp);

							

							if(resp.msg==true)

							obj.closest('tr').remove();

							else

							alert("Already deleted.");

							

						});

					}

					

				break;

				

				case 'view':

				

					window.location.href = $(this).parent().data('url');

					

				break;
				

				case 'publish':

				

					window.location.href = $(this).parent().data('publish');

					

				break;				

				

				case 'edit_schedule':

				

					window.location.href = $(this).parent().data('edit-url');

					

				break;				

				case 'edit_terms':

				

					window.location.href = $(this).parent().data('edit-terms').replace('renting', $(this).data('type'));

					

				break;		
				
				case 'edit_all':

				

					window.location.href = '/edit/?id='+$(this).parent().data('id');

					

				break;										

			}

		}

	});


	var wp_ca_product_stypes = 'Product';
	
	$('body').on('click', '.wp_ca_product_sub_types button', function(){
		$(this).parent().find('.active').removeClass('active');
		var all_caps = '.ns-center h2 span, .wp-ca-ns-product-name label, #ns-image-container div h2, .wp-ca-ns-video label, #ns-post-content div h2';
		all_caps = all_caps.split(',');
		var type = $('.wp_ca_product_types button.active').data('type');
		
		
		var rtype = ($(all_caps[0]).length>0?$(all_caps[0]).html():'');
		rtype = (rtype!=''?rtype.split(' '):'');
		rtype = (rtype!=''?rtype[0]:'');
		
		switch(rtype){

			case 'Rental':
				rtype = 'Rental Product';
			break;

			case 'On':
				rtype = 'On Demand Service';				 
			break;

			case 'Vacation':
				rtype = 'Vacation | Adventure | Tours | Games';
			break;
			
		}

		var stype = $(this).data('type');
		
		switch(type){

			default:
				
				wp_ca_product_stypes = 'Product';
				stype = '';

			break;

			case 'renting':
				
				
			
				switch(stype){
		
					default:
					case 'rental':
						wp_ca_product_stypes = 'Rental Product';
					break;
		
					case 'on_demand_service':
						wp_ca_product_stypes = 'On Demand Service';						
					break;

					case 'vacation_tour':
						wp_ca_product_stypes = 'Vacation | Adventure | Tours | Games';						
					break;
					
				}			
				
			break;
			
		}
		
		$.each(all_caps, function(i, s){
			if($(s).length>0){
				$(s).html($(s).html().replace(rtype, wp_ca_product_stypes).replace('Data', 'Information'));
			}
		});			
		
		$('#wp_ca_product_sub_type').val(stype);
		$(this).addClass('active');
		
		if($('#ns-product-data-inner-container').length>0){		
			$('#ns-product-data-inner-container').show();
			$('#ns-wp-post-content-div, div[id^="ns-image-container"]').addClass('ns-general').show();		
		}

	});
	
	setTimeout(function(){
		if($('.wp_ca_product_types').length>0){
			var desc = $('#ns-post-content div h2');
			desc.html('Product Description');
			
			$('.wp_ca_product_types button[data-type="renting"]').click();
		}
	}, 1000);
	
	setTimeout(function(){
		if(
				$('#wc-deposits-options-form').length>0 
			&& 
				wp_ca.wp_ca_wc_product_details.wp_ca_product_sub_type=='on_demand_service' 
			&& 
				wp_ca.wp_ca_wc_product_details.wp_ca_product_type=='renting'
			&&
				wp_ca.wp_ca_in_cart!='true'
		){
			//$('#pay-deposit-label').html('Down Payment');
			$('.wp_ca_product_price').append($('form.cart'));
			$('form.cart').show();
			
		}
	}, 3000);
		
	
	
	$('body').on('click', '.wp_ca_product_types button', function(){

		
		$('.wp_ca_product_sub_types').css('visibility', 'hidden');
		
		var type = $(this).data('type');

		

		var obj = $('#ns-container-add-product-frontend');		

		$(this).parent().find('.active').removeClass('active');

		$(this).addClass('active');

		$('#wp_ca_product_type, #wp_ca_product_sub_type').val('');

		

		switch(type){



			default:

				obj.removeClass('wp-ca-renting');

			break;



			case 'renting':

				obj.addClass('wp-ca-renting');

				$('#wp_ca_product_type').val(type);
				
				$('.wp_ca_product_sub_types').css('visibility', 'visible');

			break;



		}

		$('.wp_ca_product_sub_types button.active').click();

		

	});



	$('.wp_ca_item_edit_section .wp_ca_hours_selection').on('click', 'button', function(){
		
		var refresh_hours = true;

		$(this).toggleClass('active').focusout();
	
		if(wp_ca.wp_ca_multiple=='true'){
			
			if(wp_ca_is_owner()){
				
				
				
			}else{
				
				
				
				if($('.wp_ca_cslots > div.row').length>0 && $('.wp_ca_cslots > div.current').length==0){
					
					$('.wp_ca_cslots > div.row').eq(0).click();					
					
				}
				
				var slot = $('.wp_ca_cslots > div.current').data('slot');
				
				var hour = $(this).data('val');		
				
				var hstatus = false;	
				
				if(typeof wp_ca_slot_hours[slot][hour]=="undefined"){
					
					hstatus = true;
					
				}else{			
					
					hstatus = !wp_ca_slot_hours[slot][hour];
					
				}
				
				
				
				if(!hstatus){
					
					delete wp_ca_slot_hours[slot][hour];
					
				}else{
					
					//alert(wp_ca_limit_allowed());
					
					if(wp_ca_limit_allowed()){
					
						wp_ca_slot_hours[slot][hour] = hstatus;
						
					}else{
						
						refresh_hours = false;
						
					}
					
				}
				
				
			}
		}
		
		if(refresh_hours)
		wp_ca_hours_refresh(jQuery);
		

	});

	function wp_ca_limit_allowed(){
		
		var ret = false;
		var h = 0;
		
		$.each(wp_ca_slot_hours, function(slot, sdata){
			
			$.each(sdata, function(hour, status){
				
				if(status)
				h++;
				
			});			
			
		});
		
		switch(wp_ca.wp_ca_wc_product_details.wp_ca_duration_type){
			
			case 'hour':
			case 'hours':
				
				if(h<wp_ca.wp_ca_wc_product_details.wp_ca_number_of){
					
					ret = true;
					
				}else{
					
					$('<div class="alert alert-danger"><strong>'+wp_ca.wp_ca_limit_alerts.h.replace('%d', wp_ca.wp_ca_wc_product_details.wp_ca_number_of)+'</div>').insertAfter($('.wp_ca_hours_selection > h4'));
					
					setTimeout(function(){
						$('.wp_ca_hours_selection > .alert.alert-danger').remove();
						
					}, 5000);
					
					
				}
				
			break;			
			
		}
		
		return ret;
		
	}

	$('.wp_ca_item_edit_section .wp-ca-timezone, .wp_ca_hours_selection .wp_ca_duration_section').on('click', '.dropdown-toggle', function(){
		$(this).parent().find('ul.dropdown-menu').toggle();
		
	});
	
	
	$('.wp_ca_item_edit_section .wp-ca-timezone').on('click', 'ul li a', function(){

		

		var tz = $(this).data('val');

		

		$('input[name="wp-ca-timezone"]').val(tz);

		

		$('.wp-ca-timezone > button').html(tz+'<span class="caret"></span>');

		$('.wp_ca_item_edit_section .wp-ca-timezone ul.dropdown-menu').hide();

	});

	

	

	$('body').on('click', '.user_view .wp_ca_item_edit_section .submit', function(){

		var hours_selected = [];

		$.each($('.wp-ca-am-pm button.active'), function(){

			hours_selected.push($(this).data('val'));

		});

		

		var tz = $('input[name="wp-ca-timezone"]');

		var timezone = tz.val();

		

		

		if(timezone==''){

			var ask = confirm(tz.data('ask'));

			if(!ask)

			return;

		}

		

	

		var data = {

				'action':'wp_ca_hours_selection',

				'hours_selected':hours_selected,

				'postid':$('input[name="postid"]').val(),

				'timezone':timezone

				

		};			

		$.post(wp_ca.ajaxurl, data, function(resp){

			resp = $.parseJSON(resp);

			

			//if(resp.msg==true)

			

			

		});



		

	});

	

	

	$('body').on('click', '.login_proceed', function(){

		window.location.href=wp_ca.wp_ca_login_url

	});

	

	$('.booking-section > a').not('.edit-mode').on('click', function(){

		$('.booking-section > .booking-module').fadeIn();

		$(this).hide();

	});

	

	$('.booking-section > a.edit-mode').on('click', function(){

		document.location.href = '?edit';

	});

		

	

	$('body').on('click', '.wp-ca-confirm-start, .wp-ca-confirm-end', function(){

		var msg = $(this).data('msg');

		var ask = confirm(msg);

		if(!ask)
		return;

		var type = $(this).data('type');

		//alert(type+'-'+$(this).data('id'));
		
		var data = {

				'action':'wp_ca_confirm_'+type,

				'oid':$(this).data('id')

		};			

		$.post(wp_ca.ajaxurl, data, function(resp){

			resp = $.parseJSON(resp);

			if(resp.msg==true){

				document.location.reload();

			}else{

				alert(resp.msg);
			
			}
		

		});



		

	});	

	



	function wp_ca_offer_timer2(dt, sel){

		// Set the date we're counting down to

		//alert(dt);

		var countDownDate = new Date(dt).getTime();//"Jan 5, 2018 15:37:25"

		

		// Update the count down every 1 second

		var x = setInterval(function() {

		

		  // Get todays date and time

		var now = new Date().getTime();

		

		  // Find the distance between now an the count down date

		var distance = countDownDate - now;

		

		  // Time calculations for days, hours, minutes and seconds

		var days = wp_ca_pad(Math.floor(distance / (1000 * 60 * 60 * 24)), 2);

		var hours = wp_ca_pad(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)), 2);

		var minutes = wp_ca_pad(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)), 2);

		var seconds = wp_ca_pad(Math.floor((distance % (1000 * 60)) / 1000), 2);

		

		  // Display the result in the element with id="demo"		  



			

		  // If the count down is finished, write some text

		if (distance < 0) {

			clearInterval(x);

			$(sel).fadeOut();

		}else{

		  //document.title = days+' D '+hours+' H '+minutes+' M '+seconds+' S ';

		  $(sel).find('.dd').html(days);

		  $(sel).find('.hh').html(hours);

		  $(sel).find('.mm').html(minutes);

		  $(sel).find('.ss').html(seconds);			  

		}

		

		  

		}, 1000);		

	}

	

		

	setTimeout(function(){

	

		if($('.wp-ca-offer-counter.type-end > ul').length>0){

			$.each($('.wp-ca-offer-counter.type-end > ul'), function(){

				var dt = $(this).data('dt');

				var id = $(this).data('id');

				wp_ca_offer_timer2(dt, '.wp-ca-offer-item-'+id);

			});

			

		}

		

	}, 3000);	

	

	if($('input[name^="wp_ca_my_products"]').length>0){

		

		$('input[name^="wp_ca_my_products"]').on('click', function(){

			if($('input[name^="wp_ca_my_products"]:checked').length>0){

				$('.wp_ca_products_form a[data-cta="pause"], .wp_ca_products_form a[data-cta="play"]').fadeIn();

			}else{

				$('.wp_ca_products_form a[data-cta="pause"], .wp_ca_products_form a[data-cta="play"]').hide();

			}

		});

		

		$('input[name^="wp_ca_my_products"]:visible:checked').trigger('click');

	

	}

	if($('.wp_ca_products_form .btn-group button').length>0){
		
		$('.wp_ca_products_form .btn-group button').on('click', function(){
	
		
	
			$('.wp_ca_products_form tbody tr').hide();
	
			
	
			$(this).parent().find('.active').removeClass('active');
	
			$(this).addClass('active');
	
			
	
			var status = $(this).data('status');
	
			$('input[name^="wp_ca_my_products"]:visible:checked').click();
	
			switch(status){
	
	
	
				case 'publish':
	
					$('.wp_ca_products_form input[type="submit"]').val('Pause');
					
					$('.wp_ca_products_form input[name="wp_ca_my_products_status"]').val('pause');
	
					
	
					
	
				break;
	
	
	
				case 'draft':
	
					$('.wp_ca_products_form input[type="submit"]').val('Activate');
	
					$('.wp_ca_products_form input[name="wp_ca_my_products_status"]').val('play');
					
					
	
				break;
	
				
	
			}
			
			$(this).parents().eq(1).find('a.dcta').attr('data-cta', $('.wp_ca_products_form input[name="wp_ca_my_products_status"]').val()).html($('.wp_ca_products_form input[type="submit"]').val());
	
			
			
			if(status=='wc-all'){
	
				$('.wp_ca_products_form tbody tr[class^="wp_ca_products_"').show();
				$.each($('.wp_ca_products_form tbody tr.no-item-to-show'), function(){
					$(this).removeClass('no-item-to-show').addClass('no-items-to-show');
				});
				
			}else{
				//console.log(status);
				$('.wp_ca_products_form tbody tr.wp_ca_products_'+status).show();

				if($('.wp_ca_products_form tbody tr.wp_ca_products_'+status).hasClass('no-items-to-show')){
					$('.wp_ca_products_form tbody tr.wp_ca_products_'+status).removeClass('no-items-to-show').addClass('no-item-to-show');
				}
			}
	
		});
		
		$('.wp_ca_products_form .btn-group button.active').click();
	}
	
	$('form.wp_ca_products_form').on('click', 'a.dcta', function(){
		var id = $(this).data('cta');
		//$('input[data-id="'+id+'"]').click();
		$('.fhbutton').click();
		
	});

	$('.wp_ca_duration_section').on('click', '.dropdown li a', function(e){

		e.preventDefault();

		

		$('.wp_ca_duration_section .dropdown li').removeClass('active');

		

		var id = $(this).parent().data('id');

		var txt = $(this).html();

		$(this).parent().addClass('active');

		$('.wp_ca_duration_section .dropdown button').html(txt+' <span class="caret"></span>');



		$('.wp_ca_duration_section input[name^="wp_ca_duration_type"]').val(id);
	
		
		$(this).parents().eq(2).find('.dropdown-menu').hide();
		

	});

	

	

	if($('.rentable_view.owner_view').length>0){

	

		var sd = setInterval(function(){

			if($('.wp_ca_duration_section').length>0){

				

				var duration_type = $('.wp_ca_duration_section input[name^="wp_ca_duration_type"]').val();

				duration_type = (duration_type!=''?duration_type:'d');

				

				$('.wp_ca_duration_section .dropdown li[data-id="'+duration_type+'"] a').click();

				

				clearInterval(sd);

			}

		}, 100);

		

	}

	

	$('.wp_ca_booking_tracking input[type="text"]').on('keyup, click, blur, change, keydown', function(){

		if($(this).val()!='')

		$('.wp_ca_booking_tracking input[type="submit"]').fadeIn();

		else

		$('.wp_ca_booking_tracking input[type="submit"]').fadeOut();

	});

	setTimeout(function(){

		$('.wp_ca_booking_tracking input[type="text"]').trigger('blur');

		if($('.wp_ca_booking_tracking input[type="submit"]').is(':visible')){

			$('.wp_ca_booking_tracking input[type="submit"]').click();

		}


			
		$('.account-user .avatar').on('click', function(){
			document.location.href = '/my-account';
		});
		
		if(wp_ca.wp_ca_not_vendor){
			$('<li class="upgrade-account-link"><a class="primary button round is-small" href="/upgrade-account"><span>Become a Vendor</span><i class="icon-user"></i></a></li>').insertAfter('li.header-search-form');
		}
		
		
		
		if($('.wp_ca_products_publish').length>0){
			$('.wp_ca_products_form .btn-group button[data-status="publish"]').append(' ('+$('.wp_ca_products_publish input[name^="wp_ca_my_products"]').length+')');
		}
		if($('.wp_ca_products_draft').length>0){
			$('.wp_ca_products_form .btn-group button[data-status="draft"]').append(' ('+$('.wp_ca_products_draft input[name^="wp_ca_my_products"]').length+')');
			
		}		
		
	}, 1000);
	
	setTimeout(function(){

		if($('#ns-product-data-inner-container').length>0){
			$('#ns-product-data-inner-container').show();
			$('#ns-wp-post-content-div, div[id^="ns-image-container"]').addClass('ns-general').show();
			
			$('#ns-product-data-inner-container .ns-left-list-data-container ul > li').on('click', function(){
				var tab_id = $(this).attr('id');
				$('div.ns-prod-data-tab').removeClass('ns-hidden').hide();
				$('div.ns-prod-data-tab.'+tab_id).show();
			});
		}
	
	}, 2000);

	$('.wp_ca_print_ticket').on('click', function(e){

		e.preventDefault();

		var this_link = $(this);
		var product = this_link.data('product');
		var order = this_link.data('order');
		var key_extend = product+'|'+order;
		var href = window.location.href;


		var url = new URL(href);
		var search = url.searchParams;
		var orignal_key = search.get('key');
		if(orignal_key == null){

			orignal_key = 'print_ticket';
		}
		key_extend = orignal_key+'##'+key_extend;
		search.set('key', key_extend);

		window.location.href = url.href;

	});

	$('.wp_ca_back_thankyou a').on('click', function(e){

		e.preventDefault();

		var this_link = $(this);
		var key = this_link.data('key');
		var href = window.location.href;




		var url = new URL(href);
		var search = url.searchParams;

		if(key == 'print_ticket'){

			search.delete('key');
		}else{

			search.set('key', key);

		}

		window.location.href = url.href;

	});


	
	

});		

jQuery.fn.extend({
    toggleText: function (a, b){
        var that = this;
            if (that.text() != a && that.text() != b){
                that.text(a);
            }
            else
            if (that.text() == a){
                that.text(b);
            }
            else
            if (that.text() == b){
                that.text(a);
            }
        return this;
    },
    toggleHtml: function (a, b){
        var that = this;
            if (that.html() != a && that.html() != b){
                that.html(a);
            }
            else
            if (that.html() == a){
                that.html(b);
            }
            else
            if (that.html() == b){
                that.html(a);
            }
        return this;
    }



	
});