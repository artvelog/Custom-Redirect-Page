<?php

$page_id = get_option('custom_page_id');
$page_builder = get_option('custom_page_builder');
if ( $page_builder == 'elementor' ) {
    if ( class_exists( 'Elementor\Plugin' ) ) {
        add_action( 'wp_enqueue_scripts', function() {
            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            \Elementor\Plugin::$instance->frontend->enqueue_scripts();
        });
    }
} elseif ( $page_builder == 'beaver_builder' ) {
    ob_start();
    if ( class_exists( 'FLBuilder' ) ) {
        add_action( 'wp_enqueue_scripts', function() use ($page_id) {
           FLBuilder::enqueue_layout_styles_scripts($page_id);
        });
    }
    ob_start();
    $content = "";
    $page_template = get_page_template_slug( $page_id );
    $template = locate_template( $page_template );
    $path_in_theme_or_childtheme_or_compat = ( 0 === strpos( realpath( $template ), realpath( get_stylesheet_directory() ) ) || 0 === strpos( realpath( $template ), realpath( get_template_directory() ) ) || 0 === strpos( realpath( $template ), realpath( ABSPATH . WPINC . '/theme-compat/' ) ));
    if ( strlen( $template ) > 0 && $path_in_theme_or_childtheme_or_compat ) {
        include $template;
    } else {
        the_post();
        ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <?php
    }

    $content = ob_get_clean();
} elseif ( $page_builder == 'gutenberg' ) {
    if ( class_exists( 'UAGB_Post_Assets' ) ) {
        $post_assets_instance = new UAGB_Post_Assets( $page_id );
        $post_assets_instance->enqueue_scripts();
    }
} elseif ( $page_builder == 'visual_composer' ) {
    if ( defined( 'VCV_VERSION' ) ) {
        wp_enqueue_style( 'vcv:assets:front:style' );
        wp_enqueue_script( 'vcv:assets:runtime:script' );
        wp_enqueue_script( 'vcv:assets:front:script' );

        $bundle_url = get_post_meta( $page_id, 'vcvSourceCssFileUrl', true );
        if ( $bundle_url ) {
            $version = get_post_meta( $page_id, 'vcvSourceCssFileHash', true );
            if ( ! preg_match( '/^http/', $bundle_url ) ) {
                if ( ! preg_match( '/assets-bundles/', $bundle_url ) ) {
                    $bundle_url = '/assets-bundles/' . $bundle_url;
                }
            }
            if ( preg_match( '/^http/', $bundle_url ) ) {
                $bundle_url = set_url_scheme( $bundle_url );
            } elseif ( defined( 'VCV_TF_ASSETS_IN_UPLOADS' ) && constant( 'VCV_TF_ASSETS_IN_UPLOADS' ) ) {
                $upload_dir = wp_upload_dir();
                $bundle_url = set_url_scheme( $upload_dir['baseurl'] . '/' . VCV_PLUGIN_ASSETS_DIRNAME . '/' . ltrim( $bundle_url, '/\\' ) );
            } elseif ( class_exists( 'VisualComposer\Helpers\AssetsEnqueue' ) ) {
                // These methods should work for Visual Composer 26.0.
                // Enqueue custom css/js stored in vcvSourceAssetsFiles postmeta.
                $vc = new \VisualComposer\Helpers\AssetsEnqueue;
                if ( method_exists( $vc, 'enqueueAssets' ) ) {
                    $vc->enqueueAssets($page_id);
                }
                // Enqueue custom CSS stored in vcvSourceCssFileUrl postmeta.
                $upload_dir = wp_upload_dir();
                $bundle_url = set_url_scheme( $upload_dir['baseurl'] . '/' . VCV_PLUGIN_ASSETS_DIRNAME . '/' . ltrim( $bundle_url, '/\\' ) );
            } else {
                $bundle_url = content_url() . '/' . VCV_PLUGIN_ASSETS_DIRNAME . '/' . ltrim( $bundle_url, '/\\' );
            }
            wp_enqueue_style(
                'vcv:assets:source:main:styles:' . sanitize_title( $bundle_url ),
                $bundle_url,
                array(),
                VCV_VERSION . '.' . $version
            );
        }
    }

    // Visual Composer custom CSS.
    if ( defined( 'WPB_VC_VERSION' ) ) {
        // Post custom CSS.
        $post_custom_css = get_post_meta( $page_id, '_wpb_post_custom_css', true );
        if ( ! empty( $post_custom_css ) ) {
            $post_custom_css = wp_strip_all_tags( $post_custom_css );
            echo '<style type="text/css" data-type="vc_custom-css">';
            echo $post_custom_css;
            echo '</style>';
        }
        // Shortcodes custom CSS.
        $shortcodes_custom_css = get_post_meta( $page_id, '_wpb_shortcodes_custom_css', true );
        if ( ! empty( $shortcodes_custom_css ) ) {
            $shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
            echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
            echo $shortcodes_custom_css;
            echo '</style>';
        }
    }

} elseif ( $page_builder == 'siteorigin' ) {
    if ( class_exists( 'SiteOrigin_Panels' ) ) {
        $renderer = SiteOrigin_Panels::renderer();
        $renderer->add_inline_css( $page_id, $renderer->generate_css( $page_id ) );
    }
} else {
    
}
