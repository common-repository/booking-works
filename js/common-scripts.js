// JavaScript Document

	var wp_ca_ymd = (typeof wp_ca.wp_ca_ymd!="object"?{}:wp_ca.wp_ca_ymd);
	var wp_ca_slots = {};
	var wp_ca_slot_hours = {};
	var to_book;
	var wp_ca_product_add_ons = [];
	function replaceAll(str, find, replace) {
		return str.replace(new RegExp(find, 'g'), replace);
	}
	
	function wp_ca_pad(num, size) {
		var s = num+"";
		while (s.length < size) s = "0" + s;
		return s;
	}

	function wp_ca_update_slot_hours_numbers($){
		
		
		
		$.each(wp_ca_slot_hours, function(slot, data){
			//alert(slot);
			var ymd = slot.split('-');
			var hours = 0;
			var tc_obj = 'table.wp_ca_tables[data-year="'+ymd[0]+'"][data-month="'+ymd[1]+'"] td.calendar-day[data-num="'+ymd[2]+'"]';
			var tc = $(tc_obj);
			if(tc.length>0){
				
					if(tc.find('ol').length==0)
					tc.append('<ol></ol>');
					else
					tc.find('ol').html('');
					
					tc = $(tc_obj);
			}			
			$.each(data, function(day_hour, status){
				
				//alert(day);
				if(status){
					hours++;
					var l = day_hour;
					var u = parseInt(day_hour)+1;
					l = (l<10?'0'+l:l);
					u = (u<10?'0'+u:u);
					tc.find('ol').append('<li>'+l+':00 - '+u+':00</li>');					

				}
			});			
			hours = (hours>0?hours:'&nbsp;&nbsp;');
			$('.wp_ca_cslots .row[data-slot="'+slot+'"] .hh').html(hours);
		});		
	}
	
		
jQuery(document).ready(function($){
	
	$('.wp_ca_goback').click(function(){
		window.history.back();
	});

	var to_book_date = '';
	
	
	
	setTimeout(function(){
		
		wp_ca_ymd_refresher($('.wp_ca_tables').data('year'), $('.wp_ca_tables').data('month'));
	
		if(wp_ca.wp_ca_edit || (wp_ca.wp_ca_valid_rid && wp_ca.wp_ca_rid>0)){
			$('.booking-section > .btn').click();
		}
		
	}, 500);
	

	$('body').on('click', '.owner_view .wp_ca_item_edit_section .submit', function(){
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
		
		$('.wp_ca_loader').show();
		$('.wp_ca_tables').css('opacity', '0.1');
		
		var number_of = $('.wp_ca_duration_section input[name^="wp_ca_number_of"]').serialize();
		var duration_type = $('.wp_ca_duration_section input[name^="wp_ca_duration_type"]').serialize();
		
			
		var data = {
				'action':'wp_ca_hours_selection',
				'hours_selected':hours_selected,
				'postid':wp_ca.wp_ca_wc_product_id,
				'timezone':timezone,
				'number_of':number_of,
				'wp_ca_sale_status':($('.wp_ca_sale_switch .active').data('switch')=='on'),
				'duration_type':duration_type,
				'item_category':$('#item_category').val(),
				'wp_ca_product_type':$('#wp_ca_product_type').val(),
				'wp_ca_product_sub_type':$('#wp_ca_product_sub_type').val(),
				'ymd':wp_ca_ymd				
				
		};			
		$.post(wp_ca.ajaxurl, data, function(resp){
			resp = $.parseJSON(resp);
			
			$('.wp_ca_loader').fadeOut();
			$('.wp_ca_tables').css('opacity', 1);	
			
			
			// window.location.href = '/demo.fresh/my-account/my-products';
			window.location.href = window.location.href;
		});

		
	});
	
	var editable_element = '<div class="editable_wrapper"><textarea data-type="DTYPE" class="sl">VALUE</textarea><span class="editable">Save</save></div>';
	
	$('body').on('click', '.owner_view h1', function(){
		$(this).addClass('e-link');
		$(this).replaceWith(editable_element.replace('VALUE', $(this).html()).replace('DTYPE', $(this).data('type')));
		$('.edit-booking-cta').hide();
	});
	
	$('body').on('click', '.owner_view .wp_ca_product_price strong', function(){
		
		$(this).addClass('e-link');
		var val = $.trim($(this).parent().find('div').html());
		var ev = editable_element.replace('VALUE', val);		
		ev = ev.replace('DTYPE', $(this).data('type'));
		$(this).parent().find('div').replaceWith(ev);
		$(this).hide();		
		$('.edit-booking-cta').hide();
	});	
	
	$('body').on('click', '.owner_view .wp_ca_sale strong', function(){
		
		$(this).addClass('e-link');
		var val = $.trim($(this).parent().find('div').html());
		var ev = editable_element.replace('VALUE', val);		
		ev = ev.replace('DTYPE', $(this).data('type'));
		$(this).parent().find('div').replaceWith(ev);
		$(this).hide();		
		$('.edit-booking-cta').hide();
	});		
	
	$('body').on('click', '.owner_view .editable', function(){
		var val = '', type;
		if($(this).parents().eq(1).find('.e-link').length>0){
			$(this).parents().eq(1).find('.e-link').show();
			type = $(this).parent().find(':input').data('type');
			val = $.trim($(this).parent().find(':input').val().replace(/\n/g, '<br />'));
			$(this).parents().eq(0).replaceWith('<div>'+val+'</div>');
			
		}else{
			type = $(this).parent().find(':input').data('type');
			val = $.trim($(this).parent().find(':input').val());
			$(this).parent().replaceWith('<h1 data-type="'+type+'">'+val+'</h1>');
		}
		
		var data = {		
			'action':'wp_ca_update_chunks',		
			'postid':wp_ca.wp_ca_wc_product_id,
			'updated_text':val,
			'type':type
		};					
		if($.trim(val)!=''){
			$('.wp_ca_loader').show();
			
			$.post(wp_ca.ajaxurl, data, function(resp){		
				resp = $.parseJSON(resp);		
				$('.wp_ca_loader').hide();
			});
			
		}
		setTimeout(function(){
			$('.edit-booking-cta').show();
		}, 1000);
	});
	
	$('body').on('click', '.owner_view .wp_ca_product_desc strong, .owner_view .wp_ca_product_video strong', function(){

		$(this).addClass('e-link');
		var val = $.trim($(this).parent().find('div').html());
		val = replaceAll(val, '<p>', '');
		val = replaceAll(val, '</p>', '');
		val = replaceAll(val, '<br>', '\n');
		
		var ev = editable_element.replace('VALUE', val);
		ev = ev.replace('sl', 'mli').replace('DTYPE', $(this).data('type'));

		$(this).parent().find('div').replaceWith(ev);
		$(this).hide();
		$('.edit-booking-cta').hide();
		
	});	
	
	$('body').on('click', '.owner_view .wp_ca_product_types_div .prod_type', function(){
		$(this).parent().find('div').show();
		$(this).hide();
		//$('.edit-booking-cta').hide();
	});
	
	
	$('body .booking-section').on('click', '.calendars-now-btn', function(){
		$(this).parent().find('ul').toggle();
	});
	
	$('body .booking-section').on('click', '.calendars-prev, .calendars-next, .calendars-now, .wp_ca_cslots .row', function(){
		
		
		var dslot = $(this).data('slot');
		
		var set = $(this).data('set').split('-');
		
		var to_load_ym = set[0]+'-'+set[1];
		
		if(to_load_ym==wp_ca.wp_ca_loaded_ym){
			return;
		}else{	
			/*		
			$('html, body').animate({
				scrollTop: $(".booking-module").offset().top
			}, 1000);
			*/
		}

		var data = {
			'action':'wp_ca_next_prev',
			'month':set[0],
			'year':set[1],
			'pid':wp_ca.wp_ca_wc_product_id,
			'edit':wp_ca.wp_ca_edit,
			'ymd':wp_ca_ymd
		};
		$('.wp_ca_loader').show();
		$('.wp_ca_tables').css('opacity', '0.1');
		$('a.calendars-now').parent().removeClass('active');
		
		var ulObj = $('body .booking-section .calendars-now-btn.dropdown-toggle').parent().find('ul');
		
		if(ulObj.is(':visible'))
		ulObj.toggle();
		
		$.post(wp_ca.ajaxurl, data, function(resp){
			
			
			resp = $.parseJSON(resp);
			$('.calendars-prev').data('set', resp.ca_prev);
			$('.calendars-next').data('set', resp.ca_next);
			$('.calendars-nav-panel .calendars-now-btn').data('set', resp.ca_now).html(resp.ca_now_text+'<span class="caret"></span>');
			$('a.calendars-now[data-set="'+resp.ca_now+'"').parent().addClass('active');
			$('.wp_ca_loader').fadeOut();
			$('.wp_ca_tables').replaceWith(resp.calendar);
			$('.wp_ca_tables').css('opacity', 1);	
			
			var year = resp.ca_now.split('-');
			
			wp_ca_ymd_refresher(year[1], year[0]);
			
			
			$.each($('a.calendars-now'), function(){
				if($(this).data('year')!=year[1]){
					$(this).data('year', year[1]);
					$(this).data('set', $(this).data('month_n')+'-'+year[1]);
					$(this).html($(this).data('month_t')+' '+year[1]);
					
				}
			});
			
			
			wp_ca.wp_ca_loaded_ym = to_load_ym;
			
			
			var sslot = $('.wp_ca_cslots .row[data-set="'+wp_ca.wp_ca_loaded_ym+'"]');
			if(sslot.length>0){
				
				/*
				$('html, body').animate({
					scrollTop: $('.wp_ca_cslots').offset().top
				}, 1000);		
				*/

				$('.wp_ca_cslots .row[data-slot="'+dslot+'"]').click();

			}			
			
		});
	});
	


	function sortObject(o) {
		return Object.keys(o).sort().reduce((r, k) => (r[k] = o[k], r), {});
	}	
	
	setTimeout(function(){ 
		if($('.wp_ca_hours_selection').length>0 && $('.wp_ca_hours_selection').is(':visible')){
			wp_ca_hours_refresh(jQuery);
		}
	}, 1000);
	
	$('.wp_ca_cslots').on('click', 'div.row', function(){
		
		
		var slot = $(this).data('slot');
		
		
		$(this).parent().find('div.row').removeClass('current');
		
		$(this).addClass('current');
		
		$('.wp-ca-am-pm .btn-group-vertical button.active').removeClass('active');
		
		if(typeof wp_ca_slot_hours[slot]=="undefined"){
			
			wp_ca_slot_hours[slot] = {};
			
		}else{
			
			var slot_hours = wp_ca_slot_hours[slot];
			
			$.each(slot_hours, function(hour, status){
				
				if(status){
					
					$('.wp-ca-am-pm .btn-group-vertical button[data-val="'+hour+'"]').addClass('active');					
					
				}
				
			});
			
			wp_ca_hours_refresh(jQuery);
		}
		
		
		
	});
	

	function wp_ca_update_cslots(ty, tm){
		
		if(wp_ca_is_owner())
		return;

		if(typeof wp_ca_slots[ty]=="undefined")
		return;

		if(typeof wp_ca_slots[ty][tm]=="undefined")
		return;
		
		wp_ca_slots = sortObject(wp_ca_slots);

		
		var wp_ca_slots_selected = wp_ca_slots[ty][tm];
		var tbl = '.wp_ca_tables[data-year="'+ty+'"][data-month="'+tm+'"]';
		
		
		
		$.each(wp_ca_slots_selected, function(day, status){
			
			var cobj = $(tbl).find('td[data-num="'+day+'"]:not(.past):not(.confirmed)');
			
			if(status){
				cobj.addClass('selected');	
			}else{
				if(wp_ca.wp_ca_wc_product_details.hours_enabled=='true'){
					
				}else{
					cobj.removeClass('selected');
				}
			}
		});
		
		
		$('.wp_ca_cslots').html('');
		var slots = '<div data-set="MM-YYYY'+'" data-slot="YYYY-MM-DD'+'" class="row"><span class="yyyy">YYYY</span><span class="seps">-</span><span class="mm">MONTH</span><span class="seps">-</span><span class="dd">DAY</span><span class="hh">&nbsp;&nbsp;</span>'+'</div>';
		
		$.each(wp_ca_slots, function(year, wp_ca_slots_inner){
			
			wp_ca_slots_inner = sortObject(wp_ca_slots_inner);
			
			$.each(wp_ca_slots_inner, function(months, wp_ca_slots_inner2){
				
				wp_ca_slots_inner2 = sortObject(wp_ca_slots_inner2);
				
				$.each(wp_ca_slots_inner2, function(day, status){
					
					
					
					if(status){
						
							
						
											
						
						var cslots = replaceAll(slots, 'DD', day);
						cslots = replaceAll(cslots, 'DAY', wp_ca_pad(day, 2));			
						cslots = replaceAll(cslots, 'MONTH', wp_ca_pad(months, 2));			
						cslots = replaceAll(cslots, 'YYYY', year);
						cslots = replaceAll(cslots, 'MM', months);
						
							
						$('.wp_ca_cslots').append(cslots);
					}
					
 				});	
			});		
		});		
		
		//wp_ca_update_slot_hours_numbers();
	}
	
	$('body').on('click', 'table .calendar-day.normal.booked:not(.past):not(.na)', function(){
		var e_class = 'cot-'+wp_ca.wp_ca_rid;
		
		if($(this).hasClass(e_class)){
		
			$(this).removeClass('booked');
			var cn = $(this).data('num');
			var cm = $('.wp_ca_tables').data('month');
			var tmp_dmy = cn+'-'+cm+'-'+$('.wp_ca_tables').data('year');
			
			//alert(tmp_dmy);
			$('input[name^="existing_booking"][data-dmy="'+tmp_dmy+'"]').val(false);
			wp_ca_before_book();
			
		}
		//$(this).click();
	});
	
	
	$('body').on('click', '.wp_ca_item_edit_section.selecting .wp_ca_hours_selection .wipe-out', function(){
		
		
		var scell = $('.calendar-day.normal.selected.active');
		var cn = scell.data('num');
		var cm = $('.wp_ca_tables').data('month');		

		to_book_date = cn+'-'+cm+'-'+$('.wp_ca_tables').data('year');
	
		var dmy = to_book_date.split('-');
		
		
						
		wp_ca_reverse_selected_day(dmy[2], dmy[1], dmy[0], true);
		
		if(wp_ca_remove_selected_day(dmy[2], dmy[1], dmy[0], true))	
		$('.wp_ca_item_edit_section.selecting').removeClass('selecting');
		
	});		
	
	function wp_ca_reverse_selected_day(yyyy, mm, dd, force){
		
		//alert(yyyy+'`'+mm+'`'+dd);
				
		if(typeof wp_ca_slots[yyyy][mm][dd]=="undefined"){
			wp_ca_slots[yyyy][mm][dd] = true;
		}else if(wp_ca.wp_ca_wc_product_details.hours_enabled!='true' || (wp_ca.wp_ca_wc_product_details.hours_enabled=='true' && force)){
			wp_ca_slots[yyyy][mm][dd] = !wp_ca_slots[yyyy][mm][dd];
		}
			
		
		
	}
	
	function wp_ca_remove_selected_day(yyyy, mm, dd, force){
		
		var rm = false;
		//alert(yyyy+'`'+mm+'`'+dd);		
		var slot = yyyy+'-'+mm+'-'+dd;
	
		if(wp_ca.wp_ca_wc_product_details.hours_enabled!='true' || (wp_ca.wp_ca_wc_product_details.hours_enabled=='true' && force)){			
		
			
			if(typeof wp_ca_slots[yyyy][mm][dd]!="undefined" && !wp_ca_slots[yyyy][mm][dd]){
					
				if(typeof wp_ca_slot_hours[slot]!="undefined"){
					delete wp_ca_slot_hours[slot];				
				}
				
				delete wp_ca_slots[yyyy][mm][dd];
				rm = true;
				wp_ca_ymd_refresher(yyyy, mm);
				
					
				var tc_obj = 'table.wp_ca_tables[data-year="'+yyyy+'"][data-month="'+mm+'"] td.calendar-day[data-num="'+dd+'"]';
				var tc = $(tc_obj);
				
				if(tc.length>0 && tc.find('ol').length>0)
				tc.find('ol').remove();
				
				var tbl = '.wp_ca_tables[data-year="'+yyyy+'"][data-month="'+mm+'"]';
				if($(tbl).length>0){
					var cobj = $(tbl).find('td[data-num="'+dd+'"].selected');
					if(cobj.length>0){
						cobj.removeClass('selected');
					}
					
				}
								
			}	
			
		}
		
		return rm;	
	}
	
	$('body').on('click', 'table .calendar-day.normal:not(.past):not(.na):not(.booked)', function(){
		
		var scell = $(this);
		$('.calendar-day.active').removeClass('active');
		$(this).addClass('active');	
		
		var cn = scell.data('num');
		var cm = $('.wp_ca_tables').data('month');
		
		//cn = (cn<10?'0'+cn:cn);
		//cm = (cm<10?'0'+cm:cm);
		
		
		to_book_date = cn+'-'+cm+'-'+$('.wp_ca_tables').data('year');

		var dmy = to_book_date.split('-');
		var mm = moment(dmy[1], 'MM').format('MMMM');
		to_book = (dmy[0]+' '+mm+', '+dmy[2]);

		
		if(wp_ca.wp_ca_multiple=='true'){
		
	
			
			//if(wp_ca.wp_ca_wc_product_details.hours_enabled=='true'){

				
				var month = dmy[1];
				var day = dmy[0];
				
				if(typeof wp_ca_slots[dmy[2]]=="undefined"){
					wp_ca_slots[dmy[2]] = {}
				}
				
				if(typeof wp_ca_slots[dmy[2]][month]=="undefined"){
					wp_ca_slots[dmy[2]][month] = {}
				}
				
				
				wp_ca_reverse_selected_day(dmy[2], month, day, false);
				
				
				
				wp_ca_update_cslots(dmy[2], month);
				
				
				var slot = dmy[2]+'-'+month+'-'+day;
				var rm = wp_ca_remove_selected_day(dmy[2], month, day, false);
				
				/*$('.wp_ca_loader').show( 300 ).delay( 3000 ).hide( 400 );
				$('.wp_ca_tables, .calendars-nav-panel').hide();

				$('.book_date span').html(to_book);*/
				
				//alert(to_book_date);	
				if(wp_ca.wp_ca_wc_product_details.hours_enabled=='true'){						
					
					if(rm){
						$('.wp_ca_item_edit_section').removeClass('selecting');
					}else{
						
						$('.wp_ca_item_edit_section').addClass('selecting');
						
						var top_pos = '-'+(($('.booking-module').outerHeight()-scell.position().top-scell.outerHeight()))+'px';
						var left_pos = (scell.position().left-15)+'px';//(($('.booking-module').outerHeight()-scell.position().top-scell.outerHeight()))+'px';
						
						$('.wp_ca_item_edit_section.selecting').css({'top':top_pos, 'left':left_pos});
						
						
						setTimeout(function(){ 
							
							//alert(dmy[2]+' ~ '+dmy[1]);
							wp_ca_update_cslots(dmy[2], dmy[1]);
							
							$('.wp_ca_cslots .row[data-slot="'+slot+'"]').click();
							wp_ca_hours_refresh(jQuery);
						}, 100);
					}
				}
								
			
			//}
			
			
		}else{
		
			
			
			$('.wp_ca_loader').show( 300 ).delay( 3000 ).hide( 400 );
			$('.wp_ca_tables, .calendars-nav-panel').hide();
			
			
	
			$('.book_date span').html(to_book);
			
		}


		wp_ca_before_book();
		
	});
	
	function wp_ca_before_book(){
		if(!$('.wp_ca_calendar_items').is(':visible')){
			$('.wp_ca_calendar_items').show();


			$('.book_item span').html(wp_ca.wp_ca_wc_product_details.post_title);		
			
			$('.book_item_price span').html(wp_ca.wp_ca_wc_product_details.currency+wp_ca.wp_ca_wc_product_details.price);
			
			$('.book_item_duration span').html(wp_ca.wp_ca_wc_product_details.wp_ca_number_of+' '+wp_ca.wp_ca_wc_product_details.wp_ca_duration_type);		
		}		
	}
	
	$('body').on('click', 'table .calendar-day.edit:not(.past)', function(){
		
		var dd = $(this).data('num');
		var mm = $('.wp_ca_tables').data('month');
		var yy = $('.wp_ca_tables').data('year');
		
		if(wp_ca_ymd==null){
			wp_ca_ymd = {};
		}
				
		if(typeof wp_ca_ymd[yy]=="undefined"){
			wp_ca_ymd[yy] = {};
		}
		
		if(typeof wp_ca_ymd[yy][mm]=="undefined"){
			wp_ca_ymd[yy][mm] = {};
		}
		
		if(typeof wp_ca_ymd[yy][mm][dd]=="undefined"){
			wp_ca_ymd[yy][mm][dd] = new Boolean(true);
		}else{
			wp_ca_ymd[yy][mm][dd] = !wp_ca_ymd[yy][mm][dd];
		}
		
		wp_ca_ymd_refresher(yy, mm);
		
		
		
	});
	
	function wp_ca_ymd_refresher(yy, mm){
		//alert(yy+' - '+mm);
		
		var tbl = '.wp_ca_tables[data-year="'+yy+'"][data-month="'+mm+'"]';
		//alert(tbl);
		
		if($(tbl).length>0){
			//alert($(tbl).length);
			//alert(wp_ca_ymd[yy][mm]);
			
			var mm = $('.wp_ca_tables').data('month');
			var yy = $('.wp_ca_tables').data('year');	
			
			if(wp_ca_ymd==null){
				wp_ca_ymd = {};
			}
			
			if(typeof wp_ca_ymd[yy]=="undefined"){
				wp_ca_ymd[yy] = {};
			}
			
			if(typeof wp_ca_ymd[yy][mm]=="undefined"){
				wp_ca_ymd[yy][mm] = {};
			}
			
						
			$.each(wp_ca_ymd[yy][mm], function(i, v){
				
				var cobj = $(tbl).find('td[data-num="'+i+'"]:not(.past):not(.confirmed)');
				
				cobj.removeClass('na');
				//alert(i);
				//alert(v);
				v = (v=="true" || v==true);
				if(v){
					cobj.addClass('na');
				}
				
			});
			
			wp_ca_update_cslots(yy, mm);
		}		
	}
		
	
	$('body').on('click', '.wp_ca_calendar_items .back', function(){
		$('.wp_ca_loader').show( 300 ).delay( 1000 ).hide( 400 );
		$('.wp_ca_calendar_items').hide();
		$('.wp_ca_tables, .calendars-nav-panel').show();
	});
	
	$('body').on('click', '.wp_ca_calendar_items .book_confirm', function(){

		$('.wp_ca_loader').show( 300 );
		
		
		var hours_selected = [];
		$.each($('.wp-ca-am-pm button.active'), function(){
			hours_selected.push($(this).data('val'));
		});
		
	
				
		var data = {
			'action':'wp_ca_book_confirm',
			'existing_booking':$('input[name^="existing_booking"]').serializeArray(),
			'book_date':to_book_date,
			'wp_ca_rid':wp_ca.wp_ca_rid,
			'page_id':wp_ca.wp_ca_page_id,
			'wc_product_id':wp_ca.wp_ca_wc_product_id,
			'profile_id':wp_ca.wp_ca_profile_id,
			'hours_selected':hours_selected,	
			'wp_ca_multiple': (wp_ca.wp_ca_multiple=='true'),
			'wp_ca_slots':wp_ca_slots,
			'wp_ca_slot_hours':wp_ca_slot_hours,
			'book_notes':$('.book_notes textarea').val(),
			'wp_ca_product_add_ons':wp_ca_product_add_ons,
		};		
		
		//console.log(data);return;
		
		$.post(wp_ca.ajaxurl, data, function(resp){
			
			resp = $.parseJSON(resp);
			
			$('.wp_ca_loader').hide( 300 );
			
			if(resp.msg!="halt"){
				
				if(wp_ca.wp_ca_valid_rid && wp_ca.wp_ca_rid>0)
				window.location.href = '/booking-tracking/?id='+wp_ca.wp_ca_rid;
				else
				window.location.href = wp_ca.checkout_url;
			
			}
		});
	});
			


	function wp_ca_offer_timer(dt, sel){
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
	
		if($('.wp-ca-offer-counter.type-start > ul').length>0){
			$.each($('.wp-ca-offer-counter.type-start > ul'), function(){
				var dt = $(this).data('dt');
				var id = $(this).data('id');
				wp_ca_offer_timer(dt, '.wp-ca-offer-item-'+id);
			});
			
		}
		
	}, 3000);
	
	// const doc = new jspdf.jsPDF({
	// 	orientation: "landscape",
	// 	unit: "in",
	// 	format: [4, 2]
	// }).html();
	//
	// doc.html(canvas, 'JPEG', 15, 40, 180, 160)
	// doc.save("two-by-four.pdf");
	//
	// html2canvas(document.querySelector('#section-to-print')).then(function(canvas) {
	// 	document.body.appendChild(canvas);
	//
	// 	doc.addImage(canvas, 'JPEG', 15, 40, 180, 160)
	// 	doc.save("two-by-four.pdf");
	// });

	var img;

	$('.ticket-section .panel-footer').hide();
	$('.ticket-section .wp-ca-offer-counter').hide();
	$('.ticket-section .terms-section').parents('.row:first').hide();

	if($('#section-to-print').length > 0){

		html2canvas(document.querySelector("#section-to-print")).then(canvas => {

			img = canvas.toDataURL("image/jpeg,1.0");

			$('.ticket-section .panel-footer').show();
			$('.ticket-section .terms-section').parents('.row:first').show();
			$('.ticket-section .wp-ca-offer-counter').show();



			// var pdf = new jspdf.jsPDF();
			// pdf.addImage(img, 'png', 12, 5);
			// pdf.save('test.pdf');
		});

		$('.ticket-section .download a').on('click', function(){

			var pdf = new jspdf.jsPDF();
			pdf.addImage(img, 'png', 2, 2);
			pdf.save('test.pdf');

		})

	}


	var initial_change = false;

	$('.wp_ca_product_add_ons').on('change', function(){

		var book_item_total = $('.book_item_total_price');
		var add_ons_price = book_item_total.data('total');
		var add_ons = $('.wp_ca_product_add_ons:checked');
		var product_id = $(this).val();
		var this_checkbox = $(this);

		console.log(book_item_total);

		wp_ca_product_add_ons = [];


		if(add_ons.length > 0){

			$.each(add_ons, function(){

				wp_ca_product_add_ons.push($(this).val());

				var current_add_on_price = $(this).data('price');
				add_ons_price += current_add_on_price;

			});
		}



		book_item_total.find('span').html(wp_ca.currency_symbol+add_ons_price);



		if(!initial_change){
			initial_change = true;


			return;
		}


		var data = {

			'action' : 'wp_ca_add_on_cart',
			'product_id' : product_id,
			'cart_key' : 0,
			'update_cart': 'yes',

		};

		if(this_checkbox.prop('checked')){

		}else{

			data.cart_key = $(this).data('cart_key');
			data.update_cart = 'no';
		}

		$.post(wp_ca.ajaxurl, data, function(response){


			response = JSON.parse(response);

			if(data.update_cart == 'yes' && response.status == 'updated'){

				this_checkbox.data('cart_key', response.cart_key);

			}else{


				this_checkbox.data('cart_key', '');


			}


		});




	});

	$('.wp_ca_product_add_ons').change();





});		