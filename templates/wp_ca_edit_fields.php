<style type="text/css">
.ugc-notice{
	display:none;
}
.fu-upload-form{
	float:left;
	width:100%;
	display:none;
}
</style>

<?php

$item_category = get_the_terms( $product->get_id(), 'product_cat' );
$item_categories_selected = -1;
if(!empty($item_category)){
	foreach($item_category as $category){
		$item_categories_selected = $category->term_id;
	}
}

$args = array(
	'show_option_all'    => '',
	'show_option_none'   => 'Select',
	'option_none_value'  => '-1',
	'orderby'            => 'name',
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 0,
	'child_of'           => 0,
	'exclude'            => '',
	'include'            => '',
	'echo'               => 1,
	'selected'           => $item_categories_selected,
	'hierarchical'       => 1,
	'name'               => 'item_category',
	'id'                 => 'item_category',
	'class'              => 'form-control',
	'depth'              => 4,
	'tab_index'          => 0,
	'taxonomy'           => 'product_cat',
	'hide_if_empty'      => false,
	'value_field'	     => 'term_id',
);

?>
<div class="row">
<div class="col-md-8 edit-section">
<?php
if($wp_fu_plugin)
echo do_shortcode('[fu-upload-form form_layout="image" post_type="product" post_id="'.$product->get_id().'" title=""][/fu-upload-form]');
?>
<script type="text/javascript" language="javascript">

	jQuery(document).ready(function($){
		$.each($('#ug_post_title, #ug_content'), function(){
			$(this).val('gallery image');
			$(this).parent().hide();
		});
		$('label[for="ug_photo"]').html('<?php _e('Gallery Images', 'booking-works'); ?>');
		$('#ug_submit_button').val('<?php _e('Upload Images', 'booking-works'); ?>');
		$('.fu-upload-form').show();
	});
</script>
<?php if($is_edit): ?>
  <div class="form-group item_category_div">
    <label for="item_category"><?php _e('Category', 'booking-works'); ?></label>
    <?php wp_dropdown_categories($args); ?>

  </div>
<?php endif; ?>  
  

</div>

</div>