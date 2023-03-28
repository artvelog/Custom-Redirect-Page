<?php
/**
 * Artvelog - Custom Redirect Page
 *
 * Plugin Name:       Artvelog - Redirect Page
 * Plugin URI:        https://creative.artvelog.com/#redirect-page
 * Description:       It creates a 'redirect page' between your website and the site to which you will be redirected for Wordpress.
 * Version:           1.05
 * Author:            Emre Ertan
 * Author URI:        https://emreertan.com
 * Text Domain:       redirect-artvelog
 * Domain Path:       /languages
 * 
 *
 * *
 * *Admin Setting Panel by Carbon Fields.
 * *
 * 
 * Supported Custom Page Plugins - Elementor, Gutenberg (Ultimate Addons for Gutenberg), SiteOrigin, Beaver Builder, WPBakery Page Builder (Visual Composer).
 */

load_plugin_textdomain( 'redirect-artvelog', false, __FILE__  . '/languages' );

//Options Page
require_once(plugin_dir_path( __FILE__ ) . "/inc/carbon-fields/carbon-fields-plugin.php");
require_once( plugin_dir_path( __FILE__ ) . "/admin/admin.php");


/****************************************************/

// Jquery Support
function jquery_support() {
    if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'jquery_support');

//If the reference text contains a link, it removes the ssl attachments.
function remove_http_https($url) {
    if (strpos($url, 'http://') === 0) {
        $url = substr($url, 7);
    } elseif (strpos($url, 'https://') === 0) {
        $url = substr($url, 8);
    }
    return $url;
}

//Makes the text suitable for the link
function text_to_url($text) {
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $text);
    $text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}

/****************************************************/


//Redirect
function artvelog_redirect() {
    $time = carbon_get_theme_option('delay_time');
    $ref = carbon_get_theme_option('redirect_ref');
    $trim_ref = remove_http_https($ref);

    $slug = carbon_get_theme_option('redirect_rewrite');
    $trim_slug = text_to_url($slug);

    $use_tag = carbon_get_theme_option('select_link_tag');
    ?>
    <script>
    jQuery(function($){
    $("<?php echo $use_tag ?>").click(function(e) {
        if(e.target.href.indexOf(location.hostname) != -1){
            return;
        }
        else{
            if (!$(this).attr("href").startsWith("#")) {
                e.preventDefault();
                var url = $(this).attr('href');
                var ref = "<?php echo $trim_ref ?>";
                var time = <?php echo $time ?>;
                var slug = "<?php echo $trim_slug ?>";
                window.location.href = "/" + slug + "?url=" + encodeURIComponent(url) + "&ref=" + ref + "&time=" + time;
            }
        }
    });
    });
    </script>
    <?php
}
add_action('wp_footer', 'artvelog_redirect');


//Redirect page rewrite rule
function artvelog_rewrite_rule() {
    $slug = carbon_get_theme_option('redirect_rewrite');
    $redirect_slug = '^' . text_to_url($slug) . '/?';
    add_rewrite_rule($redirect_slug , 'wp-content/plugins/artvelog-redirectpage/redirect-page.php' , 'top');
}
add_action('init', 'artvelog_rewrite_rule', 10, 0);

//Rewrite page flush
function artvelog_rewrite_activate() {
    flush_rewrite_rules();
    add_theme_support('align-wide');
}
register_activation_hook( __FILE__, 'artvelog_rewrite_activate' );