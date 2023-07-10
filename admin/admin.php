<?php
class RedirectPage_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'art_create_settings' ) );
		add_action( 'admin_init', array( $this, 'art_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'art_setup_fields' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'art_admin_styles') ); 
	}

    function art_admin_styles() {
        $currentScreen = get_current_screen();
        if( $currentScreen->id === "toplevel_page_RedirectPageOptions" ) {
            wp_enqueue_style( 'settings-style', plugins_url( '/assets/css/settings.css', __FILE__ ));
        }
    }

	public function art_create_settings() {
		$page_title = __( 'Redirect Page Options', 'redirect-artvelog');
		$menu_title = __( 'Redirect Page Options', 'redirect-artvelog');
		$capability = 'manage_options';
		$slug = 'RedirectPageOptions';
		$callback = array($this, 'art_settings_content');
        $icon = 'dashicons-smiley';
		$position = 80;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
		
	}
    
	public function art_settings_content() { ?>
		<div class="wrap">
			<h1>Redirect Page Options</h1>
            <p class="p_description">You can edit the custom redirect page as you wish.</p>
            <?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
                    submit_button();
					settings_fields( 'RedirectPageOptions' );
					do_settings_sections( 'RedirectPageOptions' );
                    ?>
                    <hr>
                    <?php
                        _e( '<h4>Other Options:</h4>
                        If you wish, you can print the url address of the site to be redirected in your redirect page with this shortcode: <kbd>[rp_redirect_url]</kbd> <br>
                        If you do not want your link to be redirected to the "redirect page", you can use this class. <kbd>not-redirect</kbd> <br>', 'redirect-artvelog');

					submit_button();
				?>
			</form>
            <div class="credit-footer">
                <h3>Custom Redirect Page</h3> <p>© <a href="https://creative.artvelog.com">Plugin by Artvelog</a></p><h4><a href="https://github.com/artvelog/Custom-Redirect-Page">Github</a></h4>
            </div>
		</div> <?php
	}

	public function art_setup_sections() {
		add_settings_section( 'RedirectPageOptions_section', '', array(), 'RedirectPageOptions' );
	}   
  
    
	public function art_setup_fields() {
		
        $args = array(
            'post_type' => 'page',
            'post_status' => array( 'publish', 'private' ),
        );
        $pages = get_pages( $args );
        $options = array();
        foreach ( $pages as $page ) {
            $options[ $page->ID ] = $page->post_title;
        }

		$fields = array(
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Custom Redirect Page', 'redirect-artvelog'),
                        'placeholder' => 'Select Custom Page',
                        'id' => 'custom_page_id',
                        'desc' => __( 'Please select the page whose content will be displayed on the redirect page. <b>Using a custom page would be more beneficial.</b> <br> <hr> Page ID Shortcode: <kbd>[rp_page_ID]</kbd>', 'redirect-artvelog'),
                        'type' => 'select',
                        'options' => $options
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Page Builder', 'redirect-artvelog'),
                        'placeholder' => __( 'Select Page Builder', 'redirect-artvelog'),
                        'id' => 'custom_page_builder',
                        'desc' => __( 'Select the plugin you used to create your page. <b class="text_red">Make sure you choose the correct one. Otherwise, your page may not display correctly.</b>', 'redirect-artvelog'),
                        'type' => 'select',
                        'options' => array(
                            'elementor' => 'Elementor',
                            'beaver_builder' => 'Beaver Builder',
                            'gutenberg' => 'Gutenberg',
                            'visual_composer' => 'Visual Composer',
                            'siteorigin' => 'SiteOrigin',
                        ),
                        'default' => 'gutenberg',
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Custom Redirect Page Title', 'redirect-artvelog'),
                        'placeholder' => __( 'Title...', 'redirect-artvelog'),
                        'id' => 'redirect_page_title',
                        'desc' => __( 'Enter the title of the redirect page.  <br> <hr> Page Title Shortcode: <kbd>[rp_title]</kbd>', 'redirect-artvelog'),
                        'type' => 'text',
                        'default' => 'My Custom Redirect Page',
                        'require' => true,
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Rewrite Page Slug', 'redirect-artvelog'),
                        'placeholder' => __( 'Rewrite Page Slug...', 'redirect-artvelog'),
                        'id' => 'redirect_rewrite',
                        'desc' => __( 'Determine the extension of the redirect page. <b>By default, "redirect" is used.</b> <b class="text_red">After changing it, you must save the changes from the Permalinks section.</b>', 'redirect-artvelog'),
                        'type' => 'text',
                        'default' => 'redirect',
                        'require' => true,
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Delay Time', 'redirect-artvelog'),
                        'placeholder' => __( 'Delay Time...', 'redirect-artvelog'),
                        'id' => 'delay_time',
                        'desc' => __( 'Determine the delay time. <br> <hr> Delay Time Shortcode: <kbd>[rp_delay_time]</kbd>', 'redirect-artvelog'),
                        'type' => 'number',
                        'default' => '5',
                        'require' => true,
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Usable Html Tag', 'redirect-artvelog'),
                        'placeholder' => __( 'Usable Html Tag...', 'redirect-artvelog'),
                        'id' => 'select_link_tag',
                        'desc' => __( 'Specify the class or ID of the "a" tag to be redirected. If you want to use all links directly, just write "a". <b class="text_red">(Internal links will not be redirected!)</b>', 'redirect-artvelog'),
                        'type' => 'text',
                        'default' => 'a',
                        'require' => true,
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Reference Link or Text', 'redirect-artvelog'),
                        'placeholder' => __( 'Reference Link or Text...', 'redirect-artvelog'),
                        'id' => 'redirect_ref',
                        'desc' => __( 'Enter the reference text or link. <b>You can leave it blank.</b>', 'redirect-artvelog'),
                        'type' => 'text',
                        'default' => site_url(),
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Background Color', 'redirect-artvelog'),
                        'placeholder' => '#ffff',
                        'id' => 'redirect_page_bg_color',
                        'desc' => __( 'You can set the background color.', 'redirect-artvelog'),
                        'type' => 'color',
                        'default' => '#ffffff',
                    ),
        
                    array(
                        'section' => 'RedirectPageOptions_section',
                        'label' => __( 'Redierect Page Custom Style', 'redirect-artvelog'),
                        'placeholder' => __( 'CSS', 'redirect-artvelog'),
                        'id' => 'redirectpage_custom_styles',
                        'desc' => __( 'You can add custom styles to the redirect page.', 'redirect-artvelog'),
                        'type' => 'textarea',
                    )
		);

        // Fields label callback function
        function label_cb( $field) {
            $_value = '';
            if($field['require'] == true){
                $_value = $field['label'] . '<span class="require_field">*</span>';
            }
            else{
                $_value = $field['label'];
            }
            return $_value;
        }

        // Fields default value function
        function default_value($field){
            if ( isset($field['default']) ) {
                if(!empty($field['default'])){
                    if(get_option( $field['id'] ) === false){
                        update_option( $field['id'], $field['default'] );
                    }
                }
            }
        }

		foreach( $fields as $field ){
			add_settings_field( $field['id'], label_cb( $field ) , array( $this, 'field_cb' ), 'RedirectPageOptions', $field['section'], $field);
			register_setting( 'RedirectPageOptions', $field['id'], function($value) use ($field) {
                return validate_field($value, $field);
            });
		}

        // test
        //delete_option('');

        
        function validate_field($value, $input){
            /*$value = default_value($input, $value);*/
            $value = require_field($input, $value, '<kbd>' .$input['label'] . '</kbd> ' . 'Bu alan boş bırakılamaz.');
            return $value;
        }

        function require_field($input, $field_value, $error = null){
            if($input['require'] == true){
                if(empty($field_value)){
                    add_settings_error('RedirectPageOptions_section', 'require_error', $error);
                    $field_value = get_option( $input['id'] );
                }
            }
            return $field_value;
        }
	}

	public function field_cb( $field ) {
        default_value($field);
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
                        case 'select':
                            case 'multiselect':
                                if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
                                    $attr = '';
                                    $options = '';
                                    foreach( $field['options'] as $key => $label ) {
                                        $options.= sprintf('<option value="%s" %s>%s</option>',
                                            $key,
                                            selected($value, $key, false),
                                            $label
                                        );
                                    }
                                    if( $field['type'] === 'multiselect' ){
                                        $attr = ' multiple="multiple" ';
                                    }
                                    printf( '<select name="%1$s" id="%1$s" %2$s>%3$s</select>',
                                        $field['id'],
                                        $attr,
                                        $options
                                    );
                                }
                                break;

                        case 'textarea':
                            printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
                                $field['id'],
                                $placeholder,
                                $value
                                );
                                break;
            
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
                ?><div class="description"><?php
                echo $desc;
                ?></div><?php
			}
		}
	}
    
}
new RedirectPage_Settings_Page();

function rp_time_shortcode(){
    $time = get_option('delay_time');
    return $time;
}
add_shortcode( 'rp_delay_time', 'rp_time_shortcode' );

function rp_pageID_shortcode(){
    $p_id = get_option('custom_page_id');
    return $p_id;
}
add_shortcode( 'rp_page_ID', 'rp_pageID_shortcode' );

function rp_title_shortcode(){
    $title = get_option('redirect_page_title');
    return $title;
}
add_shortcode( 'rp_title', 'rp_title_shortcode' );