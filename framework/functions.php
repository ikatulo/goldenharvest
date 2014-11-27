<?php

add_action( 'after_setup_theme', 'golden_setup' );

if(! function_exists('golden_setup')):

function golden_setup(){

    /**
     * Load up our required theme files.
     */
    require( get_template_directory() . '/framework/settings/theme-options.php' );
    require( get_template_directory() . '/framework/settings/option-functions.php' );


    if ( ! isset( $content_width ) )
        $content_width = 600;

    add_editor_style();

    register_nav_menus( array(
        'primary-menu' => __( 'Primary Menu', 'golden' ),
        'footer-menu' => __( 'Footer Menu', 'golden' ),
        'mobile-menu' => __( 'Mobile Menu', 'golden' )
    ) );


    if ( function_exists( 'add_theme_support' ) ) {
        add_theme_support( 'post-thumbnails' );
    }

    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    show_admin_bar(false);
}

endif;

/**
* A safe way of adding JavaScripts to a WordPress generated page.
*/

if (!is_admin()){

    add_action('wp_enqueue_scripts', 'golden_js');
}

if (!function_exists('golden_js')) {

    function golden_js() {
        wp_enqueue_script('golden_script', get_template_directory_uri() . '/js/app.js', array('jquery'));
        wp_localize_script( 'golden_script', 'ajaxURL', array( 'url' => admin_url( 'admin-ajax.php' )));
        wp_localize_script( 'golden_script', 'ajaxNonce',  wp_create_nonce('157c6e3c6c6e7b93686e26de9aa1a156'));
    }
    
}

if ( function_exists('register_sidebar') ) {

    register_sidebar( array(
        'name' => __( 'Sidebar Widgets', 'golden' ),
        'id' => 'sidebar-widgets',
        'description' => __( 'An optional widget area for sidebar', 'golden' ),
        'before_widget' => '<div id="%1$s" class="sidebar widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}

if ( ! function_exists( 'golden_pagination' ) ) :
    function golden_pagination() {
        global $wp_query;

        $big = 999999999; // This needs to be an unlikely integer

        // For more options and info view the docs for paginate_links()
        // http://codex.wordpress.org/Function_Reference/paginate_links
        $paginate_links = paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'mid_size' => 5
        ) );

        // Display the pagination if more than one page is found
        if ( $paginate_links ) {
            echo '<div class="pagination">';
            echo $paginate_links;
            echo '</div><!--// end .pagination -->';
        }
    }
endif;

?>