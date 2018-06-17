<?php
/*
Plugin Name: WordPress - Filmes Review
Author: Marcus Camargo
Version: 1.0
License: MIT
Text Domain: wordpress_filmes_review
*/

require dirname(__FILE__).'/lib/class-tgm-plugin-activation.php';

class Filmes_reviews {
    private static $instance;

    public static function getInstance(){
        if( self::$instance == NULL ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'init', array($this, 'Filmes_reviews::register_post_type') );
        add_action( 'tgmpa_register', array($this, 'check_required_plugins') );
    }

    public static function register_post_type()
    {
        register_post_type( 'filmes_review', array(
            'labels' => array(
                'name' => 'Filmes Reviews',
                'singular_name' => 'Filme Review',
            ),
            'supports' => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail', 'custom-fields'],
            'public' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-format-video',
        ) );
    }

    public function check_required_plugins()
    {
        $plugins = array(
            array(
                'name' => 'Meta Box',
                'slug' => 'meta-box',
                'required' => true,
                'force_activation' => false,
                'force_desactivation' => false,

            )
        );

        $config = array(
            'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'install-required-plugins', // Menu slug.
            'parent_slug'  => 'plugins.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            /*
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
                'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
                // <snip>...</snip>
                'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
            */
        );
        tgmpa( $plugins, $config );
    }

    public static function activate()
    {
        self::register_post_type();
        flush_rewrite_rules();
    }
}

Filmes_reviews::getInstance();

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'Filmes_reviews::activate' );