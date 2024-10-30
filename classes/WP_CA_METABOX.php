<?php


class WP_CA_METABOX
{

    public static function add(){

        add_meta_box(
            'wp_ca_booking_work_box',
            'WP Booking Works',
            [self::class, 'html'],
            'product',
            'side',
            'high'
        );

    }

    public static function get_add_ons(){

        $args = array(

            'numberposts' => -1,
            'post_type' => 'product',
            'post_status' => 'any',
            'meta_query' => array(

                    array(

                        'key' => '_wp_ca_booking_option',
                        'value' => 'add_on',
                        'compare' => '=',
                    )
            )

        );

        return get_posts($args);

    }

    private static function get_add_on_option_html($selection_list){

        $add_ons = self::get_add_ons();



        if(!empty($add_ons)){

            ?>

                <label for="wp_ca_add_on_list"><?php _e('Add-Ons Selection', 'booking-works') ?></label>
                <select name="wp_ca_add_on_list[]" data-selected='<?php echo json_encode($selection_list); ?>' name="wp_ca_add_on_list" id="wp_ca_add_on_list" multiple placeholder="<?php _e('Select Add-Ons', 'booking-works') ?>">
                    <?php

                    foreach ($add_ons as $add_on){

                        echo "<option value='$add_on->ID'>$add_on->post_title</option>";

                    }

                    ?>
                </select>


            <?php






        }else{

            ?>

                <div class="alert alert-info">
                    <?php _e('No add on found, Please define some products as add on.', 'booking-works'); ?>
                </div>

            <?php


        }

    }

    public static function save($post_id){

        if(is_admin() && isset($_POST['wp_ca_product_type'])){


            $wp_ca_product_type = ($_POST['wp_ca_product_type']=='true'?'renting':'');

            $wp_ca_booking_option = wpbw_sanitize_bw_data($_POST['wp_ca_booking_option']);
            $wp_ca_add_on_list = wpbw_sanitize_bw_data($_POST['wp_ca_add_on_list']);



            update_post_meta($post_id, '_wp_ca_booking_option', $wp_ca_booking_option);
            update_post_meta($post_id, '_wp_ca_add_on_list', $wp_ca_add_on_list);


            update_post_meta($post_id, '_wp_ca_product_type', $wp_ca_product_type);

            $hours_enabled = ($_POST['hours_enabled']=='true');

            if($hours_enabled)
                update_post_meta($post_id, '_hours_enabled', $hours_enabled);
            else
                delete_post_meta($post_id, '_hours_enabled');



        }

    }

    public static function html(){

        global $post, $wp_ca_template_list;


        self::get_add_ons();

        $wp_ca_product_type = get_post_meta($post->ID, '_wp_ca_product_type', true);
        $selected_template = get_post_meta($post->ID, '_wp_ca_selected_template', true);
        $selected_template = $selected_template ?? 'default';
        $wp_ca_booking_option = get_post_meta($post->ID, '_wp_ca_booking_option', true);
        $wp_ca_add_on_list = get_post_meta($post->ID, '_wp_ca_add_on_list', true);
        $wp_ca_add_on_list = $wp_ca_add_on_list ? $wp_ca_add_on_list : array();
        $wp_ca_booking_option = $wp_ca_booking_option ? $wp_ca_booking_option : 'default';

        $wp_ca_add_on_list = array_map('trim', $wp_ca_add_on_list);


        $wp_ca_product_type = ($wp_ca_product_type=='renting');
        $hours_enabled = get_post_meta($post->ID, '_hours_enabled', true);

        ?>

            <input class="wp_ca_booking_option" type="radio" id="wp_ca_default" name="wp_ca_booking_option" value="default" <?php echo $wp_ca_booking_option == 'default' ? 'checked': ''?>>
            <label for="wp_ca_default"><?php _e('Default', 'booking-works') ?></label><br>

            <input class="wp_ca_booking_option" type="radio" id="wp_ca_add_on" name="wp_ca_booking_option" value="add_on" <?php echo $wp_ca_booking_option == 'add_on' ? 'checked': ''?>>
            <label for="wp_ca_add_on"><?php _e('Add On', 'booking-works') ?></label><br>

            <input class="wp_ca_booking_option" type="radio" id="wp_ca_online_booking" name="wp_ca_booking_option" value="online_booking" <?php echo $wp_ca_booking_option == 'online_booking' ? 'checked': ''?>>
            <input type="hidden" class="wp_ca_product_type" name="wp_ca_product_type" value="<?php echo($wp_ca_product_type?'true':'false'); ?>" />

            <label for="wp_ca_online_booking"><?php _e('Online Booking', 'booking-works') ?></label><br>


            <div class="wp-ca-section" style="display: none">
                <hr>

                <div>
                    <input id="wp_ca_hour_selection" data-h="hours_enabled" type="checkbox" value="Hours Selection" class="hours_enabled" <?php echo ($hours_enabled?'checked':'') ?> >
                    <label for="wp_ca_hour_selection"><?php _e('Hours Selection', 'booking-works') ?></label>
                    <input type="hidden" class="hours_enabled" name="hours_enabled" value="<?php echo ($hours_enabled?'true':'false') ?>" />
                </div>

                <br>

                <div>

                    <?php self::get_add_on_option_html($wp_ca_add_on_list) ?>

                </div>

                <div>
                    <label for="wp_ca_template_selection"style=" display: block; margin-top: 15px;"><?php _e('Template Selection', 'booking-works') ?></label>
                    <select id="wp_ca_template_selection" name="wp_ca_template_selection" style="width: 100%">

                        <?php

                            foreach ($wp_ca_template_list as $slug => $name){



                                $selected = $selected_template == $slug ? 'selected' : '';


                                echo "<option value='$slug' $selected>$name</option>";

                            }

                        ?>

                    </select>

                </div>


            </div>

        <!--            $html  = '<div class="wp-ca-section"> <hr>';-->
        <!--		    $html .= '<input data-h="wp_ca_product_type" type="button" value="'.wpbw_messages('type').'" class="button button-secondary '.($wp_ca_product_type?'active':'').'">';-->
        <!--            $html .= '';-->
        <!--            $html .= '</div>';-->
        <!--            echo $html;-->
        <!---->
        <!---->
        <!--        wp_ca_custom_button();-->

            <?php




    }

    public static function get_product_add_on($product_id, $add_ons = array()){

        $product_add_ons = get_post_meta($product_id, '_wp_ca_add_on_list', true);
        $product_add_ons = $product_add_ons ?? array();

        $product_add_ons = array_map(function($add_on_id)use($add_ons){

            $return_array = null;

            if(empty($add_ons)){

                $product = new WC_Product($add_on_id);

                $return_array = array(

                    'id' => $add_on_id,
                    'title' => $product->get_name(),
                    'price' => $product->get_price(),
                    'formatted_price' => wc_price($product->get_price()),


                );


            }else{


                if(in_array($add_on_id, $add_ons)){

                    $product = new WC_Product($add_on_id);

                    $return_array = array(

                        'id' => $add_on_id,
                        'title' => $product->get_name(),
                        'price' => $product->get_price(),
                        'formatted_price' => wc_price($product->get_price()),


                    );


                }


            }

            return $return_array;



        }, $product_add_ons);

        $product_add_ons = array_filter($product_add_ons);

        return $product_add_ons;

    }



}