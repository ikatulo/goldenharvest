<?php

/**
 * Set custom RSS feed links.
 *
 */
$options = get_option('golden_options');

function golden_custom_feed( $output, $feed ) {

    $options = get_option('golden_options');
    $url = $options['golden_rss_url'];

    if ( $url ) {
        $outputarray = array( 'rss' => $url, 'rss2' => $url, 'atom' => $url, 'rdf' => $url, 'comments_rss2' => '' );
        $outputarray[$feed] = $url;
        $output = $outputarray[$feed];
    }
    return $output;
}
add_filter( 'feed_link', 'golden_custom_feed', 1, 2 );

/**
 * Set custom Favicon.
 *
 */
function golden_custom_favicon() {
    $options = get_option('golden_options');
    $favicon_url = $options['golden_favicon'];

    if (!empty($favicon_url)) {
        echo '<link rel="shortcut icon" href="'. $favicon_url. '" />	'. "\n";
    }
}
add_action('wp_head', 'golden_custom_favicon');


/**
 * Set apple touch icon.
 *
 */
function golden_apple_touch() {
    $options = get_option('golden_options');
    $apple_touch = $options['golden_apple_touch'];

    if (!empty($apple_touch)) {
        echo '<link rel="apple-touch-icon" href="'. $apple_touch. '" />	'. "\n";
    }
}
add_action('wp_head', 'golden_apple_touch');

/**
 * Set meta description.
 *
 */
function golden_meta_description() {
    $options = get_option('golden_options');
    $golden_meta_description = $options['golden_meta_description'];

    if (!empty($golden_meta_description)) {
        echo '<meta name="description" content=" '. $golden_meta_description .'"  />' . "\n";
    }
}
add_action('wp_head', 'golden_meta_description');


/**
 * Set meta keywords.
 *
 */
function golden_meta_keywords() {
    $options = get_option('golden_options');
    $golden_meta_keywords = $options['golden_meta_keywords'];

    if (!empty($golden_meta_keywords)) {
        echo '<meta name="keywords" content=" '. $golden_meta_keywords .'"  />' . "\n";
    }
}
add_action('wp_head', 'golden_meta_keywords');


/**
 * Set Google site verfication code
 *
 */
function golden_google_verification() {
    $options = get_option('golden_options');
    $golden_google_verification = $options['golden_google_verification'];

    if (!empty($golden_google_verification)) {
        echo '<meta name="google-site-verification" content="' . $golden_google_verification . '" />' . "\n";
    }
}
add_action('wp_head', 'golden_google_verification');

/**
 * Set Bing site verfication code
 *
 */
function golden_bing_verification() {
    $options = get_option('golden_options');
    $golden_bing_verification = $options['golden_bing_verification'];

    if (!empty($golden_bing_verification)) {
        echo '<meta name="msvalidate.01" content="' . $golden_bing_verification . '" />' . "\n";
    }
}
add_action('wp_head', 'golden_bing_verification');

/**
 * Set Alexa site verfication code
 *
 */
function golden_alexa_verification() {
    $options = get_option('golden_options');
    $golden_alexa_verification = $options['golden_alexa_verification'];

    if (!empty($golden_alexa_verification)) {
        echo '<meta name="alexaVerifyID" content="' . $golden_alexa_verification . '" />' . "\n";
    }
}
add_action('wp_head', 'golden_alexa_verification');



/**
 * Add code in the header.
 *
 */
function golden_header_code() {
    $options = get_option('golden_options');
    $golden_header_code = $options['golden_header_code'];
    if (!empty($golden_header_code)) {
        echo $golden_header_code;
    }
}
add_action('wp_head', 'golden_header_code');


/**
 * Add code in the footer.
 *
 */
function golden_footer_code() {
    $options = get_option('golden_options');
    $golden_footer_code = $options['golden_footer_code'];
    if (!empty($golden_footer_code)) {
        echo $golden_footer_code;
    }
}
add_action('wp_footer', 'golden_footer_code');


?>