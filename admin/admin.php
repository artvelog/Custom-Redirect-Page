<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
add_action( 'carbon_fields_register_fields', 'artvelog_redirectpage_options_page' );
function artvelog_redirectpage_options_page() {
    Container::make( 'theme_options', __( 'Redirect Page Options', 'redirect-artvelog' ) )
        ->add_fields( array(
            Field::make( 'select', 'custom_page_id', __( 'Custom Redirect Page', 'redirect-artvelog') )
            ->add_options( crb_get_pages_array() )
            ->set_help_text( 'Please select the page whose content will be displayed on the redirect page. <b>Using a custom page would be more beneficial.</b> <br> <hr> Page ID Shortcode: <kbd>[rp_page_ID]</kbd>', 'redirect-artvelog' ),
            
            Field::make( 'select', 'custom_page_builder', 'Page Builder', 'redirect-artvelog' )
            ->add_options( array(
                'elementor' => 'Elementor',
                'beaver_builder' => 'Beaver Builder',
                'gutenberg' => 'Gutenberg',
                'visual_composer' => 'Visual Composer',
                'siteorigin' => 'SiteOrigin',
            ) )
            ->set_default_value( "gutenberg" )
            ->set_help_text( 'Select the plugin you used to create your page. <b style="color: red;">Make sure you choose the correct one. Otherwise, your page may not display correctly.</b>', 'redirect-artvelog' ),


            Field::make( 'text', 'redirect_page_title', __( 'Custom Redirect Page Title', 'redirect-artvelog' ) )
            ->set_help_text( 'Enter the title of the redirect page.  <br> <hr> Page Title Shortcode: <kbd>[rp_title]</kbd>', 'redirect-artvelog' )
            ->set_default_value( "Redirect Page" )
            ->set_required(true),

            Field::make( 'text', 'redirect_rewrite', __( 'Rewrite Page Slug' , 'redirect-artvelog') )
            ->set_help_text( 'Determine the extension of the redirect page. <b>By default, "redirect" is used.</b> <b style="color: red;">After changing it, you must save the changes from the Permalinks section.</b>', 'redirect-artvelog' )
            ->set_default_value( "redirect" )
            ->set_required(true),

            Field::make( 'text', 'delay_time', __( 'Delay Time', 'redirect-artvelog' ) )
            ->set_default_value( 5 )
            ->set_required(true)
            ->set_attribute( 'type', 'number' )
            ->set_attribute( 'step', '0.01' )
            ->set_help_text( 'Determine the delay time. <br> <hr> Delay Time Shortcode: <kbd>[rp_delay_time]</kbd>', 'redirect-artvelog' ),

            Field::make( 'text', 'select_link_tag', __( 'Usable Html Tag', 'redirect-artvelog' ) )
            ->set_help_text( 'Specify the class or ID of the "a" tag to be redirected. If you want to use all links directly, just write "a". <b style="color: red;">(Internal links will not be redirected!)</b> ', 'redirect-artvelog' )
            ->set_default_value( "a" )
            ->set_required(true),

            Field::make( 'text', 'redirect_ref', __( 'Reference Link or Text', 'redirect-artvelog' ) )
            ->set_help_text( 'Enter the reference text or link. <b>You can leave it blank.</b>', 'redirect-artvelog' )
            ->set_default_value( site_url() ),

            Field::make( 'color', 'redirect_page_bg_color', __( 'Background Color', 'redirect-artvelog' ) )
            ->set_alpha_enabled( true )
            ->set_help_text( 'You can set the background color.', 'redirect-artvelog' )
            ->set_default_value( '#fff' ),

            Field::make( 'textarea', 'redirectpage_custom_styles', __( 'Redierect Page Custom Style', 'redirect-artvelog' ) )
            ->set_help_text( 'You can add custom styles to the redirect page.', 'redirect-artvelog' )
            ->set_rows( 8 )
            ->set_attribute( 'placeholder', 'CSS Code...' ),

            Field::make( 'html', 'credits' )
            ->set_html(sprintf( 'If you wish, you can print the url address of the site to be redirected in your redirect page with this shortcode: <kbd>[rp_redirect_url]</kbd> <br> <hr> <h3>Redirect Page</h3> <p>© <a href="https://creative.artvelog.com">Plugin by Artvelog</a> - <a href="https://carbonfields.net/">Options Page by Carbon Fields.</a></p></br><h4><a href="https://github.com/artvelog/Custom-Redirect-Page">Github</a></h4>', __( 'https://emreertan.com or https://creative.artvelog.com' ) ) ),
    ) );

}
function crb_get_pages_array() {
    $args = array(
        'post_type' => 'page',
        'post_status' => array( 'publish', 'private' ),
    );
    $pages = get_pages( $args );
    $pages_array = array();
    foreach ( $pages as $page ) {
        $pages_array[ $page->ID ] = $page->post_title;
    }
    return $pages_array;
}

function rp_time_shortcode(){
    $time = carbon_get_theme_option('delay_time');
    return $time;
}
add_shortcode( 'rp_delay_time', 'rp_time_shortcode' );

function rp_pageID_shortcode(){
    $p_id = carbon_get_theme_option('custom_page_id');
    return $p_id;
}
add_shortcode( 'rp_page_ID', 'rp_pageID_shortcode' );

function rp_title_shortcode(){
    $title = carbon_get_theme_option('redirect_page_title');
    return $title;
}
add_shortcode( 'rp_title', 'rp_title_shortcode' );