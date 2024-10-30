// JavaScript Document

jQuery(document).ready(function($){



		$('.woo_inst_checkout_options').on('click', function(){

			if($(this).is(':checked')){

				$(this).parent().addClass('selected');

			}else{

				$(this).parent().removeClass('selected');

			}

		});


		$('.bw_settings_div a.nav-tab').click(function(){


			$(this).siblings().removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			$('.nav-tab-content').hide();
			$('.nav-tab-content').eq($(this).index()).show();
			window.history.replaceState('', '', wp_ca.this_url+'&t='+$(this).index());
			wp_ca.wp_ca_tab = $(this).index();
			$('form input[name="bw_tn"]').val($(this).index());

		});

		

		// $('.bw_settings_div a.nav-tab').click(function(){
		//
		// 	$(this).siblings().removeClass('nav-tab-active');
		//
		// 	$(this).addClass('nav-tab-active');
		//
		// 	$('.nav-tab-content').hide();
		//
		// 	$('.nav-tab-content').eq($(this).index()).show();
		//
		// });
		
		if($('.wp_ca_posting_options input[type="radio"]').length>0){
			$('.wp_ca_posting_options input[type="radio"]').on('click', function(){
				
				if($(this).val()=='paid'){
					$('#wp_ca_posting_fee').show();
				}else{
					$('#wp_ca_posting_fee').hide();
				}
			});
			
			$('.wp_ca_posting_options input[type="radio"]:checked').click();
		}

		var wp_ca_box = $('#wp_ca_booking_work_box');
		var wp_ca_section = wp_ca_box.find('.wp-ca-section');
		var wp_ca_booking_option = wp_ca_box.find('.wp_ca_booking_option');
		var wp_ca_booking_option_checked = wp_ca_box.find('.wp_ca_booking_option:checked');
		var wp_ca_hour_selection = wp_ca_box.find('#wp_ca_hour_selection');
		var wp_ca_hour_enabled = wp_ca_section.find('.hours_enabled');
		var wp_add_on_select = wp_ca_box.find('#wp_ca_add_on_list');

		wp_add_on_select.val(wp_add_on_select.data('selected'));

		wp_ca_hour_selection.on('change', function(){

			if($(this).prop('checked')){

				wp_ca_hour_enabled.val('true');

			}else{

				wp_ca_hour_enabled.val('false');

			}

		});



		wp_ca_booking_option.on('change', function(){


			var product_type = wp_ca_box.find('.wp_ca_product_type');

			if($(this).val() == 'online_booking'){
				wp_ca_section.show();

				$('#wp_ca_add_on_list').select2({
					placeholder: 'Select Add-ons',
					allowClear: true
				});

				$('#wp_ca_template_selection').select2();

				product_type.val('true');



			}else{

				wp_ca_section.hide();
				product_type.val('false');

			}

		});

		wp_ca_hour_selection.change();
		wp_ca_booking_option_checked.change();



		

});		