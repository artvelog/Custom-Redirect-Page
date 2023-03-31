<?php
define('WP_USE_THEMES', false);
if(isset($_GET['base'])){
    $base_url = base64_decode( $_GET['base'] );
	$base_directory = $base_url;
    require_once( $base_directory . '/wp-blog-header.php');
}
else{
    require_once( $_SERVER[ 'DOCUMENT_ROOT' ] .'/wp-blog-header.php');
}


if(isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    if (strpos($referer, home_url()) !== false) {

    remove_action( 'wp_footer', 'artvelog_redirect' );
    
    require_once(plugin_dir_path( __FILE__ ) . '/inc/builder_support.php');

    function redirect_url_shortcode(){
        $redirect_url = $_GET['url'];
        return $redirect_url;
    }
    add_shortcode( 'rp_redirect_url', 'redirect_url_shortcode' );
        
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <title><?php echo carbon_get_theme_option('redirect_page_title'); ?></title>
    <?php wp_head(); ?>
    <style id="redirectpage_custom_style"><?php echo carbon_get_theme_option('redirectpage_custom_styles'); ?></style>
    <style>
        *{
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        body{ background: <?php echo carbon_get_theme_option('redirect_page_bg_color') ?>;}
        .wp-site-blocks{padding-top:0px; padding-bottom:0px;}
    </style>
</head>
<body>
    <?php wp_body_open(); ?>
    <div class="wp-site-blocks">
        <?php
            $args = array(
                'page_id' => carbon_get_theme_option('custom_page_id'),
                'post_type' => 'page',
                'post_status' => array( 'publish', 'private' ),
            );
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    the_content();
                }
            }
            wp_reset_postdata();
        ?>
    </div>
    <?php
    wp_footer();
    if (isset($_GET['url'])) {
        if(empty($_GET['time'])){
            $delay = 5;
        }
        else{
            $delay = $_GET['time'];
        }

        $full_url =  $_GET['url'] . "?ref=" . $_GET['ref'];
        ?>
        <script>
        jQuery(function($){
            setTimeout(function(){
                var redir_url = "<?php echo $full_url ?>";

                window.location.href = redir_url;
            }, <?php echo $delay ?> * 1000);
        });
        </script>
        <?php
    }
    else{
        ?>
        <script>
        jQuery(function($){
            var redir_url = "<?php bloginfo('url') ?>";
            window.location.href = redir_url;
        });
        </script>
        <?php
    }
    ?>
</body>
</html>
<?php 
}
else{
    wp_die( '<h1>' . __( '404 - Not Found', 'redirect-artvelog') . '</h1>', '404', array( 'response' => '404', 'back_link' => true ) );
}

} 
else {
    wp_die( '<h1>' . __( '404 - Not Found', 'redirect-artvelog') . '</h1>', '404', array( 'response' => '404', 'back_link' => true ) );
}
?>
