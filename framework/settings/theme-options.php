<?php

    function golden_admin_enqueue_scripts( $hook_suffix ) {        
        wp_enqueue_style('thickbox');
        wp_enqueue_style( 'golden_theme_options', get_template_directory_uri() . '/framework/settings/css/theme-options.css', false, '1.0' );
        wp_enqueue_script( 'golden_theme_options', get_template_directory_uri() . '/framework/settings/js/theme-options.js', array( 'jquery' ), '1.0' );
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('golden_upload', get_template_directory_uri() .'/framework/settings/js/upload.js', array('jquery','media-upload','thickbox'));
    }

    add_action( 'admin_print_styles-appearance_page_golden-options', 'golden_admin_enqueue_scripts' );

    
    function golden_admincss_enqueue_script(){

        wp_enqueue_style( 'admin', get_template_directory_uri() . '/css/admin.css', array(), '1.0' );
    }

    add_action( 'admin_enqueue_scripts', 'golden_admincss_enqueue_script' );


    global $pagenow;

    if( ( 'themes.php' == $pagenow ) && ( isset( $_GET['activated'] ) && ( $_GET['activated'] == 'true' ) ) ) :

        function golden_init_options() {
            $options = get_option( 'golden_options' );
            if ( false === $options ) {
                $options = golden_default_options();
            }
            update_option( 'golden_options', $options );
        }
        add_action( 'after_setup_theme', 'golden_init_options', 9 );
    endif;

    function golden_register_settings() {
        register_setting( 'golden_options', 'golden_options', 'golden_validate_options' );
    }
    add_action( 'admin_init', 'golden_register_settings' );


    function golden_theme_add_page() {
        add_theme_page( __( 'Theme Options', 'golden' ), __( 'Theme Options', 'golden' ), 'edit_theme_options', 'golden-options', 'golden_theme_options_page' );
    }
    add_action( 'admin_menu', 'golden_theme_add_page');


    function golden_theme_options_page() {
        ?>
        <div id="golden-admin">
            <div class="header">
                <div class="main">
                    <div class="left">
                        <h2><?php echo _e('Theme Options', 'golden'); ?></h2>
                    </div>

                    <div class="theme-info">
                        <h3><?php _e('Golden Harvest', 'golden'); ?></h3>
                        <ul>
                            <li class="support">
                                <a href="<?php echo esc_url(__('http://magnet.sg', 'golden')); ?>" title="<?php _e('Theme Support', 'golden'); ?>" target="_blank"><?php printf(__('Theme Support', 'golden')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- /header -->

            <div class="options-wrap">

                <div class="tabs">
                    <ul>
                        <li class="general first"><a href="#tab1"><?php echo _e('General', 'golden'); ?></a></li>
                        <li class="seo"><a href="#tab2"><?php echo _e('SEO', 'golden'); ?></a></li>
                        <li class="footer"><a href="#tab3"><?php echo _e('Header & Footer', 'golden'); ?></a></li>
                        <li class="reset"><a href="#tab4"><?php echo _e('Reset', 'golden'); ?></a></li>
                    </ul>
                </div><!-- /subheader -->

                <div class="options-form">

                    <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                        <div class="updated fade"><p><?php _e('Theme settings updated successfully', 'golden'); ?></p></div>
                    <?php endif; ?>

                    <form action="options.php" method="post">

                        <?php settings_fields( 'golden_options' ); ?>
                        <?php $options = get_option('golden_options'); ?>

                        <div class="tab_content">
                            <div id="tab1" class="tab_block">
                                <h2><?php _e('General Settings', 'golden'); ?></h2>

                                <div class="fields_wrap">

                                    <div class="field infobox">
                                        <p><strong>Uploading Images</strong></p>
                                        You can specify the complete URLs for the logo and other images or you can upload the image. Please read the documentation for the image uploading instructions.
                                    </div>

                                    <h3><?php _e('Header Settings', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_logo_url">Upload logo</label>
                                        <input id="golden_options[golden_logo_url]" class="upload_image" type="text" name="golden_options[golden_logo_url]" value="<?php echo esc_attr($options['golden_logo_url']); ?>" />

                                        <input class="upload_image_button" id="golden_logo_upload_button" type="button" value="Upload" />
                                        <span class="description long updesc">Upload a logo image or specify path. Max width: 390px. Max height: 55px.</span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_favicon">Upload Favicon</label>
                                        <input id="golden_options[golden_favicon]" class="upload_image" type="text" name="golden_options[golden_favicon]" value="<?php echo esc_attr($options['golden_favicon']); ?>" />
                                        <input class="upload_image_button" id="golden_favicon_button" type="button" value="Upload" />
                                        <span class="description updesc long">Upload your 16x16 px favicon or specify path.</span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_apple_touch">Apple Touch Icon</label>
                                        <input id="golden_options[golden_apple_touch]" class="upload_image" type="text" name="golden_options[golden_apple_touch]" value="<?php echo esc_attr($options['golden_apple_touch']); ?>" />
                                        <input class="upload_image_button" id="golden_apple_touch_button" type="button" value="Upload" />
                                        <span class="description updesc">Upload your 114px by 114px icon..</span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_rss_url]"><?php _e('Custom RSS URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_rss_url]" name="golden_options[golden_rss_url]" type="text" value="<?php echo esc_attr($options['golden_rss_url']); ?>" />
                                        <span class="description long"><?php _e( 'Enter full URL of RSS Feeds link starting with <strong>http:// </strong>. Leave blank to use default RSS Feeds.', 'golden' ); ?></span>
                                    </div>

                                    <h3><?php _e('Social Media Profiles', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_options[golden_twitter_url]"><?php _e('Twitter URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_twitter_url]" name="golden_options[golden_twitter_url]" type="text" value="<?php echo esc_attr($options['golden_twitter_url']); ?>" />
                                        <span class="description"><?php _e( 'Enter full URL of your twitter profile. Leave blank if you don\'t want to display.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_fb_url]"><?php _e('Facebook URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_fb_url]" name="golden_options[golden_fb_url]" type="text" value="<?php echo esc_attr($options['golden_fb_url']); ?>" />
                                        <span class="description"><?php _e( 'Enter full URL of your Facebook profile. Leave blank if you don\'t want to display.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_gplus_url]"><?php _e('Google+ URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_gplus_url]" name="golden_options[golden_gplus_url]" type="text" value="<?php echo esc_attr($options['golden_gplus_url']); ?>" />
                                        <span class="description"><?php _e( 'Enter full URL of your Google+ page. Leave blank if you don\'t want to display.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_pinterest_url]"><?php _e('Pinterest URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_pinterest_url]" name="golden_options[golden_pinterest_url]" type="text" value="<?php echo esc_attr($options['golden_pinterest_url']); ?>" />
                                        <span class="description"><?php _e( 'Enter full URL of your Pinterest page. Leave blank if you don\'t want to display.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_instagram_url]"><?php _e('Instagram URL', 'golden'); ?></label>
                                        <input id="golden_options[golden_instagram_url]" name="golden_options[golden_instagram_url]" type="text" value="<?php echo esc_attr($options['golden_instagram_url']); ?>" />
                                        <span class="description"><?php _e( 'Enter full URL of your Instagram page. Leave blank if you don\'t want to display.', 'golden' ); ?></span>
                                    </div>

                                    <h3><?php _e('Contact Details', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_name]"><?php _e('Name/Title', 'golden'); ?></label>
                                        <input id="golden_options[golden_contact_name]" name="golden_options[golden_contact_name]" type="text" value="<?php echo esc_attr($options['golden_contact_name']); ?>" />
                                        <span class="description long"><?php _e( 'Enter contact name / title.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_address]"><?php _e('Address', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_contact_address]" class="textarea" name="golden_options[golden_contact_address]"><?php echo esc_attr($options['golden_contact_address']); ?></textarea>
                                        <span class="description long"><?php _e( 'Enter the address for the map on contact page.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_email]"><?php _e('Email Address', 'golden'); ?></label>
                                        <input id="golden_options[golden_contact_email]" name="golden_options[golden_contact_email]" type="text" value="<?php echo esc_attr($options['golden_contact_email']); ?>" />
                                        <span class="description long"><?php _e( 'Enter the email address where you wish to receive the contact form messages.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_phone]"><?php _e('Phone', 'golden'); ?></label>
                                        <input id="golden_options[golden_contact_phone]" name="golden_options[golden_contact_phone]" type="text" value="<?php echo esc_attr($options['golden_contact_phone']); ?>" />
                                        <span class="description long"><?php _e( 'Enter phone number.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_fax]"><?php _e('Fax', 'golden'); ?></label>
                                        <input id="golden_options[golden_contact_fax]" name="golden_options[golden_contact_fax]" type="text" value="<?php echo esc_attr($options['golden_contact_fax']); ?>" />
                                        <span class="description long"><?php _e( 'Enter fax number.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_contact_map]"><?php _e('Latitude,Longitude', 'golden'); ?></label>
                                        <input id="golden_options[golden_contact_map]" name="golden_options[golden_contact_map]" type="text" value="<?php echo esc_attr($options['golden_contact_map']); ?>" />
                                        <span class="description long"><?php _e( 'Enter Latitude and Longitude, check here: https://maps.google.com/.', 'golden' ); ?></span>
                                    </div>


                                </div> <!-- /fields-wrap -->

                            </div><!-- /tab_block -->                             
                            
                            <div id="tab2" class="tab_block">
                                <h2><?php _e('SEO Settings', 'golden'); ?></h2>

                                <div class="fields_wrap">

                                    <div class="field infobox">
                                        <p><strong>Site Verification</strong></p>
                                        You can improve your search rankings by verifying your website with Bing and Google.
                                        Please read the theme documentation for step by step instructions on how to find Google and Bing site verification IDs.
                                    </div>

                                    <h3><?php _e('Default Meta Settings', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_options[golden_meta_keywords]"><?php _e('Default Meta Keywords', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_meta_keywords]" class="textarea"  name="golden_options[golden_meta_keywords]"><?php echo esc_attr($options['golden_meta_keywords']); ?></textarea>
                                        <span class="description"><?php _e( 'Add default meta keywords. Separate keywords with commas.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_meta_description]"><?php _e('Default Meta Description', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_meta_description]" class="textarea" name="golden_options[golden_meta_description]"><?php echo esc_attr($options['golden_meta_description']); ?></textarea>
                                        <span class="description"><?php _e( 'Add default meta description.', 'golden' ); ?></span>
                                    </div>

                                    <h3><?php _e('Site Verification', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_options[golden_google_verification]"><?php _e('Google Site Verification', 'golden'); ?></label>
                                        <input id="golden_options[golden_google_verification]" type="text" name="golden_options[golden_google_verification]" value="<?php echo esc_attr($options['golden_google_verification']); ?>" />
                                        <span class="description"><?php _e( 'Enter your ID only.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_bing_verification]"><?php _e('Bing Site Verification', 'golden'); ?></label>
                                        <input id="golden_options[golden_bing_verification]" type="text" name="golden_options[golden_bing_verification]" value="<?php echo esc_attr($options['golden_bing_verification']); ?>" />
                                        <span class="description"><?php _e( 'Enter the ID only. It will be verified by <strong>Yahoo</strong> as well.','golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_alexa_verification]"><?php _e('Alexa Site Verification', 'golden'); ?></label>
                                        <input id="golden_options[golden_alexa_verification]" type="text" name="golden_options[golden_alexa_verification]" value="<?php echo esc_attr($options['golden_alexa_verification']); ?>" />
                                        <span class="description"><?php _e( 'Enter your ID only.', 'golden' ); ?></span>
                                    </div>

                                </div> <!-- /fields-wrap -->

                            </div>	<!-- /tab_block -->

                            <div id="tab3" class="tab_block">
                                <h2><?php _e('Header and Footer Settings', 'golden'); ?></h2>
                                <div class="fields_wrap">

                                    <div class="field infobox">
                                        <p><strong>Using Site Analytics Codes</strong></p>
                                        You can use site analytics codes in the header of footer.
                                    </div>

                                    <h3><?php _e('Header Settings', 'golden'); ?></h3>

                                    <div class="field">
                                        <label for="golden_options[golden_header_code]"><?php _e('Header Code.', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_header_code]" class="textarea" name="golden_options[golden_header_code]"><?php echo esc_attr($options['golden_header_code']); ?></textarea>
                                        <span class="description"><?php _e( 'You can add any code eg. Google Analytics. It will appear in <strong>head</strong> section.', 'golden' ); ?></span>
                                    </div>

                                    <h3><?php _e('Footer Settings', 'golden'); ?></h3>
                                    <div class="field">
                                        <label for="golden_options[golden_footer_text_left]"><?php _e('Footer Text.', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_footer_text_left]" class="textarea" name="golden_options[golden_footer_text_left]"><?php echo esc_attr($options['golden_footer_text_left']); ?></textarea>
                                        <span class="description"><?php _e( 'Enter the footer left side text.', 'golden' ); ?></span>
                                    </div>

                                    <div class="field">
                                        <label for="golden_options[golden_footer_code]"><?php _e('Footer Code', 'golden'); ?></label>
                                        <textarea id="golden_options[golden_footer_code]" class="textarea" name="golden_options[golden_footer_code]"><?php echo esc_attr($options['golden_footer_code']); ?></textarea>
                                        <span class="description"><?php _e( 'You can add any code eg. Google Analytics. It will appear in <strong>footer</strong> section.', 'golden' ); ?></span>
                                    </div>

                                </div> <!-- /fields-wrap -->


                            </div>	<!-- /tab_block -->

                            

                            <div id="tab4" class="tab_block">
                                <h2><?php _e('Reset Theme Settings', 'golden'); ?></h2>
                                <div class="fields_wrap">
                                    <div class="field warningbox">
                                        <p><strong>Please Note</strong></p>
                                        You will lose all your theme settings and theme will restore default settings.
                                    </div>

                                    <div class="field">
                                        <p class="reset-info"> If you want to reset the theme settings. </p>
                                        <input type="submit" name="golden_options[reset]" class="button-primary" value="<?php _e( 'Reset Settings', 'golden' ); ?>" />
                                    </div>
                                </div>	<!-- /fields_wrap -->
                            </div>	<!-- /tab_block -->

                        </div> <!-- /option_blocks -->

                </div> <!-- /options-form -->
            </div> <!-- /options-wrap -->
            <div class="options-footer">
                <input type="submit" name="golden_options[submit]" class="button-primary" value="<?php _e( 'Save Settings', 'golden' ); ?>" />
            </div>
            </form>
        </div> <!-- /sssc-admin -->
    <?php
    }


    /**
     * Return default array of options
     */
    function golden_default_options() {
        $options = array(
            'golden_logo_url' => get_template_directory_uri().'/images/logo.jpg',
            'golden_footer_logo_url' => get_template_directory_uri().'/images/footer_logo.png',
            'golden_favicon' => '',
            'golden_apple_touch' => '',
            'golden_rss_url' => '',
            'golden_twitter_url' => '',
            'golden_fb_url' => '',
            'golden_gplus_url' => '',
            'golden_contact_address' => '',
            'golden_contact_email' => '',
            'golden_contact_subject' => '',
            'golden_plan_title' => '',
            'golden_plan_text' => '',
            'golden_plan_button_text' => '',
            'golden_meta_keywords' => '',
            'golden_meta_description' => '',
            'golden_google_verification' => '',
            'golden_bing_verification' => '',
            'golden_slideshow1_url' => '',
            'golden_slideshow1_title' => '',
            'golden_slideshow1_loc_id' => '',
            'golden_slideshow2_url' => '',
            'golden_slideshow2_title' => '',
            'golden_slideshow2_loc_id' => '',
            'golden_slideshow3_url' => '',
            'golden_slideshow3_title' => '',
            'golden_slideshow3_loc_id' => '',
            'golden_slideshow4_url' => '',
            'golden_slideshow4_title' => '',
            'golden_slideshow4_loc_id' => '',
            'golden_slideshow5_url' => '',
            'golden_slideshow5_title' => '',
            'golden_slideshow5_loc_id' => '',
            'golden_header_code' => '',
            'golden_footer_text_left' => '&copy;'. date('Y') . ' '. get_bloginfo('name'),
            'golden_footer_code' => ''
        );
        return $options;
    }


    /**
     * Sanitize and validate options
     */
    function golden_validate_options( $input ) {
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'] ) ? true : false );
        if( $submit ) :

            $input['golden_logo_url'] = esc_url_raw($input['golden_logo_url']);
            $input['golden_footer_logo_url'] = esc_url_raw($input['golden_footer_logo_url']);
            $input['golden_favicon'] = esc_url_raw($input['golden_favicon']);
            $input['golden_apple_touch'] = esc_url_raw($input['golden_apple_touch']);
            $input['golden_rss_url'] = esc_url_raw($input['golden_rss_url']);
            $input['golden_twitter_url'] = esc_url_raw($input['golden_twitter_url']);
            $input['golden_fb_url'] = esc_url_raw($input['golden_fb_url']);
            $input['golden_gplus_url'] = esc_url_raw($input['golden_gplus_url']);
            $input['golden_contact_address'] = wp_kses_stripslashes($input['golden_contact_address']);
            $input['golden_contact_email'] = wp_filter_nohtml_kses($input['golden_contact_email']);
            $input['golden_contact_subject'] = wp_kses_stripslashes($input['golden_contact_subject']);
            $input['golden_plan_title'] = wp_filter_nohtml_kses($input['golden_plan_title']);
            $input['golden_plan_text'] = wp_kses_stripslashes($input['golden_plan_text']);
            $input['golden_plan_button_text'] = wp_kses_stripslashes($input['golden_plan_button_text']);
            $input['golden_meta_keywords'] = wp_filter_post_kses($input['golden_meta_keywords']);
            $input['golden_meta_description'] = wp_filter_post_kses($input['golden_meta_description']);
            $input['golden_google_verification'] = wp_filter_post_kses($input['golden_google_verification']);
            $input['golden_bing_verification'] = wp_filter_post_kses($input['golden_bing_verification']);

            $input['golden_slideshow1_url'] = esc_url_raw($input['golden_slideshow1_url']);
            $input['golden_slideshow1_title'] = wp_filter_nohtml_kses($input['golden_slideshow1_title']);
            $input['golden_slideshow1_loc_id'] = wp_filter_nohtml_kses($input['golden_slideshow1_loc_id']);
            $input['golden_slideshow2_url'] = esc_url_raw($input['golden_slideshow2_url']);
            $input['golden_slideshow2_title'] = wp_filter_nohtml_kses($input['golden_slideshow2_title']);
            $input['golden_slideshow2_loc_id'] = wp_filter_nohtml_kses($input['golden_slideshow2_loc_id']);
            $input['golden_slideshow3_url'] = esc_url_raw($input['golden_slideshow3_url']);
            $input['golden_slideshow3_title'] = wp_filter_nohtml_kses($input['golden_slideshow3_title']);
            $input['golden_slideshow3_loc_id'] = wp_filter_nohtml_kses($input['golden_slideshow3_loc_id']);
            $input['golden_slideshow4_url'] = esc_url_raw($input['golden_slideshow4_url']);
            $input['golden_slideshow4_title'] = wp_filter_nohtml_kses($input['golden_slideshow4_title']);
            $input['golden_slideshow4_loc_id'] = wp_filter_nohtml_kses($input['golden_slideshow4_loc_id']);
            $input['golden_slideshow5_url'] = esc_url_raw($input['golden_slideshow5_url']);
            $input['golden_slideshow5_title'] = wp_filter_nohtml_kses($input['golden_slideshow5_title']);
            $input['golden_slideshow5_loc_id'] = wp_filter_nohtml_kses($input['golden_slideshow5_loc_id']);

            $input['golden_header_code'] = wp_kses_stripslashes($input['golden_header_code']);
            $input['golden_footer_text_left'] = wp_kses_stripslashes($input['golden_footer_text_left']);
            $input['golden_footer_code'] = wp_kses_stripslashes($input['golden_footer_code']);

            return $input;

        elseif( $reset ) :
            $input = golden_default_options();
            return $input;

        endif;
    }


    if ( ! function_exists( 'golden_get_option' ) ) :
        /**
         * Used to output theme options is an elegant way
         * @uses get_option() To retrieve the options array
         */
        function golden_get_option( $option ) {
            $options = get_option( 'golden_options', golden_default_options() );
            return $options[ $option ];
        }
    endif;
